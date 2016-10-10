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
            $table->text('content');
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

            $newPage->content = $this->importContentFromBlocks($page);
            $newPage->save();

            $date = DateTime::createFromFormat('d-m-Y - H:i:s', $page->created);
            if ($date != null) {
                $newPage->setUpdatedAt($date->format('Y-m-d H:i:s'));
                $newPage->setCreatedAt($date->format('Y-m-d H:i:s'));
                $newPage->save();
            }
        });

        Schema::drop('skytz_blocks');
        Schema::drop('skytz_strokes');
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

    /**
     * Returns the page content as an array of blocks.
     *
     * @param $page : Imported page
     * @return array
     */
    public function importContentFromBlocks($page)
    {
        $blocks = DB::table('skytz_blocks')
            ->where(['skytz_blocks.pageid' => $page->id, 'visible' => '0'])
            ->orderBy('listorder', 'asc')
            ->join('skytz_strokes', 'skytz_blocks.stroke', '=', 'skytz_strokes.id')
            ->get();

        $content = array();
        $row = 0;
        $col = 0;
        $i = 0;
        foreach ($blocks as $block) {
            $rowWidth = intval(filter_var($block->widths, FILTER_SANITIZE_NUMBER_INT));
            $rowWidth = ($rowWidth<0) ? -$rowWidth : $rowWidth;

            $colWidth = intval(filter_var($block->blockwidth, FILTER_SANITIZE_NUMBER_INT));
            $colWidth = ($colWidth<0) ? -$colWidth : $colWidth;

            $content['col'.$i] = array(
                "offset" => $col,
                "row" => $row,
                "width" => $colWidth*$rowWidth/12,
                "height" => 1,
                "content" => $block->blockcontent,
                "module" => $block->module_id
            );

            $changeRow = floor (($col + $colWidth) / 12);
            if ($changeRow) {
                $row++;
                $col = 0;
            } else {
                $col += $colWidth;
            }

            $i++;
        }

        return $content;
    }

}
