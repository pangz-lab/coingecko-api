<?php
declare(strict_types=1);

namespace PangzLab\CoinGecko\Client;

use PangzLab\CoinGecko\Client\ApiClient;
use PangzLab\CoinGecko\Client\CoinGeckoUrlBuilder;

class CoinGeckoApiClient
{
    private $apiClient;
    private $endpoint = "";
    private $endpointKey = "";
    private $endpointParams = [];
    const COINGECKO_BASE_URL = "https://api.coingecko.com/api/v3";
    const COINGECKO_BASE_URL_PRO = "https://pro-api.coingecko.com/api/v3";
    const API_ENPOINTS_MAP = [
        "/ping" => "/ping",
        "/simple/price" => "/simple/price",
        "/simple/tokenprice/%s" => "/simple/token_price/%s",
        "/simple/supportedvscurrencies" => "/simple/supported_vs_currencies",
        "/coins/list" => "/coins/list",
        "/coins/markets" => "/coins/markets",
        "/coins/%s" => "/coins/%s",
        "/coins/%s/tickers" => "/coins/%s/tickers",
        "/coins/%s/history" => "/coins/%s/history",
        "/coins/%s/marketchart" => "/coins/%s/market_chart",
        "/coins/%s/marketchart/range" => "/coins/%s/market_chart/range",
        "/coins/%s/contract/%s" => "/coins/%s/contract/%s",
        "/coins/%s/contract/%s/marketchart" => "/coins/%s/contract/%s/market_chart/",
        "/coins/%s/contract/%s/marketchart/range" => "/coins/%s/contract/%s/market_chart/range",
        "/coins/%s/ohlc" => "/coins/%s/ohlc",
        "/coins/categories" => "/coins/categories",
        "/coins/categories/list" => "/coins/categories/list",
        "/assetplatforms" => "/asset_platforms",
        "/exchanges" => "/exchanges",
        "/exchanges/%s" => "/exchanges/%s",
        "/exchanges/%s/tickers" => "/exchanges/%s/tickers",
        "/exchanges/%s/volumechart" => "/exchanges/%s/volume_chart",
        "/exchanges/list" => "/exchanges/list",
        "/indexes" => "/indexes",
        "/indexes/%s/%s" => "/indexes/%s/%s",
        "/indexes/list" => "/indexes/list",
        "/derivatives" => "/derivatives",
        "/derivatives/exchanges" => "/derivatives/exchanges",
        "/derivatives/exchanges/%s" => "/derivatives/exchanges/%s",
        "/derivatives/exchanges/list" => "/derivatives/exchanges/list",
        "/nfts/%s" => "/nfts/%s",
        "/nfts/%s/contract/%s" => "/nfts/%s/contract/%s",
        "/nfts/list" => "/nfts/list",
        "/exchangerates" => "/exchange_rates",
        "/search" => "/search",
        "/search/trending" => "/search/trending",
        "/global" => "/global",
        "/global/decentralizedfinancedefi" => "/global/decentralized_finance_defi",
        "/companies/publictreasury/%s" => "/companies/public_treasury/%s",
        //PRO Version
        "/nfts/markets" => "/nfts/markets",
        "/nfts/%s/marketchart" => "/nfts/%s/market_chart",
        "/nfts/%s/contract/%s/marketchart" => "/nfts/%s/contract/%s/market_chart",
        "/nfts/%s/tickers" => "/nfts/%s/tickers",
        "/global/marketcapchart" => "/global/market_cap_chart",
    ];

    public function __construct(
        ApiClient $apiClient = null,
        string $endpoint = "",
        array $endpointParams = []
    ) {
        $this->apiClient = is_null($apiClient) ?
            new ApiClient() : $apiClient;
        $this->endpoint = $endpoint;
        $this->endpointKey = strtolower($this->endpoint);
        $this->endpointParams = $endpointParams;
    }

    /**
     * Generic method caller to create an endpoint
     * 
     * @return CoinGeckoApiClient
     */
    public function __call($name, $arguments): CoinGeckoApiClient
    {
        $hasArguments = !empty($arguments);
        $name = strtolower(str_replace("_", "", $name));
        $this->endpoint .= '/' . $name;
        if ($hasArguments) {
            $this->endpoint .= str_repeat('/%s', count($arguments));
            $this->endpointParams = array_merge(
                $this->endpointParams,
                $arguments
            );
        }
        
        return new CoinGeckoApiClient(
            $this->apiClient,
            $this->endpoint,
            $this->endpointParams
        );
    }

    /**
     * Provides the endpoint key created
     * 
     * @return string
     */
    public function getEndpointKey(): string
    {
        return $this->endpointKey;
    }

    /**
     * Initialize a CoinGeckoApiClient object
     * 
     * @return CoinGeckoApiClient
     */
    public function set(): CoinGeckoApiClient
    {
        return new CoinGeckoApiClient($this->apiClient, "", []);
    }

    /**
     * Reset the object to ready for another request
     * 
     * @return void 
     */
    public function reset(): void
    {
        $this->endpointParams = [];
        $this->endpoint = "";
        $this->endpointKey = "";
    }

    /**
     * Use to send a request to the CoinGecko Community endpoint
     * 
     * @throws ParseError|Exception|GuzzleHttp\Exception\RequestException
     * @param CoinGeckoUrlBuilder $urlBuilder
     * @return array 
     */
    public function send(CoinGeckoUrlBuilder $urlBuilder = null): array
    {
        return $this->sendSwitch($urlBuilder);
    }

    /**
     * Use to send a request to the Pro Endpoint endpoint
     * 
     * @throws ParseError|Exception|GuzzleHttp\Exception\RequestException
     * @param CoinGeckoUrlBuilder $urlBuilder
     * @return array 
     */
    public function sendPro(CoinGeckoUrlBuilder $urlBuilder = null): array
    {
        return $this->sendSwitch($urlBuilder, true);
    }

    private function sendSwitch(
        CoinGeckoUrlBuilder $urlBuilder = null,
        bool $isProVersion = false
    ): array {

        if (empty($this->endpoint)) {
            throw new \ParseError(
                "[ERROR:-1] Undefined endpoint." .
                    "Please form your endpoint first then send.",
                -1
            );
        }

        if (!$this->endpointExist()) {
            throw new \ParseError(
                "[ERROR:-2] Either the endpoint does not exist or " .
                    "\n you are setting a value to a URI " .
                    "that does not require a parameter." .
                    "\n Please check your URL. " . $this->endpoint,
                -2
            );
        }

        $baseUrl = self::COINGECKO_BASE_URL;
        if ($isProVersion) {
            if (is_null($urlBuilder) || !$urlBuilder->apiKeyParamExist()) {
                throw new \ParseError(
                    "[ERROR:-3] It appears you want to use the pro version of the CoinGecko API. " .
                        "\nIf you believe this is correct, please set the API Key to proceed, " .
                        "\notherwise use the community API by using the send() method instead of sendPro()." .
                        "\nENDPOINT : [ " . $this->endpoint . "]",
                    -3
                );
            }
            $baseUrl = self::COINGECKO_BASE_URL_PRO;
        }

        $endpoint = $this->getApiEndpoint();
        $url = $baseUrl . $endpoint;
        $this->apiClient = $this->apiClient->setUrl($url);        

        if (!is_null($urlBuilder)) {
            $this->apiClient = $this->apiClient->setUrl(
                $url . $urlBuilder->build()
            );
        }

        return $this->apiClient->send();
    }

    private function endpointExist(): bool
    {
        return isset(self::API_ENPOINTS_MAP[$this->endpointKey]);
    }

    private function getApiEndpoint(): string
    {
        return vsprintf(
            self::API_ENPOINTS_MAP[$this->endpointKey],
            $this->endpointParams
        );
    }
}
