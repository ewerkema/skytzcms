<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class ExcelTableImport implements WithCalculatedFormulas
{
    use Importable;

    /**
     * @param Collection $collection
     *
     * @return Collection
     */
    public function collection(Collection $collection)
    {
        return $collection;
    }
}
