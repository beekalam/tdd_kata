<?php


namespace App\CSVConverter;


class CSVtoXML
{
    private $csv;
    private $xmlSchema;
    private $padding = 0;

    /**
     * CSVtoXML constructor.
     */
    public function __construct($csv, $xmlSchema)
    {
        $this->csv = $csv;
        $this->xmlSchema = $xmlSchema;
    }

    public function toXML()
    {
        $xml = '';
        $csv_array = explode(PHP_EOL, $this->csv);
        foreach ($csv_array as $csv) {
            $xml .= $this->buildChild($csv);
        }
        return $xml;
    }

    private function buildChild($csv)
    {
        $xml = $this->padString("<" . $this->xmlSchema[0] . ">");
        $xml .= $this->buildElements(str_getcsv($csv));
        $xml .= $this->padString("</" . $this->xmlSchema[0] . ">");
        return $xml;
    }

    private function buildElements($row)
    {
        $this->padding += 2;
        $xml = '';
        $size = count($row);
        for ($i = 0; $i < $size; $i++) {
            $element = $this->xmlSchema[1][$i];
            $xml .= $this->padString("<{$element}>{$row[$i]}</{$element}>");
        }
        $this->padding -= 2;
        return $xml;
    }

    private function padString($str)
    {
        return str_repeat(" ", $this->padding) . $str . PHP_EOL;
    }

}