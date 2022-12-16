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
<br>

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
<br>
<br>

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

<br>
<br>

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
<br>
<br>

Features
---------------
<br>
 
### Set Method
* Before forming the endpoint, always start calling the **set()** method 
to make a clean object before building a request.
### Parameter Positioning

* The parameter position is not important.
It can be set anywhere as long as it is required by the endpoint.

### Send Methods ( Community vs Pro )

* There are 2 methods provided to send a request. The **send()** and the **sendPro()**
* **send** ⇨ used to send a request to <a href="https://www.coingecko.com/en/api/documentation">Community API endpoints</a>.
* **sendPro** ⇨ used to send a request to the exclusive <a href="https://coingeckoapi.notion.site/coingeckoapi/CoinGecko-Pro-API-exclusive-endpoints-529f4bb5c4d84d5fad797b09cfdb4b53">Pro API endpoints</a>.
This method requires the **x_cg_pro_api_key** parameter key encoded in the URL for the request to be accepted.

* Both optionally accepts instance of **CoinGeckoUrlBuilder()** class.
* Aside from the **x_cg_pro_api_key** parameter key, there is no major difference
between these 2 methods. Both are used the same way.

### CoinGeckoUrlBuilder() Method Call **with** Prefix
* Method name for building a query string using **CoinGeckoUrlBuilder()** always
have a prefix **with**.

* (i.e. withId() for id, withVsCurrency() for vs_currency)

<br>
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


<h1 id='table-of-contents'> 📋Endpoint List</h1>

<ol><li><a href="#cust-ping">ping</a></li><li><a href="#cust-simple">simple</a></li><li><a href="#cust-coins">coins</a></li><li><a href="#cust-contract">contract</a></li><li><a href="#cust-coins">coins</a></li><li><a href="#cust-asset_platforms">asset_platforms</a></li><li><a href="#cust-categories">categories</a></li><li><a href="#cust-exchanges">exchanges</a></li><li><a href="#cust-indexes">indexes</a></li><li><a href="#cust-derivatives">derivatives</a></li><li><a href="#cust-exchanges">exchanges</a></li><li><a href="#cust-nfts--beta-">nfts (beta)</a></li><li><a href="#cust-exchange_rates">exchange_rates</a></li><li><a href="#cust-search">search</a></li><li><a href="#cust-trending">trending</a></li><li><a href="#cust-global">global</a></li><li><a href="#cust-companies--beta-">companies (beta)</a></li></ol>



# <h2 id='cust-ping'>🌐 ping </h2>
| ENDPOINT <a href='#table-of-contents'>📋</a>| |
|---:|:---|
| <b>1.</b>   | `/ping`  |
|                        | <code>ping()</code> <br><br><blockquotes>Check API server status<blockquotes><br><br> |
| <i>sample</i> | <code><pre>$result = \$apiClient->set()->ping()->send();</pre></code>|


# <h2 id='cust-simple'>🌐 simple </h2>
| ENDPOINT <a href='#table-of-contents'>📋</a>| |
|---:|:---|
| <b>1.<b>   | `/simple/price` |
|                       | <code>simple()->price()</code> <br><br><blockquotes>Get the current price of any cryptocurrencies in any other supported currencies that you need.<blockquotes><br><br> |
|                  | <i>URL Keys</i> : 7<br><br> ✔️  <code>withIds('string')</code>❗️<br> ✔️  <code>withVsCurrencies('string')</code>❗️<br> ✔️  <code>withIncludeMarketCap('string')</code><br> ✔️  <code>withInclude24hrVol('string')</code><br> ✔️  <code>withInclude24hrChange('string')</code><br> ✔️  <code>withIncludeLastUpdatedAt('string')</code><br> ✔️  <code>withPrecision('string')</code><br><br><details><summary>see description</summary><ol><li><code> ids</code> ➞  string <p> ◽️ id of coins, comma-separated if querying more than 1 coin*refers to <b>`coins/list`</b></p></li><li><code> vs_currencies</code> ➞  string <p> ◽️ vs_currency of coins, comma-separated if querying more than 1 vs_currency*refers to <b>`simple/supported_vs_currencies`</b></p></li><li><code> include_market_cap</code> ➞  string <p> ◽️ <b>true/false</b> to include market_cap, <b>default: false</b></p></li><li><code> include_24hr_vol</code> ➞  string <p> ◽️ <b>true/false</b> to include 24hr_vol, <b>default: false</b></p></li><li><code> include_24hr_change</code> ➞  string <p> ◽️ <b>true/false</b> to include 24hr_change, <b>default: false</b></p></li><li><code> include_last_updated_at</code> ➞  string <p> ◽️ <b>true/false</b> to include last_updated_at of price, <b>default: false</b></p></li><li><code> precision</code> ➞  string <p> ◽️ <b>full</b> or any value between 0 - 18 to specify decimal place for currency price value, <b>default: 2</b></p></li></ol></details> |
| <i>sample</i> |<code><pre>$result = \$apiClient->set()->simple()->price()<br>    ->send(<br>        $q->withIds('string')<br>        ->withVsCurrencies('string')<br>        ->withIncludeMarketCap('string')<br>        ->withInclude24hrVol('string')<br>        ->withInclude24hrChange('string')<br>        ->withIncludeLastUpdatedAt('string')<br>        ->withPrecision('string')<br>    );</pre></code>|
| <b>2.<b>   | `/simple/token_price/{id}` |
|                       | <code>simple()->tokenPrice('{id}')</code> <br><br><blockquotes>Get current price of tokens (using contract addresses) for a given platform in any other currency that you need.<blockquotes><br><br><details><summary>see description</summary><ol><li><code> id</code> <p> ➞ The id of the platform issuing tokens (See asset_platforms endpoint for list of options)</p></li></ol></details> |
|                  | <i>URL Keys</i> : 7<br><br> ✔️  <code>withContractAddresses('string')</code>❗️<br> ✔️  <code>withVsCurrencies('string')</code>❗️<br> ✔️  <code>withIncludeMarketCap('string')</code><br> ✔️  <code>withInclude24hrVol('string')</code><br> ✔️  <code>withInclude24hrChange('string')</code><br> ✔️  <code>withIncludeLastUpdatedAt('string')</code><br> ✔️  <code>withPrecision('string')</code><br><br><details><summary>see description</summary><ol><li><code> contract_addresses</code> ➞  string <p> ◽️ The contract address of tokens, comma separated</p></li><li><code> vs_currencies</code> ➞  string <p> ◽️ vs_currency of coins, comma-separated if querying more than 1 vs_currency*refers to <b>`simple/supported_vs_currencies`</b></p></li><li><code> include_market_cap</code> ➞  string <p> ◽️ <b>true/false</b> to include market_cap, <b>default: false</b></p></li><li><code> include_24hr_vol</code> ➞  string <p> ◽️ <b>true/false</b> to include 24hr_vol, <b>default: false</b></p></li><li><code> include_24hr_change</code> ➞  string <p> ◽️ <b>true/false</b> to include 24hr_change, <b>default: false</b></p></li><li><code> include_last_updated_at</code> ➞  string <p> ◽️ <b>true/false</b> to include last_updated_at of price, <b>default: false</b></p></li><li><code> precision</code> ➞  string <p> ◽️ <b>full</b> or any Integer to specify decimal place for currency price value, <b>default: 2</b></p></li></ol></details> |
| <i>sample</i> |<code><pre>$result = \$apiClient->set()->simple()->tokenPrice('{id}')<br>    ->send(<br>        $q->withContractAddresses('string')<br>        ->withVsCurrencies('string')<br>        ->withIncludeMarketCap('string')<br>        ->withInclude24hrVol('string')<br>        ->withInclude24hrChange('string')<br>        ->withIncludeLastUpdatedAt('string')<br>        ->withPrecision('string')<br>    );</pre></code>|
| <b>3.</b>   | `/simple/supported_vs_currencies`  |
|                        | <code>simple()->supportedVsCurrencies()</code> <br><br><blockquotes>Get list of supported_vs_currencies.<blockquotes><br><br> |
| <i>sample</i> | <code><pre>$result = \$apiClient->set()->simple()->supportedVsCurrencies()->send();</pre></code>|


# <h2 id='cust-coins'>🌐 coins </h2>
| ENDPOINT <a href='#table-of-contents'>📋</a>| |
|---:|:---|
| <b>1.<b>   | `/coins/list` |
|                       | <code>coins()->list()</code> <br><br><blockquotes>List all supported coins id, name and symbol (no pagination required)<blockquotes><br><br> |
|                  | <i>URL Keys</i> : 1<br><br> ✔️  <code>withIncludePlatform('boolean')</code><br><br><details><summary>see description</summary><ol><li><code> include_platform</code> ➞  boolean <p> ◽️ flag to include platform contract addresses (eg. 0x.... for Ethereum based tokens).  valid values: true, false</p></li></ol></details> |
| <i>sample</i> |<code><pre>$result = \$apiClient->set()->coins()->list()<br>    ->send(<br>        $q->withIncludePlatform('boolean')<br>    );</pre></code>|
| <b>2.<b>   | `/coins/markets` |
|                       | <code>coins()->markets()</code> <br><br><blockquotes>List all supported coins price, market cap, volume, and market related data<blockquotes><br><br> |
|                  | <i>URL Keys</i> : 8<br><br> ✔️  <code>withVsCurrency('string')</code>❗️<br> ✔️  <code>withIds('string')</code><br> ✔️  <code>withCategory('string')</code><br> ✔️  <code>withOrder('string')</code><br> ✔️  <code>withPerPage('integer')</code><br> ✔️  <code>withPage('integer')</code><br> ✔️  <code>withSparkline('boolean')</code><br> ✔️  <code>withPriceChangePercentage('string')</code><br><br><details><summary>see description</summary><ol><li><code> vs_currency</code> ➞  string <p> ◽️ The target currency of market data (usd, eur, jpy, etc.)</p></li><li><code> ids</code> ➞  string <p> ◽️ The ids of the coin, comma separated crytocurrency symbols (base). refers to `/coins/list`.</p></li><li><code> category</code> ➞  string <p> ◽️ filter by coin category. Refer to /coin/categories/list</p></li><li><code> order</code> ➞  string <p> ◽️ valid values: <b>market_cap_desc, gecko_desc, gecko_asc, market_cap_asc, market_cap_desc, volume_asc, volume_desc, id_asc, id_desc</b>sort results by field.</p></li><li><code> per_page</code> ➞  integer <p> ◽️ valid values: 1..250 Total results per page</p></li><li><code> page</code> ➞  integer <p> ◽️ Page through results</p></li><li><code> sparkline</code> ➞  boolean <p> ◽️ Include sparkline 7 days data (eg. true, false)</p></li><li><code> price_change_percentage</code> ➞  string <p> ◽️ Include price change percentage in <b>1h, 24h, 7d, 14d, 30d, 200d, 1y</b> (eg. '`1h,24h,7d`' comma-separated, invalid values will be discarded)</p></li></ol></details> |
| <i>sample</i> |<code><pre>$result = \$apiClient->set()->coins()->markets()<br>    ->send(<br>        $q->withVsCurrency('string')<br>        ->withIds('string')<br>        ->withCategory('string')<br>        ->withOrder('string')<br>        ->withPerPage('integer')<br>        ->withPage('integer')<br>        ->withSparkline('boolean')<br>        ->withPriceChangePercentage('string')<br>    );</pre></code>|
| <b>3.<b>   | `/coins/{id}` |
|                       | <code>coins('{id}')</code> <br><br><blockquotes>Get current data (name, price, market, ... including exchange tickers) for a coin<blockquotes><br><br><details><summary>see description</summary><ol><li><code> id</code> <p> ➞ pass the coin id (can be obtained from /coins) eg. bitcoin</p></li></ol></details> |
|                  | <i>URL Keys</i> : 6<br><br> ✔️  <code>withLocalization('string')</code><br> ✔️  <code>withTickers('boolean')</code><br> ✔️  <code>withMarketData('boolean')</code><br> ✔️  <code>withCommunityData('boolean')</code><br> ✔️  <code>withDeveloperData('boolean')</code><br> ✔️  <code>withSparkline('boolean')</code><br><br><details><summary>see description</summary><ol><li><code> localization</code> ➞  string <p> ◽️ Include all localized languages in response (true/false) <b>[default: true]</b></p></li><li><code> tickers</code> ➞  boolean <p> ◽️ Include tickers data (true/false) <b>[default: true]</b></p></li><li><code> market_data</code> ➞  boolean <p> ◽️ Include market_data (true/false) <b>[default: true]</b></p></li><li><code> community_data</code> ➞  boolean <p> ◽️ Include community_data data (true/false) <b>[default: true]</b></p></li><li><code> developer_data</code> ➞  boolean <p> ◽️ Include developer_data data (true/false) <b>[default: true]</b></p></li><li><code> sparkline</code> ➞  boolean <p> ◽️ Include sparkline 7 days data (eg. true, false) <b>[default: false]</b></p></li></ol></details> |
| <i>sample</i> |<code><pre>$result = \$apiClient->set()->coins('{id}')<br>    ->send(<br>        $q->withLocalization('string')<br>        ->withTickers('boolean')<br>        ->withMarketData('boolean')<br>        ->withCommunityData('boolean')<br>        ->withDeveloperData('boolean')<br>        ->withSparkline('boolean')<br>    );</pre></code>|
| <b>4.<b>   | `/coins/{id}/tickers` |
|                       | <code>coins('{id}')->tickers()</code> <br><br><blockquotes>Get coin tickers (paginated to 100 items)<blockquotes><br><br><details><summary>see description</summary><ol><li><code> id</code> <p> ➞ pass the coin id (can be obtained from /coins/list) eg. bitcoin</p></li></ol></details> |
|                  | <i>URL Keys</i> : 5<br><br> ✔️  <code>withExchangeIds('string')</code><br> ✔️  <code>withIncludeExchangeLogo('string')</code><br> ✔️  <code>withPage('integer')</code><br> ✔️  <code>withOrder('string')</code><br> ✔️  <code>withDepth('string')</code><br><br><details><summary>see description</summary><ol><li><code> exchange_ids</code> ➞  string <p> ◽️ filter results by exchange_ids (ref: v3/exchanges/list)</p></li><li><code> include_exchange_logo</code> ➞  string <p> ◽️ flag to show exchange_logo</p></li><li><code> page</code> ➞  integer <p> ◽️ Page through results</p></li><li><code> order</code> ➞  string <p> ◽️ valid values: <b>trust_score_desc (default), trust_score_asc and volume_desc</b></p></li><li><code> depth</code> ➞  string <p> ◽️ flag to show 2% orderbook depth. valid values: true, false</p></li></ol></details> |
| <i>sample</i> |<code><pre>$result = \$apiClient->set()->coins('{id}')->tickers()<br>    ->send(<br>        $q->withExchangeIds('string')<br>        ->withIncludeExchangeLogo('string')<br>        ->withPage('integer')<br>        ->withOrder('string')<br>        ->withDepth('string')<br>    );</pre></code>|
| <b>5.<b>   | `/coins/{id}/history` |
|                       | <code>coins('{id}')->history()</code> <br><br><blockquotes>Get historical data (name, price, market, stats) at a given date for a coin<blockquotes><br><br><details><summary>see description</summary><ol><li><code> id</code> <p> ➞ pass the coin id (can be obtained from /coins) eg. bitcoin</p></li></ol></details> |
|                  | <i>URL Keys</i> : 2<br><br> ✔️  <code>withDate('string')</code>❗️<br> ✔️  <code>withLocalization('string')</code><br><br><details><summary>see description</summary><ol><li><code> date</code> ➞  string <p> ◽️ The date of data snapshot in dd-mm-yyyy eg. 30-12-2017</p></li><li><code> localization</code> ➞  string <p> ◽️ Set to false to exclude localized languages in response</p></li></ol></details> |
| <i>sample</i> |<code><pre>$result = \$apiClient->set()->coins('{id}')->history()<br>    ->send(<br>        $q->withDate('string')<br>        ->withLocalization('string')<br>    );</pre></code>|
| <b>6.<b>   | `/coins/{id}/market_chart` |
|                       | <code>coins('{id}')->marketChart()</code> <br><br><blockquotes>Get historical market data include price, market cap, and 24h volume (granularity auto)<blockquotes><br><br><details><summary>see description</summary><ol><li><code> id</code> <p> ➞ pass the coin id (can be obtained from /coins) eg. bitcoin</p></li></ol></details> |
|                  | <i>URL Keys</i> : 3<br><br> ✔️  <code>withVsCurrency('string')</code>❗️<br> ✔️  <code>withDays('string')</code>❗️<br> ✔️  <code>withInterval('string')</code><br><br><details><summary>see description</summary><ol><li><code> vs_currency</code> ➞  string <p> ◽️ The target currency of market data (usd, eur, jpy, etc.)</p></li><li><code> days</code> ➞  string <p> ◽️ Data up to number of days ago (eg. 1,14,30,max)</p></li><li><code> interval</code> ➞  string <p> ◽️ Data interval. Possible value: daily</p></li></ol></details> |
| <i>sample</i> |<code><pre>$result = \$apiClient->set()->coins('{id}')->marketChart()<br>    ->send(<br>        $q->withVsCurrency('string')<br>        ->withDays('string')<br>        ->withInterval('string')<br>    );</pre></code>|
| <b>7.<b>   | `/coins/{id}/market_chart/range` |
|                       | <code>coins('{id}')->marketChart()->range()</code> <br><br><blockquotes>Get historical market data include price, market cap, and 24h volume within a range of timestamp (granularity auto)<blockquotes><br><br><details><summary>see description</summary><ol><li><code> id</code> <p> ➞ pass the coin id (can be obtained from /coins) eg. bitcoin</p></li></ol></details> |
|                  | <i>URL Keys</i> : 3<br><br> ✔️  <code>withVsCurrency('string')</code>❗️<br> ✔️  <code>withFrom('string')</code>❗️<br> ✔️  <code>withTo('string')</code>❗️<br><br><details><summary>see description</summary><ol><li><code> vs_currency</code> ➞  string <p> ◽️ The target currency of market data (usd, eur, jpy, etc.)</p></li><li><code> from</code> ➞  string <p> ◽️ From date in UNIX Timestamp (eg. 1392577232)</p></li><li><code> to</code> ➞  string <p> ◽️ To date in UNIX Timestamp (eg. 1422577232)</p></li></ol></details> |
| <i>sample</i> |<code><pre>$result = \$apiClient->set()->coins('{id}')->marketChart()->range()<br>    ->send(<br>        $q->withVsCurrency('string')<br>        ->withFrom('string')<br>        ->withTo('string')<br>    );</pre></code>|


# <h2 id='cust-contract'>🌐 contract </h2>
| ENDPOINT <a href='#table-of-contents'>📋</a>| |
|---:|:---|
| <b>1.<b>   | `/coins/{id}/contract/{contract_address}` |
|                       | <code>coins('{id}')->contract('{contract_address}')</code> <br><br><blockquotes>Get coin info from contract address<blockquotes><br><br><details><summary>see description</summary><ol><li><code> id</code> <p> ➞ Asset platform (See asset_platforms endpoint for list of options)</p></li><li><code> contract_address</code> <p> ➞ Token's contract address</p></li></ol></details> |
| <i>sample</i> |<code><pre>$result = \$apiClient->set()->coins('{id}')->contract('{contract_address}')->send();</pre></code>|
| <b>2.<b>   | `/coins/{id}/contract/{contract_address}/market_chart/` |
|                       | <code>coins('{id}')->contract('{contract_address}')->marketChart()</code> <br><br><blockquotes>Get historical market data include price, market cap, and 24h volume (granularity auto) from a contract address <blockquotes><br><br><details><summary>see description</summary><ol><li><code> id</code> <p> ➞ The id of the platform issuing tokens (See asset_platforms endpoint for list of options)</p></li><li><code> contract_address</code> <p> ➞ Token's contract address</p></li></ol></details> |
|                  | <i>URL Keys</i> : 2<br><br> ✔️  <code>withVsCurrency('string')</code>❗️<br> ✔️  <code>withDays('string')</code>❗️<br><br><details><summary>see description</summary><ol><li><code> vs_currency</code> ➞  string <p> ◽️ The target currency of market data (usd, eur, jpy, etc.)</p></li><li><code> days</code> ➞  string <p> ◽️ Data up to number of days ago (eg. 1,14,30,max)</p></li></ol></details> |
| <i>sample</i> |<code><pre>$result = \$apiClient->set()->coins('{id}')->contract('{contract_address}')->marketChart()<br>    ->send(<br>        $q->withVsCurrency('string')<br>        ->withDays('string')<br>    );</pre></code>|
| <b>3.<b>   | `/coins/{id}/contract/{contract_address}/market_chart/range` |
|                       | <code>coins('{id}')->contract('{contract_address}')->marketChart()->range()</code> <br><br><blockquotes>Get historical market data include price, market cap, and 24h volume within a range of timestamp (granularity auto) from a contract address<blockquotes><br><br><details><summary>see description</summary><ol><li><code> id</code> <p> ➞ The id of the platform issuing tokens (See asset_platforms endpoint for list of options)</p></li><li><code> contract_address</code> <p> ➞ Token's contract address</p></li></ol></details> |
|                  | <i>URL Keys</i> : 3<br><br> ✔️  <code>withVsCurrency('string')</code>❗️<br> ✔️  <code>withFrom('string')</code>❗️<br> ✔️  <code>withTo('string')</code>❗️<br><br><details><summary>see description</summary><ol><li><code> vs_currency</code> ➞  string <p> ◽️ The target currency of market data (usd, eur, jpy, etc.)</p></li><li><code> from</code> ➞  string <p> ◽️ From date in UNIX Timestamp (eg. 1392577232)</p></li><li><code> to</code> ➞  string <p> ◽️ To date in UNIX Timestamp (eg. 1422577232)</p></li></ol></details> |
| <i>sample</i> |<code><pre>$result = \$apiClient->set()->coins('{id}')->contract('{contract_address}')->marketChart()->range()<br>    ->send(<br>        $q->withVsCurrency('string')<br>        ->withFrom('string')<br>        ->withTo('string')<br>    );</pre></code>|


# <h2 id='cust-coins'>🌐 coins </h2>
| ENDPOINT <a href='#table-of-contents'>📋</a>| |
|---:|:---|
| <b>1.<b>   | `/coins/{id}/ohlc` |
|                       | <code>coins('{id}')->ohlc()</code> <br><br><blockquotes>Get coin's OHLC<blockquotes><br><br><details><summary>see description</summary><ol><li><code> id</code> <p> ➞ pass the coin id (can be obtained from /coins/list) eg. bitcoin</p></li></ol></details> |
|                  | <i>URL Keys</i> : 2<br><br> ✔️  <code>withVsCurrency('string')</code>❗️<br> ✔️  <code>withDays('string')</code>❗️<br><br><details><summary>see description</summary><ol><li><code> vs_currency</code> ➞  string <p> ◽️ The target currency of market data (usd, eur, jpy, etc.)</p></li><li><code> days</code> ➞  string <p> ◽️  Data up to number of days ago (1/7/14/30/90/180/365/max)</p></li></ol></details> |
| <i>sample</i> |<code><pre>$result = \$apiClient->set()->coins('{id}')->ohlc()<br>    ->send(<br>        $q->withVsCurrency('string')<br>        ->withDays('string')<br>    );</pre></code>|


# <h2 id='cust-asset_platforms'>🌐 asset_platforms </h2>
| ENDPOINT <a href='#table-of-contents'>📋</a>| |
|---:|:---|
| <b>1.<b>   | `/asset_platforms` |
|                       | <code>assetPlatforms()</code> <br><br><blockquotes>List all asset platforms (Blockchain networks)<blockquotes><br><br> |
|                  | <i>URL Keys</i> : 1<br><br> ✔️  <code>withFilter('string')</code><br><br><details><summary>see description</summary><ol><li><code> filter</code> ➞  string <p> ◽️ apply relevant filters to results valid values: "nft" (asset_platform nft-support)</p></li></ol></details> |
| <i>sample</i> |<code><pre>$result = \$apiClient->set()->assetPlatforms()<br>    ->send(<br>        $q->withFilter('string')<br>    );</pre></code>|


# <h2 id='cust-categories'>🌐 categories </h2>
| ENDPOINT <a href='#table-of-contents'>📋</a>| |
|---:|:---|
| <b>1.</b>   | `/coins/categories/list`  |
|                        | <code>coins()->categories()->list()</code> <br><br><blockquotes>List all categories<blockquotes><br><br> |
| <i>sample</i> | <code><pre>$result = \$apiClient->set()->coins()->categories()->list()->send();</pre></code>|
| <b>2.<b>   | `/coins/categories` |
|                       | <code>coins()->categories()</code> <br><br><blockquotes>List all categories with market data<blockquotes><br><br> |
|                  | <i>URL Keys</i> : 1<br><br> ✔️  <code>withOrder('string')</code><br><br><details><summary>see description</summary><ol><li><code> order</code> ➞  string <p> ◽️ valid values: <b>market_cap_desc (default), market_cap_asc, name_desc, name_asc, market_cap_change_24h_desc and market_cap_change_24h_asc</b></p></li></ol></details> |
| <i>sample</i> |<code><pre>$result = \$apiClient->set()->coins()->categories()<br>    ->send(<br>        $q->withOrder('string')<br>    );</pre></code>|


# <h2 id='cust-exchanges'>🌐 exchanges </h2>
| ENDPOINT <a href='#table-of-contents'>📋</a>| |
|---:|:---|
| <b>1.<b>   | `/exchanges` |
|                       | <code>exchanges()</code> <br><br><blockquotes>List all exchanges (Active with trading volumes)<blockquotes><br><br> |
|                  | <i>URL Keys</i> : 2<br><br> ✔️  <code>withPerPage('integer')</code><br> ✔️  <code>withPage('string')</code><br><br><details><summary>see description</summary><ol><li><code> per_page</code> ➞  integer <p> ◽️ Valid values: 1...250Total results per pageDefault value:: 100</p></li><li><code> page</code> ➞  string <p> ◽️ page through results</p></li></ol></details> |
| <i>sample</i> |<code><pre>$result = \$apiClient->set()->exchanges()<br>    ->send(<br>        $q->withPerPage('integer')<br>        ->withPage('string')<br>    );</pre></code>|
| <b>2.</b>   | `/exchanges/list`  |
|                        | <code>exchanges()->list()</code> <br><br><blockquotes>List all supported markets id and name (no pagination required)<blockquotes><br><br> |
| <i>sample</i> | <code><pre>$result = \$apiClient->set()->exchanges()->list()->send();</pre></code>|
| <b>3.<b>   | `/exchanges/{id}` |
|                       | <code>exchanges('{id}')</code> <br><br><blockquotes>Get exchange volume in BTC and top 100 tickers only<blockquotes><br><br><details><summary>see description</summary><ol><li><code> id</code> <p> ➞ pass the exchange id (can be obtained from /exchanges/list) eg. binance</p></li></ol></details> |
| <i>sample</i> |<code><pre>$result = \$apiClient->set()->exchanges('{id}')->send();</pre></code>|
| <b>4.<b>   | `/exchanges/{id}/tickers` |
|                       | <code>exchanges('{id}')->tickers()</code> <br><br><blockquotes>Get exchange tickers (paginated, 100 tickers per page)<blockquotes><br><br><details><summary>see description</summary><ol><li><code> id</code> <p> ➞ pass the exchange id (can be obtained from /exchanges/list) eg. binance</p></li></ol></details> |
|                  | <i>URL Keys</i> : 5<br><br> ✔️  <code>withCoinIds('string')</code><br> ✔️  <code>withIncludeExchangeLogo('string')</code><br> ✔️  <code>withPage('integer')</code><br> ✔️  <code>withDepth('string')</code><br> ✔️  <code>withOrder('string')</code><br><br><details><summary>see description</summary><ol><li><code> coin_ids</code> ➞  string <p> ◽️ filter tickers by coin_ids (ref: v3/coins/list)</p></li><li><code> include_exchange_logo</code> ➞  string <p> ◽️ flag to show exchange_logo</p></li><li><code> page</code> ➞  integer <p> ◽️ Page through results</p></li><li><code> depth</code> ➞  string <p> ◽️ flag to show 2% orderbook depth i.e., cost_to_move_up_usd and cost_to_move_down_usd</p></li><li><code> order</code> ➞  string <p> ◽️ valid values: <b>trust_score_desc (default), trust_score_asc and volume_desc</b></p></li></ol></details> |
| <i>sample</i> |<code><pre>$result = \$apiClient->set()->exchanges('{id}')->tickers()<br>    ->send(<br>        $q->withCoinIds('string')<br>        ->withIncludeExchangeLogo('string')<br>        ->withPage('integer')<br>        ->withDepth('string')<br>        ->withOrder('string')<br>    );</pre></code>|


# <h2 id='cust-indexes'>🌐 indexes </h2>
| ENDPOINT <a href='#table-of-contents'>📋</a>| |
|---:|:---|
| <b>1.<b>   | `/indexes` |
|                       | <code>indexes()</code> <br><br><blockquotes>List all market indexes<blockquotes><br><br> |
|                  | <i>URL Keys</i> : 2<br><br> ✔️  <code>withPerPage('integer')</code><br> ✔️  <code>withPage('integer')</code><br><br><details><summary>see description</summary><ol><li><code> per_page</code> ➞  integer <p> ◽️ Total results per page</p></li><li><code> page</code> ➞  integer <p> ◽️ Page through results</p></li></ol></details> |
| <i>sample</i> |<code><pre>$result = \$apiClient->set()->indexes()<br>    ->send(<br>        $q->withPerPage('integer')<br>        ->withPage('integer')<br>    );</pre></code>|
| <b>2.<b>   | `/indexes/{market_id}/{id}` |
|                       | <code>indexes('{market_id}''{id}')</code> <br><br><blockquotes>get market index by market id and index id<blockquotes><br><br><details><summary>see description</summary><ol><li><code> market_id</code> <p> ➞ pass the market id (can be obtained from /exchanges/list)</p></li><li><code> id</code> <p> ➞ pass the index id (can be obtained from /indexes/list)</p></li></ol></details> |
| <i>sample</i> |<code><pre>$result = \$apiClient->set()->indexes('{market_id}''{id}')->send();</pre></code>|
| <b>3.</b>   | `/indexes/list`  |
|                        | <code>indexes()->list()</code> <br><br><blockquotes>list market indexes id and name<blockquotes><br><br> |
| <i>sample</i> | <code><pre>$result = \$apiClient->set()->indexes()->list()->send();</pre></code>|


# <h2 id='cust-derivatives'>🌐 derivatives </h2>
| ENDPOINT <a href='#table-of-contents'>📋</a>| |
|---:|:---|
| <b>1.<b>   | `/derivatives` |
|                       | <code>derivatives()</code> <br><br><blockquotes>List all derivative tickers<blockquotes><br><br> |
|                  | <i>URL Keys</i> : 1<br><br> ✔️  <code>withIncludeTickers('string')</code><br><br><details><summary>see description</summary><ol><li><code> include_tickers</code> ➞  string <p> ◽️ ['all', 'unexpired'] - expired to show unexpired tickers, all to list all tickers, defaults to unexpired</p></li></ol></details> |
| <i>sample</i> |<code><pre>$result = \$apiClient->set()->derivatives()<br>    ->send(<br>        $q->withIncludeTickers('string')<br>    );</pre></code>|
| <b>2.<b>   | `/derivatives/exchanges` |
|                       | <code>derivatives()->exchanges()</code> <br><br><blockquotes>List all derivative exchanges<blockquotes><br><br> |
|                  | <i>URL Keys</i> : 3<br><br> ✔️  <code>withOrder('string')</code><br> ✔️  <code>withPerPage('integer')</code><br> ✔️  <code>withPage('integer')</code><br><br><details><summary>see description</summary><ol><li><code> order</code> ➞  string <p> ◽️ order results using following params name_asc，name_desc，open_interest_btc_asc，open_interest_btc_desc，trade_volume_24h_btc_asc，trade_volume_24h_btc_desc</p></li><li><code> per_page</code> ➞  integer <p> ◽️ Total results per page</p></li><li><code> page</code> ➞  integer <p> ◽️ Page through results</p></li></ol></details> |
| <i>sample</i> |<code><pre>$result = \$apiClient->set()->derivatives()->exchanges()<br>    ->send(<br>        $q->withOrder('string')<br>        ->withPerPage('integer')<br>        ->withPage('integer')<br>    );</pre></code>|
| <b>3.<b>   | `/derivatives/exchanges/{id}` |
|                       | <code>derivatives()->exchanges('{id}')</code> <br><br><blockquotes>show derivative exchange data<blockquotes><br><br><details><summary>see description</summary><ol><li><code> id</code> <p> ➞ pass the exchange id (can be obtained from derivatives/exchanges/list) eg. bitmex</p></li></ol></details> |
|                  | <i>URL Keys</i> : 1<br><br> ✔️  <code>withIncludeTickers('string')</code><br><br><details><summary>see description</summary><ol><li><code> include_tickers</code> ➞  string <p> ◽️ ['all', 'unexpired'] - expired to show unexpired tickers, all to list all tickers, leave blank to omit tickers data in response</p></li></ol></details> |
| <i>sample</i> |<code><pre>$result = \$apiClient->set()->derivatives()->exchanges('{id}')<br>    ->send(<br>        $q->withIncludeTickers('string')<br>    );</pre></code>|
| <b>4.</b>   | `/derivatives/exchanges/list`  |
|                        | <code>derivatives()->exchanges()->list()</code> <br><br><blockquotes>List all derivative exchanges name and identifier<blockquotes><br><br> |
| <i>sample</i> | <code><pre>$result = \$apiClient->set()->derivatives()->exchanges()->list()->send();</pre></code>|


# <h2 id='cust-exchanges'>🌐 exchanges </h2>
| ENDPOINT <a href='#table-of-contents'>📋</a>| |
|---:|:---|
| <b>1.<b>   | `/exchanges/{id}/volume_chart` |
|                       | <code>exchanges('{id}')->volumeChart()</code> <br><br><blockquotes>Get volume_chart data for a given exchange<blockquotes><br><br><details><summary>see description</summary><ol><li><code> id</code> <p> ➞ pass the exchange id (can be obtained from /exchanges/list) eg. binance</p></li></ol></details> |
|                  | <i>URL Keys</i> : 1<br><br> ✔️  <code>withDays('integer')</code>❗️<br><br><details><summary>see description</summary><ol><li><code> days</code> ➞  integer <p> ◽️  Data up to number of days ago (eg. 1,14,30)</p></li></ol></details> |
| <i>sample</i> |<code><pre>$result = \$apiClient->set()->exchanges('{id}')->volumeChart()<br>    ->send(<br>        $q->withDays('integer')<br>    );</pre></code>|


# <h2 id='cust-nfts--beta-'>🌐 nfts (beta) </h2>
| ENDPOINT <a href='#table-of-contents'>📋</a>| |
|---:|:---|
| <b>1.<b>   | `/nfts/list` |
|                       | <code>nfts()->list()</code> <br><br><blockquotes>List all supported NFT ids, paginated by 100 items per page, paginated to 100 items<blockquotes><br><br> |
|                  | <i>URL Keys</i> : 4<br><br> ✔️  <code>withOrder('string')</code><br> ✔️  <code>withAssetPlatformId('string')</code><br> ✔️  <code>withPerPage('integer')</code><br> ✔️  <code>withPage('integer')</code><br><br><details><summary>see description</summary><ol><li><code> order</code> ➞  string <p> ◽️ valid values: <b>h24_volume_native_asc, h24_volume_native_desc, floor_price_native_asc, floor_price_native_desc, market_cap_native_asc, market_cap_native_desc, market_cap_usd_asc, market_cap_usd_desc</b></p></li><li><code> asset_platform_id</code> ➞  string <p> ◽️ The id of the platform issuing tokens (See asset_platforms endpoint for list of options)</p></li><li><code> per_page</code> ➞  integer <p> ◽️ Total results per page</p></li><li><code> page</code> ➞  integer <p> ◽️ Page through results</p></li></ol></details> |
| <i>sample</i> |<code><pre>$result = \$apiClient->set()->nfts()->list()<br>    ->send(<br>        $q->withOrder('string')<br>        ->withAssetPlatformId('string')<br>        ->withPerPage('integer')<br>        ->withPage('integer')<br>    );</pre></code>|
| <b>2.<b>   | `/nfts/{id}` |
|                       | <code>nfts('{id}')</code> <br><br><blockquotes>Get current data (name, price_floor, volume_24h ...) for an NFT collection<blockquotes><br><br><details><summary>see description</summary><ol><li><code> id</code> <p> ➞ id of nft collection (can be obtained from /nfts/list)</p></li></ol></details> |
| <i>sample</i> |<code><pre>$result = \$apiClient->set()->nfts('{id}')->send();</pre></code>|
| <b>3.<b>   | `/nfts/{asset_platform_id}/contract/{contract_address}` |
|                       | <code>nfts('{asset_platform_id}')->contract('{contract_address}')</code> <br><br><blockquotes>Get current data (name, price_floor, volume_24h ...) for an NFT collection<blockquotes><br><br><details><summary>see description</summary><ol><li><code> asset_platform_id</code> <p> ➞ The id of the platform issuing tokens (See asset_platforms endpoint for list of options, use filter=nft param)</p></li><li><code> contract_address</code> <p> ➞ The contract_address of the nft collection (/nfts/list for list of nft collection with metadata)</p></li></ol></details> |
| <i>sample</i> |<code><pre>$result = \$apiClient->set()->nfts('{asset_platform_id}')->contract('{contract_address}')->send();</pre></code>|


# <h2 id='cust-exchange_rates'>🌐 exchange_rates </h2>
| ENDPOINT <a href='#table-of-contents'>📋</a>| |
|---:|:---|
| <b>1.</b>   | `/exchange_rates`  |
|                        | <code>exchangeRates()</code> <br><br><blockquotes>Get BTC-to-Currency exchange rates<blockquotes><br><br> |
| <i>sample</i> | <code><pre>$result = \$apiClient->set()->exchangeRates()->send();</pre></code>|


# <h2 id='cust-search'>🌐 search </h2>
| ENDPOINT <a href='#table-of-contents'>📋</a>| |
|---:|:---|
| <b>1.<b>   | `/search` |
|                       | <code>search()</code> <br><br><blockquotes>Search for coins, categories and markets on CoinGecko<blockquotes><br><br> |
|                  | <i>URL Keys</i> : 1<br><br> ✔️  <code>withQuery('string')</code>❗️<br><br><details><summary>see description</summary><ol><li><code> query</code> ➞  string <p> ◽️ Search string</p></li></ol></details> |
| <i>sample</i> |<code><pre>$result = \$apiClient->set()->search()<br>    ->send(<br>        $q->withQuery('string')<br>    );</pre></code>|


# <h2 id='cust-trending'>🌐 trending </h2>
| ENDPOINT <a href='#table-of-contents'>📋</a>| |
|---:|:---|
| <b>1.</b>   | `/search/trending`  |
|                        | <code>search()->trending()</code> <br><br><blockquotes>Get trending search coins (Top-7) on CoinGecko in the last 24 hours<blockquotes><br><br> |
| <i>sample</i> | <code><pre>$result = \$apiClient->set()->search()->trending()->send();</pre></code>|


# <h2 id='cust-global'>🌐 global </h2>
| ENDPOINT <a href='#table-of-contents'>📋</a>| |
|---:|:---|
| <b>1.</b>   | `/global`  |
|                        | <code>global()</code> <br><br><blockquotes>Get cryptocurrency global data<blockquotes><br><br> |
| <i>sample</i> | <code><pre>$result = \$apiClient->set()->global()->send();</pre></code>|
| <b>2.</b>   | `/global/decentralized_finance_defi`  |
|                        | <code>global()->decentralizedFinanceDefi()</code> <br><br><blockquotes>Get cryptocurrency global decentralized finance(defi) data<blockquotes><br><br> |
| <i>sample</i> | <code><pre>$result = \$apiClient->set()->global()->decentralizedFinanceDefi()->send();</pre></code>|


# <h2 id='cust-companies--beta-'>🌐 companies (beta) </h2>
| ENDPOINT <a href='#table-of-contents'>📋</a>| |
|---:|:---|
| <b>1.<b>   | `/companies/public_treasury/{coin_id}` |
|                       | <code>companies()->publicTreasury('{coin_id}')</code> <br><br><blockquotes>Get public companies data<blockquotes><br><br><details><summary>see description</summary><ol><li><code> coin_id</code> <p> ➞ bitcoin or ethereum</p></li></ol></details> |
| <i>sample</i> |<code><pre>$result = \$apiClient->set()->companies()->publicTreasury('{coin_id}')->send();</pre></code>|
