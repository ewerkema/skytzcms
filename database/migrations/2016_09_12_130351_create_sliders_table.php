<?php

use App\Models\Media;
use App\Models\Slider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSlidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sliders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        $slider = false;
        if (ImportTable::check()) {
            $slider = Slider::create([
                'name' => 'Main Slider'
            ]);
        }

        ImportTable::import('skytz_slider', function ($image) use ($slider) {
            $sliderImage = Media::createFromFile($image->imagepath, config('skytz.upload_slider_images'));
            if ($sliderImage)
                $sliderImage->setSlider($slider);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sliders');
    }
}
