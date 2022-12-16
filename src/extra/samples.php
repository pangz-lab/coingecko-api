<?php
require __DIR__ . './../../vendor/autoload.php';

use GuzzleHttp\Exception\RequestException;
use PangzLab\CoinGecko\Client\CoinGeckoUrlBuilder;
use PangzLab\CoinGecko\Client\CoinGeckoApiClient;

$q = new CoinGeckoUrlBuilder();
$apiClient = new CoinGeckoApiClient();

try {
    //================================//
    $response = $apiClient->set()
        ->ping()
        ->send();
    print_r($response);

    //================================//
    $response = $apiClient->set()
        ->simple()
        ->price()
        ->send(
            $q->withIds("verus-coin,ethereum")
                ->withVsCurrencies("btc")
                ->withIncludeMarketCap("true")
        );
    print_r($response);

    //================================//
    $response = $apiClient->set()
        ->nfts()
        ->list()
        ->send();
    print_r($response);

    //================================//
    $response = $apiClient->set()
        ->nfts()
        ->list()
        ->send($q->withPage(2)
            ->withOrder("market_cap_usd_desc")
        );
    print_r($response);

    //================================//
    $response = $apiClient->set()
        ->exchanges("safe_trade")
        ->volumeChart()
        ->send($q->withDays(1));
    print_r($response);
    
    
    //================================//
    $response = $apiClient->set()
        ->coins()
        ->categories()
        ->send($q->withOrder("name_desc"));
    print_r($response);


    //================================//
    $response = $apiClient->set()
        ->coins("verus-coin")
        ->send($q->withLocalization("false")
            ->withDeveloperData("true")
            ->withSparkline("true")
            ->withCommunityData("true")
            ->withMarketData("true")
            ->withTickers("true")
        );
    print_r($response);

    //================================//
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
    
    //================================//
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

    //================================//
    $request = $apiClient->set()->ping();
    for($x = 0; $x <= 10; $x++) {
        $response = $request->send();
        sleep(3);
        print_r($response);
    }

} catch (\ParseError $e) {
    print("Invalid Command");
    print($e->getMessage());
} catch (RequestException $e) {
    print("Invalid Request");
    print($e->getMessage());
} catch (\Exception $e) {
    print("Unknown Exception");
    print($e->getMessage());
}
