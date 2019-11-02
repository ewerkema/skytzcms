<?php

use App\Models\MenuItem;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->integer('page_id')->nullable();
            $table->integer('parent_id')->unsigned()->nullable();
            $table->integer('order')->default(0);
            $table->string('link')->nullable();
            $table->boolean('open_in_new_tab')->default(false);
            $table->timestamps();
        });

        $this->createMenuFromPages();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_items');
    }

    public function createMenuFromPages()
    {
        $pages = Page::all();
        foreach ($pages as $page) {
            if ($page->menu) {
                MenuItem::create([
                    'page_id' => $page->id,
                    'order' => $page->order,
                    'parent_id' => $page->parent_id,
                ]);
            }
        }
    }
}
