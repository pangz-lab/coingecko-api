<?php
declare(strict_types=1);

namespace PangzLab\CoinGecko\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use PangzLab\CoinGecko\Client\CoinGeckoUrlBuilder;
use PangzLab\Client\CoinGecko\Service\HttpClient;

class ApiClient
{
  private $client;
  private $url;

  public function __construct(
    string $url = "",
    ClientInterface $client = null
  ) {
    $this->client = is_null($client)? new HttpClient() : $client;
    $this->url = $url;
  }
  
  public function getUrl(): string
  {
    return $this->url;
  }

  public function send(): array
  {
    $url = $this->getUrl();
    try {
      $response = $this->client->request('GET', $url);
      if($response->getStatusCode() == 200) {
        return json_decode(
          $response->getBody()->getContents(),
          true
        );
      }
      return [];

    } catch (RequestException $e) {
      throw $e;
    } catch (Exception $e) {
      throw $e;
    }

  }

  public function setUrl(string $url): ApiClient
  {
    return new ApiClient(
      $url,
      $this->client
    );
  }

  public function setUrlBuilder(CoinGeckoUrlBuilder $builder): ApiClient
  {
    return $this->setUrl($builder->build());
  }
}