<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Service\GraphDataProcessor;

class GraphDataProcessorTest extends TestCase
{
    public function testGetGraphDataStructure(): void
    {
        $processor = new GraphDataProcessor();
        $out = $processor->getGraphData([]);

        $this->assertInstanceOf("stdClass", $out);
        $this->assertObjectHasAttribute('open', $out);
        $this->assertObjectHasAttribute('close', $out);
    }

    public function testGetGraphDataWithValidData(): void
    {
        $data = [];
        $processor = new GraphDataProcessor();

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

        $out = $processor->getGraphData($data);

        $validOutput = new \stdClass();
        $validOutput->open = [[11,22],[44,55]];
        $validOutput->close = [[11,33],[44,66]];

        $this->assertEquals($validOutput, $out);
    }
}
