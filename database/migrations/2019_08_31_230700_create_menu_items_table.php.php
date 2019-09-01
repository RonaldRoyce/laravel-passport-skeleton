<?php

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
            $table->bigIncrements('menu_item_id')->unsigned();
            $table->bigInteger('menu_item_parent_id')->unsigned();
            $table->char('menu_item_type', 1);
            $table->tinyInteger('level_order')->unsigned();
            $table->string('page_id', 100)-default('');
            $table->string('div_anchor_name', 100);
            $table->string('menu_item_text', 200);
            $table->striing('anchor_url', 50);
            $table->string('image_class', 100);

            $table->foreign('menu_item_parent_id')->references('menu_item_id')->on('menu_items');
        });
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
}
