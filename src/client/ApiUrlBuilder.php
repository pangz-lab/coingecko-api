<?php
declare(strict_types=1);

namespace PangzLab\CoinGecko\Client;
use ParseError;

class ApiUrlBuilder
{
  private $parameterList = [];
  private $endpoint = "";
  private $apiVersion = "v3";
  const COINGECKO_BASE_URL = "https://api.coingecko.com/api";
  const COINGECKO_BASE_URL_PRO = "https://pro-api.coingecko.com/api";
  const SETTER_PREFIX = 'with';
  const API_QUERY_PARAMS = [
    "id" => "id",
    "ids" => "ids",
    "vscurrencies" => "vs_currencies",
    "includemarketcap" => "include_market_cap",
    "include24hrvol" => "include_24hr_vol",
    "include24hrchange" => "include_24hr_change",
    "includelastupdatedat" => "include_last_updated_at",
    "precision" => "precision",
    "contractaddresses" => "contract_addresses",
    "includeplatform" => "include_platform",
    "vscurrency" => "vs_currency",
    "category" => "category",
    "order" => "order",
    "perpage" => "per_page",
    "page" => "page",
    "sparkline" => "sparkline",
    "pricechangepercentage" => "price_change_percentage",
    "localization" => "localization",
    "tickers" => "tickers",
    "marketdata" => "market_data",
    "communitydata" => "community_data",
    "developerdata" => "developer_data",
    "exchangeids" => "exchange_ids",
    "includeexchangelogo" => "include_exchange_logo",
    "depth" => "depth",
    "date" => "date",
    "days" => "days",
    "interval" => "interval",
    "from" => "from",
    "to" => "to",
    "contractaddress" => "contract_address",
    "filter" => "filter",
    "coinids" => "coin_ids",
    "marketid" => "market_id",
    "includetickers" => "include_tickers",
    "assetplatformid" => "asset_platform_id",
    "query" => "query",
    "coinid" => "coin_id",
  ];
  /**
   * @param array $parameterList
   * @param string $endpoint
   * @param string $apiVersion
   */
  public function __construct(
    array $parameterList = [],
    string $endpoint = "",
    string $apiVersion = "v3"
  ) {
    $this->parameterList = $parameterList;
    $this->endpoint = $endpoint;
    $this->apiVersion = $apiVersion;
  }

  public function __call($name, $arguments)
  {
    $sanitizedName = strtolower(str_replace("_", "", $name));
    $paramName = substr($sanitizedName, strlen(self::SETTER_PREFIX));
    if(!str_starts_with($sanitizedName, self::SETTER_PREFIX) || !$this->paramExist($paramName)) {
      throw new ParseError("Unknown method used. Setter name : $name", -1);
    }

    $paramElement = $this->getParameter($paramName);
    $this->parameterList[] = [$paramElement, $arguments[0]];
    return new ApiUrlBuilder($this->parameterList, $this->endpoint, $this->apiVersion);
  }

  public function setEndpoint(string $endpoint = ""): ApiUrlBuilder
  {
    $endpoint = (empty(trim($endpoint))) ? $this->endpoint : $endpoint;
    return new ApiUrlBuilder(
      $this->parameterList,
      $endpoint,
      $this->apiVersion
    );
  }

  public function build(): string
  {
    $query = (!empty($this->parameterList)) ?
      '?' . $this->buildKeyValuePair() :
      '';
    return self::COINGECKO_BASE_URL .  
      '/' . $this->apiVersion .
      $this->endpoint .
      $query;
  }

  public function clean(): ApiUrlBuilder
  {
    return new ApiUrlBuilder([], "", $this->apiVersion);
  }

  private function buildKeyValuePair(): string 
  {
    $urlQuery = [];
    foreach($this->parameterList as $keyValue) {
      $urlQuery[$keyValue[0]] = $keyValue[0] . '=' . $keyValue[1];
    }
    return implode('&', $urlQuery);
  }

  private function paramExist($name): bool
  {
    return isset(self::API_QUERY_PARAMS[$name]);
  }

  private function getParameter($key)
  {
    return self::API_QUERY_PARAMS[$key];
  }
}