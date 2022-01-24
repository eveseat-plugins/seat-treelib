<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use RecursiveTree\Seat\InfoPlugin\Model\Resource;

class ArticleAcl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recursive_tree_seat_info_acl_map', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('acl_provider');
        });

        Schema::create('recursive_tree_seat_info_role_acl', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger("acl");
            $table->string('acl_provider');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('recursive_tree_seat_info_article_acl');
    }
}

