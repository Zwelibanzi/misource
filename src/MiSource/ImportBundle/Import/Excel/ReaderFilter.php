<?php
namespace MiSource\ImportBundle\Import\Excel;
use PHPExcel_Reader_IReadFilter;

class ReaderFilter implements PHPExcel_Reader_IReadFilter
{
    public function readCell($column, $row, $worksheetName = '') {

        //  Read rows 1 to 7 and columns A to E only
        if (in_array($column, ['A', 'B', 'C', 'D'])) {
             return true;
        }
        return false;
    }
}