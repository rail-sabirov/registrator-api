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
        Schema::create('verification_codes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('code')->length(8);
            $table->timestamps();

            $table->foreign('user_id') // связь изспользуя текущую переменную user_id
                ->references('id') // сопоставляем с полем id в таблице users
                ->on('users') // таблица users
                ->onDelete('cascade'); // при удалении пользователя из табилцы users, удаляются все его коды верификации в текущей таблице
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verification_codes');
    }
};
