<?php

namespace App\Services;

use App\Imports\ExcelTableImport;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;

class TableGeneratorService
{
    /**
     * @param UploadedFile $file
     *
     * @return string
     */
    public function generateTable(UploadedFile $file)
    {
        $sheets = Excel::toCollection(new ExcelTableImport, $file);

        return $this->generateTableFromCollection($sheets->first());
    }

    private function generateTableFromCollection(Collection $collection)
    {
        $tableHtml = "<table>";
        $tableHtml .= $collection->reduce(function ($carry, $row) {
            return $carry . $this->generateRow($row);
        }, '');
        $tableHtml .= "</table>";
        return $tableHtml;
    }

    private function generateRow(Collection $row)
    {
        $rowHtml = "<tr>";
        $rowHtml .= $row->reduce(function ($carry, $column) {
            return $carry . $this->generateColumn($column);
        }, '');
        $rowHtml .= "</tr>";
        return $rowHtml;
    }

    private function generateColumn($column)
    {
        return "<td>$column</td>";
    }
}