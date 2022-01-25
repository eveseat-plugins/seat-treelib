<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use RecursiveTree\Seat\InfoPlugin\Model\Resource;

class AccessProviderTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recursive_tree_seat_treelib_access_control', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('used_for');
        });

        Schema::create('recursive_tree_seat_treelib_access_provider_map', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger("access_control_id");
            $table->string('provider_class');
        });

        Schema::create('recursive_tree_seat_treelib_role_provider', function (Blueprint $table) {
            $table->bigInteger("provider_id")->unique()->primary();
            $table->string('role');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('recursive_tree_seat_treelib_access_control');
        Schema::drop('recursive_tree_seat_treelib_access_provider_map');
        Schema::drop('recursive_tree_seat_treelib_role_provider');
    }
}

