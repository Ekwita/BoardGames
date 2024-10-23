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
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained();
            $table->foreignId('player_id')->constrained();
            $table->string('player_name');
            $table->integer('status');
            $table->boolean('art5')->nullable()->default(0);
            $table->boolean('art7')->nullable()->default(0);
            $table->boolean('art10')->nullable()->default(0);
            $table->boolean('art12')->nullable()->default(0);
            $table->boolean('art15')->nullable()->default(0);
            $table->boolean('art17')->nullable()->default(0);
            $table->boolean('art20')->nullable()->default(0);
            $table->boolean('art25')->nullable()->default(0);
            $table->boolean('art30')->nullable()->default(0);
            $table->integer('gold')->nullable()->default(0);
            $table->integer('tokens')->nullable()->default(0);
            $table->integer('cards')->nullable()->default(0);
            $table->integer('total_points')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
