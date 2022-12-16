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
                "</code> ➞  " .$param['type']. (($param['required'])? '<i>required</i>' : ''). " <p> ◽️ ". 
                $param['description']. 
                "</p></li>";
        }
        return [
            "methodParam" => $list,
            "codeUsageList" => $codeUsage,
            "methodParamDescCount" => $methodParamCount,
            "methodParamDesc" => str_replace("\n","", "<details><summary>show endpoint parameters</summary>". 
                "<ol>" . implode("", $methodParamDesc) . "</ol></details>"),
            "paramDesc" => str_replace("\n","","<details><summary>show url parameters</summary>". 
                "<ol>" . implode("", $paramDesc) . "</ol></details>")
        ];
    };

    $createSampleCode = function(string $endpointMethod = "", array $urlMethods = []) {
        $clientObject = '$result = $apiClient->set()->';
        if(!isset($urlMethods[0])) {
            return "$clientObject" . $endpointMethod . "->send();";
        }
        $space4s = "    ";
        $urlQuery = '
            $q->' . implode('
        '.$space4s.$space4s.'->', $urlMethods) ."";
        return "" . $clientObject . $endpointMethod . "
        " . "->send($urlQuery
        );";
    };

    $table = "";
    $lastTag = "";
    $linkId = "";
    $endpointCtr = 0;
    $endpointTableOfContentItem = "";
    $enpointTitle = "<a href='#table-of-contents'>_TT_</a> endpoint : ";
    // $tableHeader = "\n\n# <h2 id='%s'>_WW_ %s </h2>\n| ENDPOINT <a href='#table-of-contents'>_TT_</a>| |\n|---:|:---|\n";
    $tableHeader = "\n\n\n\n# <h2 id='%s'>_WW_ %s </h2>\n <br>";

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
        $endpointDescription = "\n\n\n>\n>" . 
            $currentPath["get"]["summary"] .
            "\n>\n\n";

        $endpointMethod = $createEndpointMethod($endpoint);
        if (
            !isset($currentPath["get"]["parameters"]) 
            || empty($currentPath["get"]["parameters"])
        ) {
            $table .= "<b>$endpointCtr.</b> $enpointTitle `$endpoint`\n$endpointDescription\n";
            $table .= "   \n[ method ] : <br>`" . $endpointMethod . "` \n\n";
            $table .= "<i>_BB_ sample usage</i>\n\n```php\n" . $createSampleCode($endpointMethod, []) ."\n```\n<br>";
            continue;
        }

        $params = $currentPath["get"]["parameters"];
        $params = $extractEndpointData($params);
        $paramFormatted = $params['methodParam'];
        $paramsCount = count($paramFormatted);

        $endpointParamDesc = $endpointDescription .
            ($params['methodParamDescCount'] > 0 ? $params['methodParamDesc']  : '');

        $parameterDetail = ($paramsCount > 0) ? (
            "\n<i>URL Keys</i> : $paramsCount<br>" . 
            implode("<br>", $paramFormatted) .
            "<br><br>" . $params["paramDesc"]
        )  : '';

        $table .= "<b>$endpointCtr.</b> $enpointTitle `$endpoint`\n$endpointParamDesc\n";
        $table .= "   \n[ method ] : <br>`" . $endpointMethod . "`\n\n";
        
        if(!empty(trim($parameterDetail))) {
            $table .= "🗝 $parameterDetail <br>";
        }

        $table .= "<i>_BB_ sample usage</i>\n\n```php\n". $createSampleCode($endpointMethod, $params['codeUsageList']) . "\n```\n<br>";
    }

    $table = "\n\n<h1 id='table-of-contents'> _TT_Endpoint List</h1>\n\n<ol>". 
        $endpointTableOfContentItem ."</ol>\n\n".
        $table;
    return $table;

}

//Run in smallest font term
//replace the broken emojis
print(createReadmeTable($api));
