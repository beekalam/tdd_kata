<?php

namespace Tests\Unit;

use App\CSVConverter\CSVtoXML;
use PHPUnit\Framework\TestCase;

class CSVtoXMLTest extends TestCase
{

    /** @test */
    function can_create_xml_elements()
    {
        $xmlString = $this->buildXMLString();
        $this->assertStringContainsString("<firstname>john</firstname>",$xmlString);
        $this->assertStringContainsString("<lastname>doe</lastname>",$xmlString);
        $this->assertStringContainsString("<age>18</age>",$xmlString);
        $this->assertStringContainsString("<job>programmer</job>",$xmlString);
        $this->assertStringContainsString("<favorite_programming_language>php</favorite_programming_language>",$xmlString);
    }

    /** @test */
    function can_create_xml_parent_element()
    {
        $xmlString = $this->buildXMLString();
        $this->assertStringContainsString("<person>",$xmlString);
        $this->assertStringContainsString("</person>",$xmlString);
    }

    private function csvString()
    {
        $csv = <<<CSV
john,doe,18,programmer,php
john,smith,25,developer,python
CSV;
        return $csv;

    }

    /**
     * @return string
     */
    private function buildXMLString()
    {
        $csvtoxml = new CSVtoXML($this->csvString(), ["person", [
            "firstname",
            "lastname",
            "age",
            "job",
            "favorite_programming_language"
        ]]);
        $xmlString = $csvtoxml->toXML();
        return $xmlString;
    }

}