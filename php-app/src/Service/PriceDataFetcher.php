<?php

namespace App\Service;

class PriceDataFetcher
{
    public function __construct(private string $env)
    {
        $this->apiKey = $env;
    }

    public function getHistoricalPrices(string $company, CurlGet $curlGet): Array
    {
        $url = "https://yh-finance.p.rapidapi.com/stock/v3/get-historical-data?symbol=" . $company . "&region=US";

        $headers = [
            "X-RapidAPI-Host: yh-finance.p.rapidapi.com",
            "X-RapidAPI-Key: " . $this->apiKey
        ];

        $response = $curlGet->getData($url, $headers);

        return json_decode($response)->prices;
    }
}