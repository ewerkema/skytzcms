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
            $table->text('published_content');
            $table->string('meta_title');
            $table->text('meta_desc')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->boolean('menu')->default(0);
            $table->integer('parent_id')->unsigned()->nullable();
            $table->integer('order')->default(0);
            $table->integer('header_image_id')->unsigned()->nullable();
            $table->foreign('header_image_id')->references('id')->on('media');
            $table->integer('pagehits')->default(0);
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

            $importedContent = $this->importContentFromBlocks($page);
            $newPage->content = $importedContent;
            $newPage->published_content = $importedContent;
            $newPage->save();

            $date = DateTime::createFromFormat('d-m-Y - H:i:s', $page->created);
            if ($date != null) {
                $newPage->setUpdatedAt($date->format('Y-m-d H:i:s'));
                $newPage->setCreatedAt($date->format('Y-m-d H:i:s'));
                $newPage->save();
            }
        });

        if (ImportTable::check()) {
            Schema::drop('skytz_blocks');
            Schema::drop('skytz_strokes');
        }

        if (!Page::all()->count())
            $this->generateHomePage();
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

            $content[] = array(
                "name" => "block".$i,
                "x" => $col,
                "y" => $row,
                "width" => $colWidth*$rowWidth/12,
                "height" => 1,
                "content" => $block->blockcontent,
                "module" => $block->module,
                "module_id" => $block->module_id
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

    /**
     * Generates a default home page.
     */
    public function generateHomePage()
    {
        Page::create([
            'slug' => 'index',
            'title' => 'Beginpagina',
            'content' => array(array(
                'module' => '0',
                'x' => '0',
                'y' => '0',
                'width' => '12',
                'height' => '1',
                'name' => 'block0',
                'content' => '<h1>Beginpagina</h1><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat turpis elit, et lobortis neque hendrerit id. Vestibulum viverra libero vel volutpat interdum. Cras vitae lobortis sem. Mauris nec eros ex. Nulla nec nunc sit amet lacus lobortis venenatis in sed risus. Sed dignissim aliquet tellus, nec placerat nisl efficitur sit amet. Aliquam erat volutpat. Nam a aliquam tellus, nec eleifend felis. Phasellus rutrum rhoncus lorem quis efficitur. Aliquam eu faucibus leo. Interdum et malesuada fames ac ante ipsum primis in faucibus. Nulla nisl ipsum, facilisis id molestie quis, dapibus eget diam. Maecenas ante dolor, eleifend quis tortor ut, blandit molestie justo.</p>',
            )),
            'published_content' => array(array(
                'module' => '0',
                'x' => '0',
                'y' => '0',
                'width' => '12',
                'height' => '1',
                'name' => 'block0',
                'content' => '<h1>Beginpagina</h1><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat turpis elit, et lobortis neque hendrerit id. Vestibulum viverra libero vel volutpat interdum. Cras vitae lobortis sem. Mauris nec eros ex. Nulla nec nunc sit amet lacus lobortis venenatis in sed risus. Sed dignissim aliquet tellus, nec placerat nisl efficitur sit amet. Aliquam erat volutpat. Nam a aliquam tellus, nec eleifend felis. Phasellus rutrum rhoncus lorem quis efficitur. Aliquam eu faucibus leo. Interdum et malesuada fames ac ante ipsum primis in faucibus. Nulla nisl ipsum, facilisis id molestie quis, dapibus eget diam. Maecenas ante dolor, eleifend quis tortor ut, blandit molestie justo.</p>',
            )),
            'meta_title' => 'Beginpagina',
            'meta_desc' => 'Eerste pagina van de website',
            'menu' => 1,
            'order' => 0,
            'pagehits' => 0
        ]);
    }

}
