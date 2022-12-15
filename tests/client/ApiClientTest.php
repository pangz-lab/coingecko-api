<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use PangzLab\CoinGecko\Client\ApiClient;
use PangzLab\CoinGecko\Client\CoinGeckoUrlBuilder;

final class ApiClientTest extends TestCase
{
    public function testCanGetTheURL(): void
    {
        $urlBuilder = new CoinGeckoUrlBuilder([], "/coins/verus-coin/market_chart");
        $client = new ApiClient($urlBuilder->build());
        $this->assertEquals(
            $client->getUrl(),
            "/coins/verus-coin/market_chart"
        );
    }

    public function testCanBuildURL(): void
    {
        $urlBuilder = new CoinGeckoUrlBuilder([], "/coins/verus-coin/market_chart");
        $client = new ApiClient();
        $client = $client
            ->setUrlBuilder(
                $urlBuilder
                    ->withDays(7)
                    ->withVsCurrency("jpy")
            );

        $this->assertEquals(
            $client->getUrl(),
            "/coins/verus-coin/market_chart?days=7&vs_currency=jpy"
        );
    }

    public function testCanBuildUrlWithoutParameters(): void
    {
        $urlBuilder = new CoinGeckoUrlBuilder([], "/coins/verus-coin/market_chart");
        $client = new ApiClient($urlBuilder->build());
        $this->assertEquals(
            $client->getUrl(),
            "/coins/verus-coin/market_chart"
        );
    }

    public function testCanBuildURLWithCustomEndpoint(): void
    {
        $urlBuilder = new CoinGeckoUrlBuilder([], "/coins/verus-coin/market_chart");
        $client = new ApiClient($urlBuilder->build());
        $client = $client->setUrlBuilder($urlBuilder->withDays(7)->withVsCurrency("jpy"));

        $this->assertEquals(
            $client->getUrl(),
            "/coins/verus-coin/market_chart?days=7&vs_currency=jpy"
        );

        $urlBuilder = new CoinGeckoUrlBuilder([["days", "9999"], ["vs_currency", "usd"]], "/coins/verus-coin/market_chart");
        $client = new ApiClient($urlBuilder->build());
        $client = $client->setUrlBuilder($urlBuilder->withDays(7)->withVsCurrency("jpy"));

        $this->assertEquals(
            $client->getUrl(),
            "/coins/verus-coin/market_chart?days=7&vs_currency=jpy"
        );
    }

    public function testCanHandlerHttpResponse(): void
    {
        $responsePayload =  [
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
        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], json_encode($responsePayload)),
            new Response(202, ['Content-Length' => 0]),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $httpClient = new Client(['handler' => $handlerStack]);

        $urlBuilder = new CoinGeckoUrlBuilder([], "/coins/verus-coin/market_chart");
        $client = new ApiClient($urlBuilder->build(), $httpClient);
        $response = $client->setUrlBuilder($urlBuilder->withDays(1)->withVsCurrency("jpy"))->send();
        $this->assertEquals($response, $responsePayload);

        $response = $client->setUrlBuilder($urlBuilder->withDays(1)->withVsCurrency("jpy"))->send();
        $this->assertEquals($response, []);
    }

    public function testCanHandleRequestException(): void
    {
        $mock = new MockHandler([
            new Response(404, ['Content-Length' => 0]),
            new RequestException('Error Communicating with Server', new Request('GET', 'test'))
        ]);
        $handlerStack = HandlerStack::create($mock);
        $httpClient = new Client(['handler' => $handlerStack]);

        $urlBuilder = new CoinGeckoUrlBuilder([], "/coins/verus-coin/market_chart");
        $client = new ApiClient($urlBuilder->build(), $httpClient);

        try {
            $response = $client
                ->setUrlBuilder(
                    $urlBuilder
                        ->withDays(1)
                        ->withVsCurrency("jpy")
                )->send();
            $this->assertEquals($response, []);
        } catch (RequestException $e) {
            $this->assertEquals(
                $e->getCode(),
                404
            );
        }

        try {
            $response = $client
                ->setUrlBuilder(
                    $urlBuilder
                        ->withDays(1)
                        ->withVsCurrency("jpy")
                )->send();
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
        $httpClient = new Client(['handler' => $handlerStack]);

        $urlBuilder = new CoinGeckoUrlBuilder([], "coins/verus-coin/market_chart");
        $client = new ApiClient($urlBuilder->build(), $httpClient);
        $response = $client
            ->setUrlBuilder(
                $urlBuilder
                    ->withDays(1)
                    ->withVsCurrency("jpy")
            )->send();
        $this->assertEquals($response, []);
    }
}
