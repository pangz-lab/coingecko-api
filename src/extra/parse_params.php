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
        if(str_starts_with($value, "('{")) { return $value;}
        return lcfirst(
            implode(
                "",
                array_map(
                    function ($v) {
                        return ucfirst(strtolower($v));
                    },
                    explode("_", $value)
                )
            )
        );
    };

    $createEndpointMethod = function(string $endpoint) use ($ucFirster) {
        $method = [];
        $endpoints = explode("/",substr($endpoint, 1));
        foreach($endpoints as $value) {
            if(str_starts_with($value, "{")) {
                $value = "('$value')";
            }
            $method[] = $ucFirster($value). "()";
        }

        return str_replace(
            ["}''{", "')(", ")->("], 
            ["}','{", "'", ""],
            implode("->", $method)
        );
    };

    $extractEndpointData = function(array $paramList) use ($ucFirster) {
        $list = [];
        $methodParamCount = 0;
        $methodParamDesc = [];
        $paramDesc = [];
        $codeUsage = [];
        foreach($paramList as $key => $param) {
            if($param['in'] != 'query') {
                $methodParamDesc[] =  "<li><code> " . 
                $param['name'] . 
                "</code> <p> ➞ ". 
                $param['description']. 
                "</p></li>";
                $methodParamCount++;
                continue;
            }
            $methodName = 'with' . ucfirst($ucFirster($param['name'])) . "('" . $param['type'] . "')";
            $codeUsage[] = "$methodName";
            $list[] = ' ✔️  <code>' . $methodName .                 
                "</code>" . (($param['required'])? '❗️' : '');

            $paramDesc[] =  "<li><code> " . 
                $param['name'] . 
                "</code> ➞  " .$param['type']. " <p> ◽️ ". 
                $param['description']. 
                "</p></li>";
        }
        return [
            "methodParam" => $list,
            "codeUsageList" => $codeUsage,
            "methodParamDescCount" => $methodParamCount,
            "methodParamDesc" => str_replace("\n","", "<details><summary>see description</summary>". 
                "<ol>" . implode("", $methodParamDesc) . "</ol></details>"),
            "paramDesc" => str_replace("\n","","<details><summary>see description</summary>". 
                "<ol>" . implode("", $paramDesc) . "</ol></details>")
        ];
    };

    $createSampleCode = function(string $endpointMethod = "", array $urlMethods = []) {
        $clientObject = '$result = \$apiClient->set()->';
        if(!isset($urlMethods[0])) {
            return "<code><pre>$clientObject" . $endpointMethod . "->send();</pre></code>";
        }
        $space4s = "    ";
        $urlQuery = '<br>' .$space4s.$space4s. '$q->' . implode('<br>'.$space4s.$space4s.'->', $urlMethods) ."<br>$space4s";
        return "<code><pre>" . $clientObject . $endpointMethod . "<br>" . $space4s . "->send($urlQuery);</pre></code>";
    };

    $table = "";
    $lastTag = "";
    $linkId = "";
    $endpointCtr = 0;
    $endpointTableOfContentItem = "";
    $tableHeader = "\n\n# <h2 id='%s'>_WW_ %s </h2>\n| ENDPOINT <a href='#table-of-contents'>_TT_</a>| |\n|---:|:---|\n";

    foreach ($apiDataArray["paths"] as $endpoint => $currentPath) {
        if($currentPath["get"]["tags"][0] != $lastTag) {
            $lastTag = $currentPath["get"]["tags"][0];
            $linkId = "cust-" . str_replace([" ", '(',')'],'-', $lastTag);
            $table .= vsprintf($tableHeader, [$linkId, $lastTag]);
            
            $endpointTableOfContentItem .= '<li><a href="#'.$linkId.'">'.
                $lastTag.'</a></li>';
            $endpointCtr = 0;
        }
        
        $endpointCtr++;
        $endpointDescription = "<br><br><blockquotes>" . 
            $currentPath["get"]["summary"] .
            "<blockquotes><br><br>";
        $endpointMethod = $createEndpointMethod($endpoint);
        if (
            !isset($currentPath["get"]["parameters"]) 
            || empty($currentPath["get"]["parameters"])
        ) {
            $table .= "| <b>$endpointCtr.</b>   | `$endpoint`  |\n";
            $table .= "|                        | <code>" . 
                $endpointMethod . 
                "</code> $endpointDescription |\n";
            $table .= "| <i>sample</i> | " . $createSampleCode($endpointMethod, []) . 
                "|\n";
            continue;
        }

        $params = $currentPath["get"]["parameters"];
        $params = $extractEndpointData($params);
        $paramFormatted = $params['methodParam'];
        $paramsCount = count($paramFormatted);

        $endpointParamDesc = $endpointDescription .
            ($params['methodParamDescCount'] > 0 ? $params['methodParamDesc']  : '');

        $parameterDetail = ($paramsCount > 0) ? (
            "<i>URL Keys</i> : $paramsCount<br><br>" . 
            implode("<br>", $paramFormatted) .
            "<br><br>" . $params["paramDesc"]
        )  : '';

        $table .= "| <b>$endpointCtr.<b>   | `$endpoint` |\n";
        $table .= "|                       | <code>" . $createEndpointMethod($endpoint) . "</code> $endpointParamDesc |\n";
        
        if(!empty(trim($parameterDetail))) {
            $table .= "|                  | $parameterDetail |\n";
        }

        $table .= "| <i>sample</i> |".
            $createSampleCode($endpointMethod, $params['codeUsageList']) . "|\n";
    }

    $table = "\n\n<h1 id='table-of-contents'> _TT_Endpoint List</h1>\n\n<ol>". 
        $endpointTableOfContentItem ."</ol>\n\n".
        $table;
    return $table;

}

//Run in smallest font term
//replace the broken emojis
print(createReadmeTable($api));
