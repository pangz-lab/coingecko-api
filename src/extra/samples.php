<?php
require __DIR__ . './../../vendor/autoload.php';

use GuzzleHttp\Exception\RequestException;
use PangzLab\CoinGecko\Client\CoinGeckoUrlBuilder;
use PangzLab\CoinGecko\Client\CoinGeckoApiClient;

$apiUrlBuilder = new CoinGeckoUrlBuilder();
$apiClient = new CoinGeckoApiClient();
try {
    $response = $apiClient->set()
        ->ping()
        ->send();
    print_r($response);

    $response = $apiClient->set()
        ->simple()
        ->price()
        ->send(
            $apiUrlBuilder
                ->withIds("verus-coin,ethereum")
                ->withVsCurrencies("btc")
                ->withIncludeMarketCap("true")
        );
    print_r($response);

    $response = $apiClient->set()
        ->nfts()
        ->list()
        ->send();
    print_r($response);

    $response = $apiClient->set()
        ->nfts()
        ->list()
        ->send(
            $apiUrlBuilder
                ->withPage(2)
                ->withOrder("market_cap_usd_desc")
        );
    print_r($response);
} catch (\ParseError $e) {
    print("Invalid Command");
} catch (RequestException $e) {
    print("Invalid Request");
} catch (\Exception $e) {
    print("Unknown Exception");
    print($e->getMessage());
}
