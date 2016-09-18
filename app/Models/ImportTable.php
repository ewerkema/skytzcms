<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ImportTable extends Model
{
    public function check()
    {
        return config('skytz.old_cms') !== false;
    }

    public function import($table, $importFunc)
    {
        if ($this->check()) {
            $data = DB::table($table)->get();
            $data->each($importFunc);
            Schema::drop($table);
        }
    }

    public function reverseImport($table, $foreachFunc)
    {
        if ($this->check()) {
            $data = DB::table($table)->get();
            $count = count($data);

            if ($count == 1) {
                $foreachFunc($data->first());
            } else {
                echo "Couldn't import table $table, because no rows or more than one row exists.";
            }

            Schema::drop($table);
        }
    }
}
