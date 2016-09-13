<?php

use App\Models\Media;
use App\Slider;
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

        if (config('skytz.old_cms'))
            $this->importSliderImages();
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

    /**
     * Import existing images from old table.
     *
     * @return void
     */
    public function importSliderImages()
    {
        $images = DB::table('skytz_slider')->get();

        $slider = Slider::create([
            'name' => 'Main Slider'
        ]);

        $images->each(function($image) use ($slider) {
            $sliderImage = Media::createFromFile($image->imagepath, config('skytz.upload_slider_images'));
            if ($sliderImage)
                $sliderImage->setSlider($slider);
        });

        Schema::drop('skytz_slider');
    }
}
