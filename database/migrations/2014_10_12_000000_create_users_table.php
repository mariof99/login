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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('rol')->default('user');
            // $table->boolean('activo')->defautl(); // esto no sé muy bien cómo hacerlo (poner a false activado por defecto)
            $table->integer('sabiduria')->nullable();
            $table->integer('nobleza')->nullable();
            $table->integer('virtud')->nullable();
            $table->integer('maldad')->nullable();
            $table->integer('audacia')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
