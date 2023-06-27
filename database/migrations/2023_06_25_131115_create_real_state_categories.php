<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('real_state_categories', function (Blueprint $table) {

            $table->bigInteger('real_state_id')->unsigned();
            $table->foreign('real_state_id')->references('id')->on('real_state');

            $table->bigInteger('category_id')->unsigned();
            $table->foreign('category_id')->references('id')->on('categories');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('real_state_categories');
    }
};
