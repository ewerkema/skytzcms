<?php

use App\Models\Page;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug');
            $table->string('title');
            $table->string('meta_title');
            $table->text('meta_desc');
            $table->text('meta_keywords');
            $table->boolean('menu')->default(0);
            $table->integer('parent_id')->unsigned()->nullable();
            $table->integer('order')->default(0);
            $table->integer('header_image_id')->unsigned()->nullable();
            $table->foreign('header_image_id')->references('id')->on('media');
            $table->integer('pagehits');
            $table->softDeletes();
            $table->timestamps();
        });

        ImportTable::import('skytz_pages', function ($page) {
            $newPage = Page::create([
                'slug' => $page->serverpath,
                'title' => $page->pagetitle,
                'meta_title' => $page->metatitle,
                'meta_desc' => $page->metadescr,
                'menu' => $page->menuitem,
                'parent_id' => $page->subitem,
                'order' => is_null($page->listorder) ? 0 : $page->listorder,
                'pagehits' => $page->pagehits,
            ]);

            $date = DateTime::createFromFormat('d-m-Y - H:i:s', $page->created);
            if ($date != null) {
                $newPage->setUpdatedAt($date->format('Y-m-d H:i:s'));
                $newPage->setCreatedAt($date->format('Y-m-d H:i:s'));
                $newPage->save();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::drop('pages');
    }

}
