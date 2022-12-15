<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use PangzLab\CoinGecko\Client\ApiClient;
use PangzLab\CoinGecko\Client\CoinGeckoUrlBuilder;
use PangzLab\CoinGecko\Client\CoinGeckoApiClient;

class CoinGeckoApiClientTest extends TestCase
{
    protected $responsePayload;
    protected $mockResponse;
    protected function setUp(): void
    {
        $this->responsePayload =  [
            "prices" => [
                [1670847878786, 46.396548362005],
                [1670848287566, 46.402436046387]
            ],
            "market_caps" => [
                [1670847878786, 46.396548362005],
                [1670848287566, 46.402436046387]
            ],
            "total_volumes" => [
                [1670847878786, 46.396548362005],
                [1670848287566, 46.402436046387]
            ],
        ];
    }

    protected function setupMulitpleResponse(int $count, ?MockHandler $mock = null): MockHandler
    {
        $payload = json_encode($this->responsePayload);
        if(is_null($mock)) {
            $mock = new MockHandler([
                new Response(200, [], $payload),
            ]);
        }

        for ($i = 0; $i < $count; $i++) { 
            $mock->append(new Response(200, [], $payload));
        }
        return $mock;
    }

    public function testCanBuildTheEndpoint(): void
    {
        $apiClient = new CoinGeckoApiClient();
        $apiClient = $apiClient->ping();
        $this->assertEquals($apiClient->getEndpointKey(), '/ping');

        $apiClient->reset();
        $apiClient = $apiClient->simple()->price();
        $this->assertEquals($apiClient->getEndpointKey(), '/simple/price');
    }

    public function testCanBuildNewEndpointWhenResetIsCalled(): void
    {
        $apiClient = new CoinGeckoApiClient();
        $apiClient = $apiClient->ping();
        $this->assertEquals($apiClient->getEndpointKey(), '/ping');

        $apiClient->reset();
        $apiClient = $apiClient->simple()->price();
        $this->assertEquals($apiClient->getEndpointKey(), '/simple/price');

        $apiClient->reset();
        $apiClient = $apiClient->coins()->markets();
        $this->assertEquals($apiClient->getEndpointKey(), '/coins/markets');
    }

    public function testCanBuildNewEndpointWhenSetIsCalled(): void
    {
        $apiClient = new CoinGeckoApiClient();
        $apiClient = $apiClient->set()->ping();
        $this->assertEquals($apiClient->getEndpointKey(), '/ping');

        $apiClient = $apiClient->set()->simple()->price();
        $this->assertEquals($apiClient->getEndpointKey(), '/simple/price');

        $apiClient = $apiClient->set()->coins()->markets();
        $this->assertEquals($apiClient->getEndpointKey(), '/coins/markets');
    }

    public function testCanBuildNewEndpointWhenResetIsCalledAfterSend(): void
    {
        $mock = $this->setupMulitpleResponse(3);
        $handlerStack = HandlerStack::create($mock);
        $mockHttpClientHandler = new Client(['handler' => $handlerStack]);
        $urlBuilder = new CoinGeckoUrlBuilder();
        $apiClient = new CoinGeckoApiClient(new ApiClient($urlBuilder->build(), $mockHttpClientHandler));

        $apiClient = $apiClient->ping();
        $apiClient->send();
        $this->assertEquals($apiClient->getEndpointKey(), '/ping');

        $apiClient->reset();
        $apiClient = $apiClient->simple()->price();
        $apiClient->send();
        $this->assertEquals($apiClient->getEndpointKey(), '/simple/price');

        $apiClient->reset();
        $apiClient = $apiClient->coins()->markets();
        $apiClient->send();
        $this->assertEquals($apiClient->getEndpointKey(), '/coins/markets');
    }

    public function testCanBuildNewEndpointWhenSetIsCalledAfterSend(): void
    {
        $mock = $this->setupMulitpleResponse(3);
        $handlerStack = HandlerStack::create($mock);
        $mockHttpClientHandler = new Client(['handler' => $handlerStack]);
        $urlBuilder = new CoinGeckoUrlBuilder();
        $apiClient = new CoinGeckoApiClient(new ApiClient($urlBuilder->build(), $mockHttpClientHandler));

        $apiClient = $apiClient->set()->ping();
        $apiClient->send();
        $this->assertEquals($apiClient->getEndpointKey(), '/ping');

        $apiClient = $apiClient->set()->simple()->price();
        $apiClient->send();
        $this->assertEquals($apiClient->getEndpointKey(), '/simple/price');

        $apiClient = $apiClient->set()->coins()->markets();
        $apiClient->send();
        $this->assertEquals($apiClient->getEndpointKey(), '/coins/markets');
    }

    public function testCanBuildNewEndpointWhenResetIsCalledAfterMultipleSend(): void
    {
        $mock = $this->setupMulitpleResponse(9);
        $handlerStack = HandlerStack::create($mock);
        $mockHttpClientHandler = new Client(['handler' => $handlerStack]);
        $urlBuilder = new CoinGeckoUrlBuilder();
        $apiClient = new CoinGeckoApiClient(new ApiClient($urlBuilder->build(), $mockHttpClientHandler));

        $apiClient = $apiClient->ping();
        $apiClient->send();
        $apiClient->send();
        $apiClient->send();
        $this->assertEquals($apiClient->getEndpointKey(), '/ping');

        $apiClient->reset();
        $apiClient = $apiClient->simple()->price();
        $apiClient->send();
        $apiClient->send();
        $apiClient->send();
        $this->assertEquals($apiClient->getEndpointKey(), '/simple/price');

        $apiClient->reset();
        $apiClient = $apiClient->coins()->markets();
        $apiClient->send();
        $apiClient->send();
        $apiClient->send();
        $this->assertEquals($apiClient->getEndpointKey(), '/coins/markets');
    }

    public function testCanBuildNewEndpointWhenSetIsCalledAfterMultipleSend(): void
    {
        $mock = $this->setupMulitpleResponse(9);
        $handlerStack = HandlerStack::create($mock);
        $mockHttpClientHandler = new Client(['handler' => $handlerStack]);
        $urlBuilder = new CoinGeckoUrlBuilder();
        $apiClient = new CoinGeckoApiClient(new ApiClient($urlBuilder->build(), $mockHttpClientHandler));

        $apiClient = $apiClient->set()->ping();
        $apiClient->send();
        $apiClient->send();
        $apiClient->send();
        $this->assertEquals($apiClient->getEndpointKey(), '/ping');

        $apiClient = $apiClient->set()->simple()->price();
        $apiClient->send();
        $apiClient->send();
        $apiClient->send();
        $this->assertEquals($apiClient->getEndpointKey(), '/simple/price');

        $apiClient = $apiClient->set()->coins()->markets();
        $apiClient->send();
        $apiClient->send();
        $apiClient->send();
        $this->assertEquals($apiClient->getEndpointKey(), '/coins/markets');
    }
    
    public function testCanBuildTheEndpointWithParameters(): void
    {
        $apiClient = new CoinGeckoApiClient();
        $apiClient = $apiClient->exchanges("PARAM1");
        $this->assertEquals($apiClient->getEndpointKey(), '/exchanges/%s');

        $apiClient->reset();
        $apiClient = $apiClient->exchanges("PARAM1")->tickers();
        $this->assertEquals($apiClient->getEndpointKey(), '/exchanges/%s/tickers');

        $apiClient->reset();
        $apiClient = $apiClient->indexes("PARAM1", "PARAM2")->tickers();
        $this->assertEquals($apiClient->getEndpointKey(), '/indexes/%s/%s/tickers');
    }
    
    public function testCanBuildTheEndpointWithUriVariation(): void
    {
        $apiClient = new CoinGeckoApiClient();
        $apiClient = $apiClient->ping();
        $this->assertEquals($apiClient->getEndpointKey(), '/ping');

        $apiClient->reset();
        $apiClient = $apiClient->Ping();
        $this->assertEquals($apiClient->getEndpointKey(), '/ping');

        $apiClient->reset();
        $apiClient = $apiClient->PING();
        $this->assertEquals($apiClient->getEndpointKey(), '/ping');

        $apiClient->reset();
        $apiClient = $apiClient->_PING_();
        $this->assertEquals($apiClient->getEndpointKey(), '/ping');

        $apiClient->reset();
        $apiClient = $apiClient->_PING();
        $this->assertEquals($apiClient->getEndpointKey(), '/ping');

        $apiClient->reset();
        $apiClient = $apiClient->PING_();
        $this->assertEquals($apiClient->getEndpointKey(), '/ping');

        $apiClient->reset();
        $apiClient = $apiClient->simple()->supportedvscurrencies();
        $this->assertEquals($apiClient->getEndpointKey(), '/simple/supportedvscurrencies');

        $apiClient->reset();
        $apiClient = $apiClient->SIMPLE()->supportedvsCurrencies();
        $this->assertEquals($apiClient->getEndpointKey(), '/simple/supportedvscurrencies');

        $apiClient->reset();
        $apiClient = $apiClient->SIMPLE()->SUPPORTEDVSCURRENCIES();
        $this->assertEquals($apiClient->getEndpointKey(), '/simple/supportedvscurrencies');

        $apiClient->reset();
        $apiClient = $apiClient->SIMPLE()->SUPPORTED_VS_CURRENCIES();
        $this->assertEquals($apiClient->getEndpointKey(), '/simple/supportedvscurrencies');

        $apiClient->reset();
        $apiClient = $apiClient->Simple()->SupportedVsCurrencies();
        $this->assertEquals($apiClient->getEndpointKey(), '/simple/supportedvscurrencies');

        $apiClient->reset();
        $apiClient = $apiClient->simple()->supportedVsCurrencies();
        $this->assertEquals($apiClient->getEndpointKey(), '/simple/supportedvscurrencies');

        $apiClient->reset();
        $apiClient = $apiClient->simple()->supported_vs_currencies();
        $this->assertEquals($apiClient->getEndpointKey(), '/simple/supportedvscurrencies');
    }
    
    public function testCanBuildTheEndpointWithUriVariationAndParameters(): void
    {
        $apiClient = new CoinGeckoApiClient();
        $apiClient = $apiClient->exchanges("PARAM1");
        $this->assertEquals($apiClient->getEndpointKey(), '/exchanges/%s');

        $apiClient->reset();
        $apiClient = $apiClient->_exchanges_("PARAM1");
        $this->assertEquals($apiClient->getEndpointKey(), '/exchanges/%s');

        $apiClient->reset();
        $apiClient = $apiClient->Exchanges("PARAM1");
        $this->assertEquals($apiClient->getEndpointKey(), '/exchanges/%s');

        $apiClient->reset();
        $apiClient = $apiClient->EXCHANGES("PARAM1");
        $this->assertEquals($apiClient->getEndpointKey(), '/exchanges/%s');

        $apiClient->reset();
        $apiClient = $apiClient->_EXCHANGES_("PARAM1");
        $this->assertEquals($apiClient->getEndpointKey(), '/exchanges/%s');

        $apiClient->reset();
        $apiClient = $apiClient->_EXCHANGES("PARAM1");
        $this->assertEquals($apiClient->getEndpointKey(), '/exchanges/%s');

        $apiClient->reset();
        $apiClient = $apiClient->EXCHANGES_("PARAM1");
        $this->assertEquals($apiClient->getEndpointKey(), '/exchanges/%s');

        $apiClient->reset();
        $apiClient = $apiClient->exchanges("PARAM1")->tickers();
        $this->assertEquals($apiClient->getEndpointKey(), '/exchanges/%s/tickers');

        $apiClient->reset();
        $apiClient = $apiClient->exchanges("PARAM1")->Tickers();
        $this->assertEquals($apiClient->getEndpointKey(), '/exchanges/%s/tickers');

        $apiClient->reset();
        $apiClient = $apiClient->exchanges("PARAM1")->TICKERS();
        $this->assertEquals($apiClient->getEndpointKey(), '/exchanges/%s/tickers');

        $apiClient->reset();
        $apiClient = $apiClient->exchanges("PARAM1")->_TICKERS_();
        $this->assertEquals($apiClient->getEndpointKey(), '/exchanges/%s/tickers');

        $apiClient->reset();
        $apiClient = $apiClient->exchanges("PARAM1")->_TICKERS();
        $this->assertEquals($apiClient->getEndpointKey(), '/exchanges/%s/tickers');

        $apiClient->reset();
        $apiClient = $apiClient->exchanges("PARAM1")->TICKERS_();
        $this->assertEquals($apiClient->getEndpointKey(), '/exchanges/%s/tickers');

        $apiClient->reset();
        $apiClient = $apiClient->indexes("PARAM1", "PARAM2");
        $this->assertEquals($apiClient->getEndpointKey(), '/indexes/%s/%s');

        $apiClient->reset();
        $apiClient = $apiClient->Indexes("PARAM1", "PARAM2");
        $this->assertEquals($apiClient->getEndpointKey(), '/indexes/%s/%s');

        $apiClient->reset();
        $apiClient = $apiClient->INDEXES("PARAM1", "PARAM2");
        $this->assertEquals($apiClient->getEndpointKey(), '/indexes/%s/%s');

        $apiClient->reset();
        $apiClient = $apiClient->_INDEXES_("PARAM1", "PARAM2");
        $this->assertEquals($apiClient->getEndpointKey(), '/indexes/%s/%s');
    }

    public function testCannotBuildUnknownEndpoint(): void
    {
        $apiClient = new CoinGeckoApiClient();
        try {
            $apiClient = $apiClient->pong();
        } catch (\ParseError $e) {
            $this->assertEquals($e->getCode(), -2);
        }

        try {
            $apiClient->reset();
            $apiClient = $apiClient->simple()->prices();
        } catch (\ParseError $e) {
            $this->assertEquals($e->getCode(), -2);
        }

        try {
            $apiClient->reset();
            $apiClient = $apiClient->simple()->tokenpriceNotFound();
        } catch (\ParseError $e) {
            $this->assertEquals($e->getCode(), -2);
        }
        $this->assertEquals($apiClient->getEndpointKey(), '/simple/tokenpricenotfound');
    }

    public function testCannotBuildUnknownEndpointWithParams(): void
    {
        $apiClient = new CoinGeckoApiClient();
        try {
            $apiClient = $apiClient->pong("PARAM1");
        } catch (\ParseError $e) {
            $this->assertEquals($e->getCode(), -2);
        }

        try {
            $apiClient = $apiClient->pong("PARAM1", "PARAM2");
        } catch (\ParseError $e) {
            $this->assertEquals($e->getCode(), -2);
        }

        try {
            $apiClient->reset();
            $apiClient = $apiClient->simple()->prices("PARAM1");
        } catch (\ParseError $e) {
            $this->assertEquals($e->getCode(), -2);
        }

        try {
            $apiClient->reset();
            $apiClient = $apiClient->simple("PARAM1")->tokenpriceNotFound("PARAM1");
        } catch (\ParseError $e) {
            $this->assertEquals($e->getCode(), -2);
        }
        $this->assertEquals($apiClient->getEndpointKey(), '/simple/%s/tokenpricenotfound/%s');
    }

    public function testCanHandleTheApiResponse(): void
    {
        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], json_encode($this->responsePayload)),
            new Response(202, ['Content-Length' => 0]),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $mockHttpClientHandler = new Client(['handler' => $handlerStack]);
        $urlBuilder = new CoinGeckoUrlBuilder();
        $apiClient = new CoinGeckoApiClient(new ApiClient($urlBuilder->build(), $mockHttpClientHandler));

        $response = $apiClient
            ->coins("verus-coin")
            ->marketChart()
            ->send($urlBuilder->withDays(1)->withVsCurrency("jpy"));
        $this->assertEquals($response, $this->responsePayload);

        $apiClient->reset();
        $response = $apiClient
            ->coins("verus-coin")
            ->marketChart()
            ->send($urlBuilder->withDays(1)->withVsCurrency("jpy"));
        $this->assertEquals($response, []);
    }

    public function testCanHandleParseError(): void
    {
        $urlBuilder = new CoinGeckoUrlBuilder();
        $apiClient = new CoinGeckoApiClient();
        try {
            $apiClient->send($urlBuilder->withDays(1)->withVsCurrency("jpy"));
        } catch (\ParseError $e) {
            $this->assertEquals(
                $e->getCode(),
                -1
            );
        }
    }

    public function testCanHandleRequestException(): void
    {
        $mock = new MockHandler([
            new Response(404, ['Content-Length' => 0]),
            new RequestException('Error Communicating with Server', new Request('GET', 'test'))
        ]);
        $handlerStack = HandlerStack::create($mock);
        $mockHttpClientHandler = new Client(['handler' => $handlerStack]);
        $urlBuilder = new CoinGeckoUrlBuilder();
        $apiClient = new CoinGeckoApiClient(new ApiClient($urlBuilder->build(), $mockHttpClientHandler));

        try {
            $apiClient
                ->coins("verus-coin")
                ->marketChart()
                ->send($urlBuilder->withDays(1)->withVsCurrency("jpy"));
        } catch (RequestException $e) {
            $this->assertEquals(
                $e->getCode(),
                404
            );
        }

        try {
            $apiClient->reset();
            $response = $apiClient
                ->coins("verus-coin")
                ->marketChart()
                ->send($urlBuilder->withDays(1)->withVsCurrency("jpy"));
            $this->assertEquals($response, []);
        } catch (RequestException $e) {
            $this->assertEquals(
                $e->getMessage(),
                'Error Communicating with Server'
            );
        }
    }

    public function testCanHandleRequestWith300Response(): void
    {
        $mock = new MockHandler([
            new Response(300, ['Content-Length' => 0]),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $mockHttpClientHandler = new Client(['handler' => $handlerStack]);
        $urlBuilder = new CoinGeckoUrlBuilder();
        $apiClient = new CoinGeckoApiClient(new ApiClient($urlBuilder->build(), $mockHttpClientHandler));

        try {
            $response = $apiClient
                ->coins("verus-coin")
                ->marketChart()
                ->send($urlBuilder->withDays(1)->withVsCurrency("jpy"));
            $this->assertEquals($response, []);
        } catch (RequestException $e) {
            $this->assertEquals(
                $e->getMessage(),
                'Error Communicating with Server'
            );
        }
    }

    public function testCanSendTheSameRequestMultipleTimes(): void
    {
        $mock = $this->setupMulitpleResponse(3);
        $handlerStack = HandlerStack::create($mock);
        $mockHttpClientHandler = new Client(['handler' => $handlerStack]);
        $urlBuilder = new CoinGeckoUrlBuilder();
        $apiClient = new CoinGeckoApiClient(new ApiClient($urlBuilder->build(), $mockHttpClientHandler));

        $request = $apiClient->set()->ping();
        $this->assertEquals($request->send(), $this->responsePayload);
        $this->assertEquals($request->send(), $this->responsePayload);
        $this->assertEquals($request->send(), $this->responsePayload);
    }

    public function testCanBuildAllApiEndpoints(): void
    {
        $defaultResponse = json_encode($this->responsePayload);
        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], $defaultResponse),
        ]);
        for ($x = 0; $x <= count(CoinGeckoApiClient::API_ENPOINTS_MAP); $x++) {
            $mock->append(new Response(200, ['X-Foo' => 'Bar'], $defaultResponse));
        }

        $handlerStack = HandlerStack::create($mock);
        $mockHttpClientHandler = new Client(['handler' => $handlerStack]);
        $urlBuilder = new CoinGeckoUrlBuilder();
        $apiClient = new CoinGeckoApiClient(new ApiClient($urlBuilder->build(), $mockHttpClientHandler));

        $endpointRequests = [
            $apiClient->set()->ping(),
            $apiClient->set()->simple()->price(),
            $apiClient->set()->simple()->tokenPrice("PARAM"),
            $apiClient->set()->simple()->supportedVsCurrencies(),
            $apiClient->set()->coins()->list(),
            $apiClient->set()->coins()->markets(),
            $apiClient->set()->coins("PARAM"),
            $apiClient->set()->coins("PARAM")->tickers(),
            $apiClient->set()->coins("PARAM")->history(),
            $apiClient->set()->coins("PARAM")->marketChart(),
            $apiClient->set()->coins("PARAM")->marketChart()->range(),
            $apiClient->set()->coins("PARAM")->contract("PARAM"),
            $apiClient->set()->coins("PARAM")->contract("PARAM")->marketChart(),
            $apiClient->set()->coins("PARAM")->contract("PARAM")->marketChart()->range(),
            $apiClient->set()->coins("PARAM")->ohlc(),
            $apiClient->set()->coins()->categories(),
            $apiClient->set()->coins()->categories()->list(),
            $apiClient->set()->assetPlatforms(),
            $apiClient->set()->exchanges(),
            $apiClient->set()->exchanges("PARAM"),
            $apiClient->set()->exchanges("PARAM")->tickers(),
            $apiClient->set()->exchanges("PARAM")->volumeChart(),
            $apiClient->set()->exchanges()->list(),
            $apiClient->set()->indexes(),
            $apiClient->set()->indexes("PARAM1", "PARAM2"),
            $apiClient->set()->indexes()->list(),
            $apiClient->set()->derivatives(),
            $apiClient->set()->derivatives()->exchanges(),
            $apiClient->set()->derivatives()->exchanges("PARAM"),
            $apiClient->set()->derivatives()->exchanges()->list(),
            $apiClient->set()->nfts("PARAM"),
            $apiClient->set()->nfts("PARAM")->contract("PARAM"),
            $apiClient->set()->nfts()->list(),
            $apiClient->set()->exchangeRates(),
            $apiClient->set()->search(),
            $apiClient->set()->search()->trending(),
            $apiClient->set()->global(),
            $apiClient->set()->global()->decentralizedFinanceDefi(),
            $apiClient->set()->companies()->publicTreasury("PARAM"),
            //PRO Version
            $apiClient->set()->nfts()->markets(),
            $apiClient->set()->nfts("PARAM")->marketchart(),
            $apiClient->set()->nfts("PARAM")->contract("PARAM")->marketchart(),
            $apiClient->set()->nfts("PARAM")->tickers(),
            $apiClient->set()->global()->marketcapchart(),
        ];

        foreach ($endpointRequests as $key => $currentEndpointRequest) {
            $this->assertInstanceOf(CoinGeckoApiClient::class, $currentEndpointRequest);
        }
    }

    public function testCanSendToAllApiEndpoints(): void
    {
        $defaultResponse = json_encode($this->responsePayload);
        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], $defaultResponse),
        ]);
        for ($x = 0; $x <= count(CoinGeckoApiClient::API_ENPOINTS_MAP); $x++) {
            $mock->append(new Response(200, ['X-Foo' => 'Bar'], $defaultResponse));
        }

        $handlerStack = HandlerStack::create($mock);
        $mockHttpClientHandler = new Client(['handler' => $handlerStack]);
        $urlBuilder = new CoinGeckoUrlBuilder();
        $apiClient = new CoinGeckoApiClient(new ApiClient($urlBuilder->build(), $mockHttpClientHandler));

        $endpointResponse = [
            $apiClient->set()->ping()->send(),
            $apiClient->set()->simple()->price()->send(),
            $apiClient->set()->simple()->tokenPrice("PARAM")->send(),
            $apiClient->set()->simple()->supportedVsCurrencies()->send(),
            $apiClient->set()->coins()->list()->send(),
            $apiClient->set()->coins()->markets()->send(),
            $apiClient->set()->coins("PARAM")->send(),
            $apiClient->set()->coins("PARAM")->tickers()->send(),
            $apiClient->set()->coins("PARAM")->history()->send(),
            $apiClient->set()->coins("PARAM")->marketChart()->send(),
            $apiClient->set()->coins("PARAM")->marketChart()->range()->send(),
            $apiClient->set()->coins("PARAM")->contract("PARAM")->send(),
            $apiClient->set()->coins("PARAM")->contract("PARAM")->marketChart()->send(),
            $apiClient->set()->coins("PARAM")->contract("PARAM")->marketChart()->range()->send(),
            $apiClient->set()->coins("PARAM")->ohlc()->send(),
            $apiClient->set()->coins()->categories()->send(),
            $apiClient->set()->coins()->categories()->list()->send(),
            $apiClient->set()->assetPlatforms()->send(),
            $apiClient->set()->exchanges()->send(),
            $apiClient->set()->exchanges("PARAM")->send(),
            $apiClient->set()->exchanges("PARAM")->tickers()->send(),
            $apiClient->set()->exchanges("PARAM")->volumeChart()->send(),
            $apiClient->set()->exchanges()->list()->send(),
            $apiClient->set()->indexes()->send(),
            $apiClient->set()->indexes("PARAM1", "PARAM2")->send(),
            $apiClient->set()->indexes()->list()->send(),
            $apiClient->set()->derivatives()->send(),
            $apiClient->set()->derivatives()->exchanges()->send(),
            $apiClient->set()->derivatives()->exchanges("PARAM")->send(),
            $apiClient->set()->derivatives()->exchanges()->list()->send(),
            $apiClient->set()->nfts("PARAM")->send(),
            $apiClient->set()->nfts("PARAM")->contract("PARAM")->send(),
            $apiClient->set()->nfts()->list()->send(),
            $apiClient->set()->exchangeRates()->send(),
            $apiClient->set()->search()->send(),
            $apiClient->set()->search()->trending()->send(),
            $apiClient->set()->global()->send(),
            $apiClient->set()->global()->decentralizedFinanceDefi()->send(),
            $apiClient->set()->companies()->publicTreasury("PARAM")->send(),
            //PRO Version
            $apiClient->set()->nfts()->markets()->sendPro($urlBuilder->withXcGProApiKey("API_KEY")),
            $apiClient->set()->nfts("PARAM")->marketchart()->sendPro($urlBuilder->withXcGProApiKey("API_KEY")),
            $apiClient->set()->nfts("PARAM")->contract("PARAM")->marketchart()->sendPro($urlBuilder->withXcGProApiKey("API_KEY")),
            $apiClient->set()->nfts("PARAM")->tickers()->sendPro($urlBuilder->withXcGProApiKey("API_KEY")),
            $apiClient->set()->global()->marketcapchart()->sendPro($urlBuilder->withXcGProApiKey("API_KEY")),
        ];

        foreach ($endpointResponse as $currentEndpointResponse) {
            $this->assertEquals($currentEndpointResponse, $this->responsePayload);
        }
    }

    public function testCanPrepareApiRequestInArrayToSendLater(): void
    {
        $defaultResponse = json_encode($this->responsePayload);
        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], $defaultResponse),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $mockHttpClientHandler = new Client(['handler' => $handlerStack]);
        $urlBuilder = new CoinGeckoUrlBuilder();
        $apiClient = new CoinGeckoApiClient(new ApiClient($urlBuilder->build(), $mockHttpClientHandler));
        $endpointRequest = [
            $apiClient->set()->ping(),
            $apiClient->set()->simple()->price(),
            $apiClient->set()->simple()->tokenPrice("PARAM"),
            $apiClient->set()->ping(),
            $apiClient->set()->simple()->price(),
            $apiClient->set()->simple()->tokenPrice("PARAM"),
        ];
        
        $this->setupMulitpleResponse(count($endpointRequest), $mock);

        foreach ($endpointRequest as $currentRequest) {
            $res = $currentRequest->send();
            $this->assertEquals($res, $this->responsePayload);
        }
    }
}
