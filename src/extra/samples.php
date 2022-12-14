<?php
require __DIR__ . './../../vendor/autoload.php';

use PangzLab\CoinGecko\Client\ApiUrlBuilder;
use PangzLab\CoinGecko\Client\CoinGeckoApiClient;
use GuzzleHttp\Exception\RequestException;

$apiQuery = new ApiUrlBuilder();
$apiClient= new CoinGeckoApiClient();
try {
  $response = $apiClient->get()->ping()->send();
  print_r($response);
  // $response = $apiClient->get()->coins()->list()->send();
  // print_r($response);
  $response = $apiClient->get()
    ->simple()
    ->price()
    ->send(
      $apiQuery
        ->withIds("verus-coin")
        ->withVsCurrencies("btc")
        ->withIncludeMarketCap(true)
    );
  print_r($response);
} catch (\ParseError $e) {
  print("Invalid Command");
} catch (\RequestException $e) {
  print("Invalid Request");
} catch (\Exception $e) {
  print("Unknown Exception");
  print($e->getMessage());
}
