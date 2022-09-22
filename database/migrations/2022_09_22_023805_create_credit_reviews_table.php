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
        Schema::create('credit_reviews', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id');
            $table->boolean('negative');
            $table->float('salary', 10, 2);
            $table->float('limit_card', 10, 2);
            $table->float('rent_value', 10, 2);
            $table->boolean('disapproved90');            
            $table->integer('final_score');
            $table->string('result', 1);
            $table->timestamps();
            
            $table->foreign('client_id')->references('id')->on('Clients');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('credit_reviews');
    }
};
