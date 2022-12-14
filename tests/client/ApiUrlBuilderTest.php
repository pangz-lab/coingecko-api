<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use PangzLab\CoinGecko\Client\ApiUrlBuilder;
use PangzLab\Client\CoinGecko\Service\HttpClient;

final class ApiUrlBuilderTest extends TestCase
{
  const API_BASE_URL = "https://api.coingecko.com/api/";
  public function testCanBuildUrl(): void
  {
    $urlBuilder = new ApiUrlBuilder();
    $this->assertEquals(
      $urlBuilder->setEndpoint()->build(),
      self::API_BASE_URL . "v3"
    );
  }

  public function testCanBuildUrlWithSpecificVersion(): void
  {
    $urlBuilder = new ApiUrlBuilder([], "", "v4");
    $this->assertEquals(
      $urlBuilder->setEndpoint()->build(),
      self::API_BASE_URL . "v4"
    );
  }

  public function testCanBuildUrlWithSpecificEndpoint(): void
  {
    $urlBuilder = new ApiUrlBuilder([], "/coins/btc/market_chart", "v4");
    $this->assertEquals(
      $urlBuilder->setEndpoint()->build(),
      self::API_BASE_URL . "v4/coins/btc/market_chart"
    );
  }

  public function testCanBuildUrlWithSpecificEndpointInCreate(): void
  {
    $urlBuilder = new ApiUrlBuilder([], "/will/be/replaced", "v4");
    $this->assertEquals(
      $urlBuilder->setEndpoint("/coins/verus-coin/market_chart")->build(),
      self::API_BASE_URL . "v4/coins/verus-coin/market_chart"
    );
  }

  public function testCanBuildUrlWithCustomParameters(): void
  {
    $urlBuilder = new ApiUrlBuilder([["apiToken","44343"],["id","verus-coin"]]);
    $this->assertEquals(
      $urlBuilder->setEndpoint()->build(),
      self::API_BASE_URL . "v3?apiToken=44343&id=verus-coin"
    );

    $urlBuilder = new ApiUrlBuilder([["apiToken","44343"],["id","verus-coin"]], "/coins/verus-coin/market_chart");
    $this->assertEquals(
      $urlBuilder->setEndpoint()->build(),
      self::API_BASE_URL . "v3/coins/verus-coin/market_chart?apiToken=44343&id=verus-coin"
    );

    $urlBuilder = new ApiUrlBuilder([["apiToken","44343"],["id","verus-coin"]], "/coins/verus-coin/market_chart", "v5");
    $this->assertEquals(
      $urlBuilder->setEndpoint()->build(),
      self::API_BASE_URL . "v5/coins/verus-coin/market_chart?apiToken=44343&id=verus-coin"
    );
  }

  public function testShouldOverrideByLatestParametersForTheSameKey(): void
  {
    $urlBuilder = new ApiUrlBuilder([["id","44343","id","verus-coin"]]);
    $this->assertEquals(
      $urlBuilder->setEndpoint()
        ->withId(222)
        ->build(),
      self::API_BASE_URL . "v3?id=222"
    );
  }

  public function testCanBuildUrlFromInstantiationAndCreateMethod(): void
  {
    $urlBuilder = new ApiUrlBuilder([["apiToken","44343"],["id","verus-coin"]]);
    $this->assertEquals(
      $urlBuilder->setEndpoint()
        ->withVsCurrency("jpy")
        ->withId("232")
        ->build(),
      self::API_BASE_URL . "v3?apiToken=44343&id=232&vs_currency=jpy"
    );

    $urlBuilder = new ApiUrlBuilder([["apiToken","44343"],["id","verus-coin"]]);
    $this->assertEquals(
      $urlBuilder->setEndpoint("/coin/btc/market_chart")
        ->withVsCurrency("jpy")
        ->build(),
      self::API_BASE_URL . "v3/coin/btc/market_chart?apiToken=44343&id=verus-coin&vs_currency=jpy"
    );

    $urlBuilder = new ApiUrlBuilder([["apiToken","44343"],["id","verus-coin"]], "/coin/btc/market_chart");
    $this->assertEquals(
      $urlBuilder->setEndpoint()
        ->withVsCurrency("jpy")
        ->build(),
      self::API_BASE_URL . "v3/coin/btc/market_chart?apiToken=44343&id=verus-coin&vs_currency=jpy"
    );

    $urlBuilder = new ApiUrlBuilder([["apiToken","44343"],["id","verus-coin"]], "/coin/btc/market_chart");
    $this->assertEquals(
      $urlBuilder->setEndpoint("/coin/verus-coin/market_chart")
        ->withVsCurrency("jpy")
        ->build(),
      self::API_BASE_URL . "v3/coin/verus-coin/market_chart?apiToken=44343&id=verus-coin&vs_currency=jpy"
    );
  }

  public function testCanBuildUrlWithUnknownParameterInInstantiation(): void
  {
    $urlBuilder = new ApiUrlBuilder([["withRandomParameters", "42343"]]);
    $this->assertEquals(
      $urlBuilder->setEndpoint()->build(),
      self::API_BASE_URL . "v3?withRandomParameters=42343"
    );
  }

  public function testCannotBuildUrlFromUnknownParameter(): void
  {
    $urlBuilder = new ApiUrlBuilder();
    try {
      $urlBuilder
        ->setEndpoint()
        ->withRandomParameters(42343)
        ->build();
    } catch (ParseError $e) {
      $this->assertEquals(
        $e->getMessage(),
        "Unknown method used. Setter name : withRandomParameters"
      );
    }
  }

  public function testCanCleanupWhenCleanIsCalled(): void
  {
    $urlBuilder = new ApiUrlBuilder([], "/coins/btc/market_chart", "v4");
    $this->assertEquals(
      $urlBuilder->setEndpoint()->build(),
      self::API_BASE_URL . "v4/coins/btc/market_chart"
    );

    $urlBuilder = $urlBuilder->clean();
    $this->assertEquals(
      $urlBuilder->setEndpoint()->build(),
      self::API_BASE_URL . "v4"
    );
  }

  public function testCanUseParameterKeysInDifferentCases(): void
  {
    $urlBuilder = new ApiUrlBuilder([]);
    $this->assertEquals(
      $urlBuilder->withId("PARAM")
        ->build(),
      self::API_BASE_URL . "v3?id=PARAM"
    );

    $this->assertEquals(
      $urlBuilder->withID("PARAM")
        ->build(),
      self::API_BASE_URL . "v3?id=PARAM"
    );

    $this->assertEquals(
      $urlBuilder->withID("PARAM")
        ->build(),
      self::API_BASE_URL . "v3?id=PARAM"
    );

    $this->assertEquals(
      $urlBuilder->WITHID("PARAM")
        ->build(),
      self::API_BASE_URL . "v3?id=PARAM"
    );

    $this->assertEquals(
      $urlBuilder->_withId_("PARAM")
        ->build(),
      self::API_BASE_URL . "v3?id=PARAM"
    );

    $this->assertEquals(
      $urlBuilder->_WITHID_("PARAM")
        ->build(),
      self::API_BASE_URL . "v3?id=PARAM"
    );

    $this->assertEquals(
      $urlBuilder->_withid_("PARAM")
        ->build(),
      self::API_BASE_URL . "v3?id=PARAM"
    );

    $this->assertEquals(
      $urlBuilder->_WithId_("PARAM")
        ->build(),
      self::API_BASE_URL . "v3?id=PARAM"
    );

    $this->assertEquals(
      $urlBuilder->_WITH_ID_("PARAM")
        ->build(),
      self::API_BASE_URL . "v3?id=PARAM"
    );
  }

  public function testCanUseAllAllowedParameterKeys(): void
  {
    $urlBuilder = new ApiUrlBuilder([]);
    $this->assertEquals(
      $urlBuilder->withId("PARAM")
        ->withIds("PARAM")
        ->withVsCurrencies("PARAM")
        ->withIncludeMarketcap("PARAM")
        ->withInclude24HrVol("PARAM")
        ->withInclude24HrChange("PARAM")
        ->withIncludeLastUpdatedAt("PARAM")
        ->withPrecision("PARAM")
        ->withContractAddresses("PARAM")
        ->withIncludePlatform("PARAM")
        ->withVsCurrency("PARAM")
        ->withCategory("PARAM")
        ->withOrder("PARAM")
        ->withPerPage("PARAM")
        ->withPage("PARAM")
        ->withSparkline("PARAM")
        ->withPriceChangePercentage("PARAM")
        ->withLocalization("PARAM")
        ->withTickers("PARAM")
        ->withMarketData("PARAM")
        ->withCommunityData("PARAM")
        ->withDeveloperData("PARAM")
        ->withExchangeIds("PARAM")
        ->withIncludeExchangeLogo("PARAM")
        ->withDepth("PARAM")
        ->withDate("PARAM")
        ->withDays("PARAM")
        ->withInterval("PARAM")
        ->withFrom("PARAM")
        ->withTo("PARAM")
        ->withContractAddress("PARAM")
        ->withFilter("PARAM")
        ->withCoinIds("PARAM")
        ->withMarketId("PARAM")
        ->withIncludeTickers("PARAM")
        ->withAssetPlatformId("PARAM")
        ->withQuery("PARAM")
        ->withCoinId("PARAM")
        ->build(),
      self::API_BASE_URL . "v3?id=PARAM&ids=PARAM&vs_currencies=PARAM&include_market_cap=PARAM&include_24hr_vol=PARAM&include_24hr_change=PARAM&include_last_updated_at=PARAM&precision=PARAM&contract_addresses=PARAM&include_platform=PARAM&vs_currency=PARAM&category=PARAM&order=PARAM&per_page=PARAM&page=PARAM&sparkline=PARAM&price_change_percentage=PARAM&localization=PARAM&tickers=PARAM&market_data=PARAM&community_data=PARAM&developer_data=PARAM&exchange_ids=PARAM&include_exchange_logo=PARAM&depth=PARAM&date=PARAM&days=PARAM&interval=PARAM&from=PARAM&to=PARAM&contract_address=PARAM&filter=PARAM&coin_ids=PARAM&market_id=PARAM&include_tickers=PARAM&asset_platform_id=PARAM&query=PARAM&coin_id=PARAM"
    );
  }
}