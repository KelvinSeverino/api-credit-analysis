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
            $table->string('name');
            $table->string('cpf');
            $table->boolean('negative')->default(0);
            $table->float('salary', 10, 2);
            $table->float('limit_card', 10, 2);
            $table->float('rent_value', 10, 2);            
            $table->string('street', 129);
            $table->integer('street_number');
            $table->string('county', 75);
            $table->string('state', 2);
            $table->string('cep', 9);         
            $table->integer('final_score');
            $table->string('result', 20);
            $table->timestamps();
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
