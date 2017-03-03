<?php

namespace MiSource\ImportBundle\Import;

use MiSource\ImportBundle\Import\Excel\ReaderFilter;
use PHPExcel_IOFactory;

class Parse
{

    private $serviceContainer = null;

    private $_records;

    private $_fields;

    /**
     * @return mixed
     */
    public function getRecords()
    {
        return $this->_records;
    }

    /**
     * @param mixed $records
     */
    public function setRecords($records)
    {
        $this->_records = $records;
    }

    /**
     * @return mixed
     */
    public function getFields()
    {
        return $this->_fields;
    }

    /**
     * @param mixed $fields
     */
    public function setFields($fields)
    {
        $this->_fields = $fields;
    }


    public function setContainer($serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;

        return $this;
    }

    public function consume($fileName = null)
    {
        $fields = '';
        $records = '';
        $phpExcel = $this->loadFileIntoReader($fileName);

         foreach ($phpExcel->getWorksheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $row)  {

                foreach ($row->getCellIterator() as $cell) {

                   if (1 === $cell->getRow())  {
                       $fields .=  $cell->getValue() . ',';
                   } else {
                       $records .=  $cell->getValue() . ',';
                   }
                }

                $records = trim($records, ',');
                $records .= PHP_EOL;
            }

            $fields = trim($fields, ',');
         }

        $this->setFields($fields);
        return $this->makeArrayRecords($fields, $records, true);
    }

    private function loadFileIntoReader($fileName)
    {
        //TODO handle file missing exception
        $inputFileName =  __DIR__ . '/' . $fileName;
        $filter = new ReaderFilter();

        $type = PHPExcel_IOFactory::identify($inputFileName);
        $reader = PHPExcel_IOFactory::createReader($type);
        $reader->setReadDataOnly(true);
        $reader->setReadFilter($filter);
        $phpExcel = $reader->load($inputFileName);

        return $phpExcel;
    }

    private function makeArrayRecords($fields, $records) {

        $return = [];
        $line = [];
        $fields = explode(',', $fields);
        $records = $this->explodeRecords($records);
        $this->setRecords($records);

        foreach ($records as $record) {

            $singleRecord = explode(',', trim($record));
            for($x = 0; $x < count($singleRecord); $x++) {
                 $line[$fields[$x]] = $singleRecord[$x];
            }
            $return[] = $line;
        }

        return  $return;
    }

    private function explodeRecords($records)
    {
        return explode(PHP_EOL, trim($records));
    }
}