<?php

namespace App\Service;

class GraphDataProcessor
{
    public function getGraphData(Array $prices): \stdClass
    {
        $graphData = new \stdClass();
        $graphData->open = [];
        $graphData->close = [];

        foreach ($prices as $key => $value) {
            if( isset($value->open) && isset($value->close) ) {
                array_push($graphData->open, [$value->date, $value->open]);
                array_push($graphData->close, [$value->date, $value->close]);
            }
        }

        return $graphData;
    }
}