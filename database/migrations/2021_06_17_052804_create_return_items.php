<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturnItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('return_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id');
            $table->foreignId('doc_id');
            $table->integer('amount');
            $table->text('description')->nullable();
            $table->timestamp('return_date');
            $table->boolean('is_done')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('doc_id')->references('id')->on('product_changes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('return_items');
    }
}
