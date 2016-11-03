<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Slider;
use App\Models\Media;

class CreateRelationBetweenMediaSlider extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_slider', function (Blueprint $table) {
            $table->integer('media_id')->unsigned();
            $table->foreign('media_id')->references('id')->on('media');
            $table->integer('slider_id')->unsigned();
            $table->foreign('slider_id')->references('id')->on('sliders');
            $table->unique(array('media_id', 'slider_id'));
        });

        ImportTable::import('skytz_slider', function ($image) {
            $slider = Slider::all()->first();

            $sliderImage = Media::createFromFile($image->imagepath, config('skytz.upload_slider_images'));

            if ($sliderImage != null)
                $slider->media()->save($sliderImage);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('media_slider');
    }
}
