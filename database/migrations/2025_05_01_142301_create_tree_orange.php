<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orange_orange_tree', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('orange_tree_id');
            $table->unsignedBigInteger('orange_id');
            $table->timestamps();

            // Foreign keys
            $table->foreign('orange_tree_id')->references('id')->on('orange_trees')->onDelete('cascade');
            $table->foreign('orange_id')->references('id')->on('oranges')->onDelete('cascade');

            // Unique constraint to prevent duplicate entries
            $table->unique(['orange_id', 'orange_tree_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orange_orange_tree');
    }
};