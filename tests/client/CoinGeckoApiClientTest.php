<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Client;
use PangzLab\CoinGecko\Client\ApiClient;
use PangzLab\CoinGecko\Client\ApiUrlBuilder;
use PangzLab\CoinGecko\Client\CoinGeckoApiClient;
use GuzzleHttp\Exception\RequestException;

class CoinGeckoApiClientTest extends TestCase
{
  protected $responsePayload;
  protected function setUp(): void
  {
    $this->responsePayload =  [
      "prices" => [
        [ 1670847878786, 46.396548362005 ],
        [ 1670848287566, 46.402436046387 ]
      ],
      "market_caps" => [
        [ 1670847878786, 46.396548362005 ],
        [ 1670848287566, 46.402436046387 ]
      ],
      "total_volumes" => [
        [ 1670847878786, 46.396548362005 ],
        [ 1670848287566, 46.402436046387 ]
      ],
    ];
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
    $urlBuilder = new ApiUrlBuilder();
    $apiClient = new CoinGeckoApiClient(new ApiClient($urlBuilder->build(), $mockHttpClientHandler));

    $response = $apiClient
      ->coins("verus-coin")
      ->marketChart()
      ->send(
        $urlBuilder
          ->withDays(1)
          ->withVsCurrency("jpy"));
    $this->assertEquals($response, $this->responsePayload);

    $apiClient->reset();
    $response = $apiClient
    ->coins("verus-coin")
    ->marketChart()
    ->send(
      $urlBuilder
        ->withDays(1)
        ->withVsCurrency("jpy"));
    $this->assertEquals($response, []);
  }

  public function testCanHandleParseError(): void
  {
    $urlBuilder = new ApiUrlBuilder();
    $apiClient = new CoinGeckoApiClient();
    try {
      $response = $apiClient
      ->send($urlBuilder
        ->withDays(1)
        ->withVsCurrency("jpy")
      );
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
    $httpClient = new Client(['handler' => $handlerStack]);
    $mockHttpClientHandler = new Client(['handler' => $handlerStack]);
    $urlBuilder = new ApiUrlBuilder();
    $apiClient = new CoinGeckoApiClient(new ApiClient($urlBuilder->build(), $mockHttpClientHandler));

    try {
      $response = $apiClient
      ->coins("verus-coin")
      ->marketChart()
      ->send($urlBuilder
        ->withDays(1)
        ->withVsCurrency("jpy")
      );
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
        ->send($urlBuilder
          ->withDays(1)
          ->withVsCurrency("jpy")
        );
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
    $urlBuilder = new ApiUrlBuilder();
    $apiClient = new CoinGeckoApiClient(new ApiClient($urlBuilder->build(), $mockHttpClientHandler));

    try {
    $response = $apiClient
      ->coins("verus-coin")
      ->marketChart()
      ->send($urlBuilder
        ->withDays(1)
        ->withVsCurrency("jpy")
      );
      $this->assertEquals($response, []);
    } catch (RequestException $e) {
      $this->assertEquals(
        $e->getMessage(),
        'Error Communicating with Server'
      );
    }
  }

  public function testCanSendTheSameRequestMultipleTimes(): void {
    $jsonResponse = json_encode($this->responsePayload);
    $defaultResponse = new Response(200, ['X-Foo' => 'Bar'], json_encode($jsonResponse));
    $mock = new MockHandler([
      new Response(200, ['X-Foo' => 'Bar'], $jsonResponse),
      new Response(200, ['X-Foo' => 'Bar'], $jsonResponse),
      new Response(200, ['X-Foo' => 'Bar'], $jsonResponse),
    ]);
    $handlerStack = HandlerStack::create($mock);
    $mockHttpClientHandler = new Client(['handler' => $handlerStack]);
    $urlBuilder = new ApiUrlBuilder();
    $apiClient = new CoinGeckoApiClient(new ApiClient($urlBuilder->build(), $mockHttpClientHandler));

    $request = $apiClient->get()->ping();
    $this->assertEquals($request->send(), $this->responsePayload);
    $this->assertEquals($request->send(), $this->responsePayload);
    $this->assertEquals($request->send(), $this->responsePayload);
  }

  public function testCanFormAndSendAllApiEndpoints(): void
  {
    $defaultResponse = json_encode($this->responsePayload);
    $mock = new MockHandler([
      new Response(200, ['X-Foo' => 'Bar'], $defaultResponse),
    ]);
    for($x = 0; $x <= count(CoinGeckoApiClient::API_ENPOINTS_MAP) ; $x++) {
      $mock->append(new Response(200, ['X-Foo' => 'Bar'], $defaultResponse));
    }

    $handlerStack = HandlerStack::create($mock);
    $mockHttpClientHandler = new Client(['handler' => $handlerStack]);
    $urlBuilder = new ApiUrlBuilder();
    $apiClient = new CoinGeckoApiClient(new ApiClient($urlBuilder->build(), $mockHttpClientHandler));

    $endpointResponse = [
      $apiClient->get()->ping()->send(),
      $apiClient->get()->simple()->price()->send(),
      $apiClient->get()->simple()->tokenPrice("PARAM")->send(),
      $apiClient->get()->simple()->supportedVsCurrencies()->send(),
      $apiClient->get()->coins()->list()->send(),
      $apiClient->get()->coins()->markets()->send(),
      $apiClient->get()->coins("PARAM")->send(),
      $apiClient->get()->coins("PARAM")->tickers()->send(),
      $apiClient->get()->coins("PARAM")->history()->send(),
      $apiClient->get()->coins("PARAM")->marketChart()->send(),
      $apiClient->get()->coins("PARAM")->marketChart()->range()->send(),
      $apiClient->get()->coins("PARAM")->contract("PARAM")->send(),
      $apiClient->get()->coins("PARAM")->contract("PARAM")->marketChart()->send(),
      $apiClient->get()->coins("PARAM")->contract("PARAM")->marketChart()->range()->send(),
      $apiClient->get()->coins("PARAM")->ohlc()->send(),
      $apiClient->get()->coins()->categories()->send(),
      $apiClient->get()->coins()->categories()->list()->send(),
      $apiClient->get()->assetPlatforms()->send(),
      $apiClient->get()->exchanges()->send(),
      $apiClient->get()->exchanges("PARAM")->send(),
      $apiClient->get()->exchanges("PARAM")->tickers()->send(),
      $apiClient->get()->exchanges("PARAM")->volumeChart()->send(),
      $apiClient->get()->exchanges()->list()->send(),
      $apiClient->get()->indexes()->send(),
      $apiClient->get()->indexes("PARAM1", "PARAM2")->send(),
      $apiClient->get()->indexes()->list()->send(),
      $apiClient->get()->derivatives()->send(),
      $apiClient->get()->derivatives()->exchanges()->send(),
      $apiClient->get()->derivatives()->exchanges("PARAM")->send(),
      $apiClient->get()->derivatives()->exchanges()->list()->send(),
      $apiClient->get()->nfts("PARAM")->send(),
      $apiClient->get()->nfts("PARAM")->contract("PARAM")->send(),
      $apiClient->get()->nfts()->list()->send(),
      $apiClient->get()->exchangeRates()->send(),
      $apiClient->get()->search()->send(),
      $apiClient->get()->search()->trending()->send(),
      $apiClient->get()->global()->send(),
      $apiClient->get()->global()->decentralizedFinanceDefi()->send(),
      $apiClient->get()->companies()->publictreasury("PARAM")->send(),
    ];

    foreach($endpointResponse as $currentEndpointResponse) {
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
    $urlBuilder = new ApiUrlBuilder();
    $apiClient = new CoinGeckoApiClient(new ApiClient($urlBuilder->build(), $mockHttpClientHandler));
    $endpointRequest = [
      $apiClient->get()->ping(),
      $apiClient->get()->simple()->price(),
      $apiClient->get()->simple()->tokenPrice("PARAM"),
      $apiClient->get()->ping(),
      $apiClient->get()->simple()->price(),
      $apiClient->get()->simple()->tokenPrice("PARAM"),
    ];

    for($x = 0; $x < count($endpointRequest) ; $x++) {
      $mock->append(new Response(200, ['X-Foo' => 'Bar'], $defaultResponse));
    }

    foreach($endpointRequest as $currentRequest) {
      $res = $currentRequest->send();
      $this->assertEquals($res, $this->responsePayload);
    }
  }
}