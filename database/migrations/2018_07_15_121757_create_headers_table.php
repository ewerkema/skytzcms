<?php

use App\Models\Header;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('headers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('position')->default(0);
            $table->integer('image_id')->nullable();
            $table->integer('slider_id')->nullable();
            $table->string('video')->nullable();
            $table->text('content')->nullable();
            $table->integer('link_to_page')->nullable();
            $table->string('link_to_url')->nullable();
            $table->boolean('open_in_new_tab')->nullable();
            $table->timestamps();
        });

        $this->migratePageHeaders();
        $this->migrateSettingsHeader();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->rollbackPageHeaders();
        $this->rollbackSettingsHeader();
        Schema::drop('headers');
    }

    /**
     * Migrate all page headers into header models.
     */
    private function migratePageHeaders()
    {
        $pages = Page::all();
        foreach ($pages as $page) {
            if ($page->header_image_id != null) {
                $header = $this->createHeaderFromImage("Header ".$page->title, $page->header_image_id);
                $page->header_image_id = $header->id;
                $page->save();
            }
        }

        Schema::table('pages', function (Blueprint $table) {
            $table->renameColumn('header_image_id', 'header_id');
        });
    }

    /**
     * Create header from image ID
     *
     * @param $name
     * @param $header_image_id
     * @return Header
     */
    private function createHeaderFromImage($name, $header_image_id)
    {
        return Header::create([
            'name' => $name,
            'image_id' => $header_image_id,
        ]);
    }

    /**
     * Rollback all page headers into the page model.
     */
    private function rollbackPageHeaders()
    {
        $pages = Page::all();
        foreach ($pages as $page) {
            if (count($page->header)) {
                $page->header_id = $page->header()->first()->image_id;
                $page->save();
            }
        }

        Schema::table('pages', function (Blueprint $table) {
            $table->renameColumn('header_id', 'header_image_id');
        });
    }

    /**
     * Migrate the header that is stored in settings.
     */
    private function migrateSettingsHeader()
    {
        $header = Header::create([
            'name' => 'Algemene website header',
            'image_id' => Setting::get('header_image'),
            'slider_id' => Setting::get('header_slider'),
        ]);

        Setting::create([
            'name' => 'header_id',
            'value' => $header->id,
        ]);
    }

    private function rollbackSettingsHeader()
    {
        Setting::deleteMany(array('header_id'));
    }
}
