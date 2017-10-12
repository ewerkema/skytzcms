<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePagesIncreasePageContentSize extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE pages MODIFY content MEDIUMTEXT;');
        DB::statement('ALTER TABLE pages MODIFY published_content MEDIUMTEXT;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE pages MODIFY content TEXT;');
        DB::statement('ALTER TABLE pages MODIFY published_content TEXT;');
    }
}
