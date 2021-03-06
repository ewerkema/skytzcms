<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{

    /**
     * Update the settings in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $input = $request->all();

        foreach ($input as $name => $value) {
            $update = Setting::where('name', '=', $name)->first();
            if ($update) {
                $update->value = $value;
                $update->save();
            }
        }

        return response()->json($input);
    }
}
