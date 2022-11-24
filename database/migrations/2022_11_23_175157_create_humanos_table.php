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
        Schema::create('humanos', function (Blueprint $table) {
            $table->unsignedBigInteger('idHumano')->primary()->references('id')->on('users')->delete('cascade'); // para el delete cascade
            $table->unsignedBigInteger('idDios')->nullable();
            $table->string('lugarDeMuerte')->nullable();
            $table->integer('destino');
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
        Schema::dropIfExists('humanos');
    }
};
