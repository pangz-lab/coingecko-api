<?php
declare(strict_types=1);

namespace PangzLab\CoinGecko\Client;
use ParseError;

class CoinGeckoUrlBuilder
{
  private $parameterList = [];
  private $endpoint = "";
  const SETTER_PREFIX = 'with';
  const API_QUERY_PARAMS = [
    "xcgproapikey" => "x_cg_pro_api_key",
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
   */
  public function __construct(
    array $parameterList = [],
    string $endpoint = ""
  ) {
    $this->parameterList = $parameterList;
    $this->endpoint = $endpoint;
  }

  public function __call($name, $arguments)
  {
    $sanitizedName = strtolower(str_replace("_", "", $name));
    $paramName = substr($sanitizedName, strlen(self::SETTER_PREFIX));
    if(
      !str_starts_with($sanitizedName, self::SETTER_PREFIX)
      || !$this->paramExist($paramName)
    ) {
      throw new ParseError("Unknown method used. Setter name : $name", -1);
    }

    $paramElement = $this->getParameter($paramName);
    $this->parameterList[] = [$paramElement, $arguments[0]];
    return new CoinGeckoUrlBuilder($this->parameterList, $this->endpoint);
  }

  public function setEndpoint(string $endpoint = ""): CoinGeckoUrlBuilder
  {
    $endpoint = (empty(trim($endpoint))) ? $this->endpoint : $endpoint;
    return new CoinGeckoUrlBuilder(
      $this->parameterList,
      $endpoint
    );
  }

  public function build(): string
  {
    $query = (!empty($this->parameterList)) ?
      '?' . $this->buildKeyValuePairQueryString() :
      '';
    return $this->endpoint . $query;
  }

  public function clean(): CoinGeckoUrlBuilder
  {
    return new CoinGeckoUrlBuilder([], "");
  }

  private function isApiKeyParamExist(): bool
  {
    return isset($this->buildKeyValuePair()['x_cg_pro_api_key']);
  }

  private function buildKeyValuePair(): array
  {
    $urlQuery = [];
    foreach($this->parameterList as $keyValue) {
      $urlQuery[$keyValue[0]] = $keyValue[0] . '=' . $keyValue[1];
    }
    return $urlQuery;
  }

  private function buildKeyValuePairQueryString(): string 
  {
    return implode('&', $this->buildKeyValuePair());
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