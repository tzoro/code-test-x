<?php

namespace App\Tests;

use App\Service\CurlGet;
use PHPUnit\Framework\TestCase;
use App\Service\PriceDataFetcher;

class PriceDataFetcherTest extends TestCase
{
    public function testFetchFlow(): void
    {
        $fetcher = new PriceDataFetcher('key');
        $curlGet = $this->createMock(CurlGet::class);
        $curlGet->expects($this->once())->method('getData');

        $out = $fetcher->getHistoricalPrices('ABC', $curlGet);
        
        $this->assertEquals([], $out);
    }

    public function testFetchData(): void
    {
        $data = [];
        $res = new \stdClass();
        $res->prices = [];

        $p1 = new \stdClass();
        $p1->date = 11;
        $p1->open = 22;
        $p1->close = 33;

        $p2 = new \stdClass();
        $p2->date = 44;
        $p2->open = 55;
        $p2->close = 66;

        array_push($data, $p1);
        array_push($data, $p2);
        array_push($res->prices, $data);

        $fetcher = new PriceDataFetcher('key');
        $curlGet = $this->getMockBuilder(CurlGet::class)->getMock();
        $curlGet->expects($this->once())->method('getData')->will($this->returnValue(json_encode($res)));
        $out = $fetcher->getHistoricalPrices('ABC', $curlGet);

        $this->assertEquals($data, $out[0]);
    }
}
