<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('decks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('format', ['standard', 'historic', 'brawl', 'alchemy', 'explorer', 'pauper', 'other'])->default('other');
            $table->boolean('is_public')->default(false);
            $table->json('color_identity');
            $table->integer('card_count')->default(0);
            $table->json('card_counts'); // Stores counts for different card types
            $table->enum('game', ['arena', 'mtgo'])->default('arena');
            $table->timestamps();
        });

        Schema::create('deck_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deck_id')->constrained()->onDelete('cascade');
            $table->uuid('card_id');
            $table->foreign('card_id')->references('id')->on('cards')->onDelete('cascade');
            $table->integer('quantity');
            $table->enum('location', ['main', 'sideboard', 'commander'])->default('main');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('deck_cards');
        Schema::dropIfExists('decks');
    }
};
