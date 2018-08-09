<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'value',
    ];

    /**
     * Custom getter for only the key value pairs
     *
     * @param string $name
     * @return mixed
     */
    public function get($name)
    {
        $setting = $this->where('name', $name)->first();

        return $setting != null ? $setting->value : false;
    }

    /**
     * Create many settings by array[name => value, ..].
     *
     * @param array $settings
     */
    public function createMany($settings)
    {
        foreach ($settings as $name => $value) {
            Setting::create([
                'name' => $name,
                'value' => $value,
            ]);
        }
    }

    /**
     * Delete many settings by array [name => value, ..].
     *
     * @param array $settings
     */
    public function deleteMany($settings)
    {
        foreach ($settings as $name => $value) {
            if (is_numeric($name))
                $name = $value;

            $setting = Setting::where('name', '=', $name)->first();

            if ($setting != null)
                $setting->delete();
        }
    }
}
