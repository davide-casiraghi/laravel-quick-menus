<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuItemsTable extends Migration
{
    public function up()
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('menu_id')->nullable();
            $table->string('parent_item_id')->nullable();
            $table->integer('type')->nullable();
            $table->string('url')->nullable();
            $table->string('route')->nullable();
            $table->string('font_awesome_class')->nullable();
            $table->boolean('hide_name')->default('0');
            $table->integer('order')->nullable();
            $table->integer('access')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('menu_items');
    }
}
