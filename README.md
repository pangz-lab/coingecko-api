# coingecko-api
## Coingecko API client for PHP
<br>

A simple, intuitive and composable API Client for the [CoinGecko REST API Service](https://www.coingecko.com/en/api/documentation).

API Version Support
---------------
- ✔️ API v3
- ✔️ Community Endpoint
- ✔️ Pro Endpoint

Requirements
---------------

- ✔️ php: 8.x
- ✔️ guzzlehttp/guzzle: ^7.5

Installation
---------------
<p>
The best and the easiest way to use this library is thru <a href="https://getcomposer.org/">composer</a>.
You can also download the source directly and require it to your project.
<br>
<br>
Just take note of the requirements.
</p>

### [ *Composer* ]
![composer](https://getcomposer.org/favicon.ico)
[ see packagist.org](https://packagist.org/packages/pangzlab/coingecko-api)
```
composer require pangzlab/coingecko-api
```

OR

### [ *Direct Download* ]

#### 📥 [Get it from the release](https://github.com/pangz-lab/coingecko-api/releases)

<br>

Preface
---------------
<p>
All REST API endpoints can either require a <a href="https://www.rfc-editor.org/rfc/rfc3986#section-3.4">request query</a>
in the URL or none at all depending on the API design.
<br>
<br>
CoinGecko API supports both types.
<br>
</p>

---
<br>

Library
---------------
### [ Classes ]
<p>
This library provides 2 main classes which you can use depending on the type of endpoints you are accessing.
<list>
    <li>CoinGeckoUrlBuilder</li>
    <li>CoinGeckoApiClient</li>
</list>
</p>

<br>

#### 📦 CoinGeckoApiClient
<p>
This is the main class that allows building the API endpoints and sending the request.
<br>
<br>
This is always required.
</p>

```php
<?php

use PangzLab\CoinGecko\Client\CoinGeckoApiClient;

$client = new CoinGeckoApiClient();

```

#### 📦 CoinGeckoUrlBuilder
<p>
CoinGecko API endpoints require a URL query which is a set of key value pairs encoded in the URL.
This class enables to dynamically create them with ease without worrying the position
or the casing of the keys.
<br>
<br>
Although by design can be achieved by using the CoinGeckoApiClient class alone,
using the CoinGeckoUrlBuilder gives you a finer control over the parameters you set and more flexibility in managing the endpoints you're building.
<br>
<br>
This class is optional depending on the endpoint you need.
</p>

```php
<?php

use PangzLab\CoinGecko\Client\CoinGeckoUrlBuilder;

$query = new CoinGeckoUrlBuilder();

```

Usage
---------------
<p>
Accessing the API endpoints are fairly straightforward. If not for convenience, you don't really need a set of manuals to start using this library.
You can just go directly to the <a href="https://www.coingecko.com/en/api/documentation">CoinGecko REST API Official Documenation</a>, find the endpoint you need and start building your request.
<br>
<br>
Let's use some examples.
<br>
</p>

#### 🔹 Endpoint request "without"🚫 URL query
🌐 endpoint: [/ping](https://www.coingecko.com/api/documentations/v3#/ping/get_ping)

```php
<?php

use PangzLab\CoinGecko\Client\CoinGeckoApiClient;

$client = new CoinGeckoApiClient();

try {
    $response = $client->set()->ping()->send();
    print_r($response);
    // do something here..

} catch (Exception $e) {
    print($e->getMessage());
}

```

#### 🔹 Endpoint request "with"✔️ URL query
🌐 endpoint: [/coins/categoreis](https://www.coingecko.com/api/documentations/v3#/categories/get_coins_categories)

```php
<?php

use PangzLab\CoinGecko\Client\CoinGeckoApiClient;
use PangzLab\CoinGecko\Client\CoinGeckoApiClient;

$q = new CoinGeckoUrlBuilder();
$client = new CoinGeckoApiClient();

try {
    $response = $apiClient->set()
        ->coins()
        ->categories()
        ->send($q->withOrder("name_desc"));
    print_r($response);
    // do something here ...

} catch (Exception $e) {
    print($e->getMessage());
}

```

#### 🔹 Endpoint request with path parameter(id)
🌐 endpoint: [/exchanges/{id}/volume_chart](https://www.coingecko.com/api/documentations/v3#/exchanges/get_exchanges__id__volume_chart)
```php
<?php

use PangzLab\CoinGecko\Client\CoinGeckoApiClient;
use PangzLab\CoinGecko\Client\CoinGeckoUrlBuilder;

$q = new CoinGeckoUrlBuilder();
$client = new CoinGeckoApiClient();

try {
    $response = $apiClient->set()
        ->exchanges("safe_trade")
        ->volumeChart()
        ->send($q->withDays(1));
    print_r($response);
    // do something here ...

} catch (Exception $e) {
    print($e->getMessage());
}

```

#### 🔹 Endpoint request with path parameter(id) and URL Query
🌐 endpoint: [/coins/{id}](https://www.coingecko.com/api/documentations/v3#/coins/get_coins__id_)
```php
<?php

use PangzLab\CoinGecko\Client\CoinGeckoApiClient;
use PangzLab\CoinGecko\Client\CoinGeckoUrlBuilder;

$q = new CoinGeckoUrlBuilder();
$client = new CoinGeckoApiClient();

try {
    $response = $apiClient->set()
        ->coins("verus-coin")
        ->send(
            $q->withLocalization("false")
            ->withDeveloperData("true")
            ->withSparkline("true")
            ->withCommunityData("true")
            ->withMarketData("true")
            ->withTickers("true")
        );
    print_r($response);
    // do something here ...

} catch (Exception $e) {
    print($e->getMessage());
}

```

Features
---------------

> <br>
> 
> ## Set Method
>
> * Before forming the endpoint, always start calling the **set()** method 
> to make a clean object before building a request.
>
> ## Parameter Positioning
>
> * The parameter position is not important.
> It can be set anywhere as long as it is required by the endpoint.
>
> ## Send Methods ( Community vs Pro )
> * There are 2 methods provided to send a request. The **send()** and the **sendPro()**
> * **send** ⇨ used to send a request to <a href="https://www.coingecko.com/en/api/documentation">Community API endpoints</a>.
> * **sendPro** ⇨ used to send a request to the exclusive <a href="https://coingeckoapi.notion.site/coingeckoapi/CoinGecko-Pro-API-exclusive-endpoints-529f4bb5c4d84d5fad797b09cfdb4b53">Pro API endpoints</a>.
> This method requires the **x_cg_pro_api_key** parameter key encoded in the URL for the request to be accepted.
>
> * Both optionally accepts instance of **CoinGeckoUrlBuilder()** class.
> * Aside from the **x_cg_pro_api_key** parameter key, there is no major difference
> between these 2 methods. Both are used the same way.
>
> ## CoinGeckoUrlBuilder() Method Call **with** Prefix
> * Method name for building a query string using **CoinGeckoUrlBuilder()** always
> have a prefix **with**.
>
>> (i.e. withId() for id, withVsCurrency() for vs_currency)
>
> <br>

<br>

Bonus Quirks
---------------
<p>
There are some benefits of using this CoinGecko client libray.
<br>
<br>
Aside from it's not required to learn any methods to use 
and the parameter positioning of each methods, 
there are other features which might not be essential but are available and ready to be used
to provide manageability and flexibility to your coding.
</p>

#### 1. Use of reset() instead of set()
> ⚠️ Calling **set()** is always the preferred way but 
> you can also build request using the **reset()** method to cleanup resource. Just make sure to separate
> the call to **send()** method to avoid **ParseError**.
```php
<?php

use PangzLab\CoinGecko\Client\CoinGeckoApiClient;
use PangzLab\CoinGecko\Client\CoinGeckoUrlBuilder;

$q = new CoinGeckoUrlBuilder();
$client = new CoinGeckoApiClient();

try {
    //No set() method and no send() call
    $apiClient = $apiClient->coins("verus-coin");
    //Separate the call to send
    $response = $apiClient->send(
        $q->withLocalization("false")
        ->withDeveloperData("true")
        ->withSparkline("true")
        ->withCommunityData("true")
        ->withMarketData("true")
        ->withTickers("true")
    );
    print_r($response);

    //Call reset() method to form another request
    $apiClient->reset();
    $apiClient = $apiClient->exchanges("safe_trade")
        ->volumeChart();
    $response = $apiClient->send($q->withDays(1));
    //Call reset() for the next calls
    $apiClient->reset();
    print_r($response);

} catch (Exception $e) {
    print($e->getMessage());
}

```

#### 2. Case-insensitive method names
> <br>
> 
> Endpoint name and URL parameter key name are case-insensitive.
> This means calling ping(), PING() and Ping() are treated the same thing.
> Additionally you can also use underscore(_) as a separator for names
> like VS_CURRENCY or vs_Currency. They will be handled properly.
> 
> <br>
```php
<?php

use PangzLab\CoinGecko\Client\CoinGeckoApiClient;
use PangzLab\CoinGecko\Client\CoinGeckoUrlBuilder;

$q = new CoinGeckoUrlBuilder();
$client = new CoinGeckoApiClient();

try {
    
    //All caps
    $response = $apiClient->set()
        ->PING()
        ->send();
    print_r($response);

    //All lowercase
    $response = $apiClient->set()
        ->ping()
        ->send();
    print_r($response);

    //UC First
    $response = $apiClient->set()
        ->Ping()
        ->send();
    print_r($response);

    //With underscore - only underscore is allowed
    $response = $apiClient->set()
        ->_PING_()
        ->send();
    print_r($response);

    //With insensitive parameter case
    $response = $apiClient
        ->coins("verus-coin")
        ->send(
            $q->with_LOCALIZATION("false")
            ->withDeveloperData_("true")
            ->withSparkline("true")
            ->withCommunity_DATA("true")
            ->with_Market_Data("true")
            ->withTiCKers("true")
        );
    print_r($response);

} catch (Exception $e) {
    print($e->getMessage());
}

```


#### 3. Request reusability
```php
<?php

use PangzLab\CoinGecko\Client\CoinGeckoApiClient;
use PangzLab\CoinGecko\Client\CoinGeckoUrlBuilder;

$q = new CoinGeckoUrlBuilder();
$client = new CoinGeckoApiClient();

try {
    $request = $apiClient->set()->ping();

    for($x = 0; $x <= 10; $x++) {
        $response = $request->send();
        sleep(3);
        print_r($response);
    }
} catch (Exception $e) {
    print($e->getMessage());
}

```

<br>

API Usage
---------------
## (🚧 Under Construction)