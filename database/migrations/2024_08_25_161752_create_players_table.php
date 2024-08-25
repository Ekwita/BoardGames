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
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->string('player_name')->unique();
            $table->integer('games')->default('0');
            $table->integer('wins')->default('0');
            $table->integer('deaths')->default('0');
            $table->integer('best')->default('0');
            $table->integer('average')->default('0');
            $table->integer('totalgold')->default('0');
            $table->integer('art5')->default('0');
            $table->integer('art7')->default('0');
            $table->integer('art10')->default('0');
            $table->integer('art12')->default('0');
            $table->integer('art15')->default('0');
            $table->integer('art17')->default('0');
            $table->integer('art20')->default('0');
            $table->integer('art25')->default('0');
            $table->integer('art30')->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
