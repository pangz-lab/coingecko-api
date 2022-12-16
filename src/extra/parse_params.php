<?php
declare(strict_types=1);

$api = json_decode(file_get_contents("./coingecko-api.v3.json"), true);

function getParameterList($apiDataArray) {
    $fileParameterList = [];
    foreach ($apiDataArray["paths"] as $currentPath) {
        if (!isset($currentPath["get"]["parameters"]) || empty($currentPath["get"]["parameters"])) {
            continue;
        }

        foreach ($currentPath["get"]["parameters"] as $key => $param) {
            $fileParameterList[$param["name"]] = $param;
        }
    }

    print_r($fileParameterList);
    print_r(count($fileParameterList));
    print_r(array_keys($fileParameterList));
}

function createReadmeTable(array $apiDataArray): string {
    $ucFirster = function ($value) {
        return lcfirst(implode(
            "",
            array_map(function ($v) {
                return ucfirst(strtolower($v));
            },
        explode("_", $value))));
    };

    $createMethodName = function(string $endpoint) use ($ucFirster) {
        $method = [];
        $endpoints = explode("/",substr($endpoint, 1));
        foreach($endpoints as $value) {
            if(str_starts_with($value, "{")) {
                $value = "('$value')";
            }
            $method[] = $ucFirster($value). "()";
        }

        return str_replace(
            "}''{", "}','{",
             str_replace(
                "')(", "'",
                str_replace(")->(", "", implode("->", $method))
            )
        );
    };

    $createParameterList = function(array $paramList) use ($ucFirster) {
        $list = [];
        $methodParamDescCount = 0;
        $methodParamDesc = [];
        $paramDesc = [];
        foreach($paramList as $key => $param) {
            if($param['in'] != 'query') {
                $methodParamDesc[] =  "<li><code> " . 
                $param['name'] . 
                "</code> <p> ➞ ". 
                str_replace("\n","", $param['description']) . 
                "</p></li>";
                $methodParamDescCount++;
            }
            $list[] = ' ✔️  <code>with' . 
                ucfirst($ucFirster($param['name'])) . 
                "('" . $param['type'] . "')" . 
                "</code>" . (($param['required'])? '❗️' : '');

            $paramDesc[] =  "<li><code> " . 
                $param['name'] . 
                "</code> ➞  " .$param['type']. " <p> ◽️ ". 
                $param['description']. 
                "</p></li>";
        }
        return [
            "methodParam" => $list,
            "methodParamDescCount" => $methodParamDescCount,
            "methodParamDesc" => str_replace("\n","", "<details><summary>see description</summary>". 
                "<ol>" . implode("", $methodParamDesc) . "</ol></details>"),
            "paramDesc" => str_replace("\n","","<details><summary>see description</summary>". 
                "<ol>" . implode("", $paramDesc) . "</ol></details>")
        ];
    };

    $table = "";
    $lastTag = "";
    $linkId = "";
    $endpointCtr = 0;
    $endpointTableOfContent = "";
    $tableHeader = "\n\n# <h2 id='%s'>🌐 %s </h2>\n| ENDPOINT <a href='#table-of-contents'>_TT_</a>| |\n|:---|:---|\n";

    foreach ($apiDataArray["paths"] as $endpoint => $currentPath) {
        if($currentPath["get"]["tags"][0] != $lastTag) {
            $lastTag = $currentPath["get"]["tags"][0];
            $linkId = "cust-" . str_replace([" ", '(',')'],'-', $lastTag);
            $table .= vsprintf($tableHeader, [$linkId, $lastTag]);
            
            $endpointTableOfContent .= '<li><a href="#'.$linkId.'">'.
                $lastTag.'</a></li>';
            $endpointCtr = 0;
        }
        
        $endpointCtr++;
        $description = "<br><br><blockquotes>" . $currentPath["get"]["summary"] ."<blockquotes><br><br>";
        if (!isset($currentPath["get"]["parameters"]) || empty($currentPath["get"]["parameters"])) {
            $table .= "| <b>$endpointCtr.</b>   | `$endpoint`  |\n";
            $table .= "|                   | <code>" . $createMethodName($endpoint) . "</code> $description |\n";
            continue;
        }
        $params = $currentPath["get"]["parameters"];
        $params = $createParameterList($params);
        $paramFormatted = $params['methodParam'];
        $paramsCount = count($paramFormatted);

        $endpointParamDesc = $description .
            ($params['methodParamDescCount'] > 0 ? $params['methodParamDesc']  : '');
        $parameterDetail = ($paramsCount > 0) ? (
            "<i>URL Keys</i> : $paramsCount<br> " . 
            implode("<br>", $paramFormatted) .
            "<br><br>" . $params["paramDesc"]
        )  : '';

        $table .= "| <b>$endpointCtr.<b>   | `$endpoint` |\n";
        $table .= "|                  | <code>" . $createMethodName($endpoint) . "</code> $endpointParamDesc |\n";
        $table .= "|                  | $parameterDetail |\n";
    }

    $table = "\n\n<h1 id='table-of-contents'> _TT_Endpoint List</h1>\n\n<ol>". $endpointTableOfContent ."</ol>\n\n".$table;
    return $table;

}

//Run in smallest font term
//replace the broken emojis
print(createReadmeTable($api));
