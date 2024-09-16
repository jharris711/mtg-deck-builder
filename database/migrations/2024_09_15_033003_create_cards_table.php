<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('cards', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('lang', 3);
            $table->string('layout');
            $table->uuid('oracle_id')->nullable();

            // Arena and MTGO specific fields
            $table->integer('arena_id')->nullable();

            // Gameplay Fields
            $table->decimal('cmc', 8, 2);
            $table->json('color_identity');
            $table->json('colors')->nullable();
            $table->string('mana_cost')->nullable();
            $table->text('oracle_text')->nullable();
            $table->string('power')->nullable();
            $table->string('toughness')->nullable();
            $table->string('type_line');
            $table->json('keywords');
            $table->json('legalities');

            // Print Fields
            $table->string('set');
            $table->string('set_name');
            $table->string('collector_number');
            $table->string('rarity');
            $table->boolean('digital');
            $table->json('image_uris')->nullable();

            // Arena and MTGO relevant fields
            $table->boolean('booster')->default(false);
            $table->json('games');
            $table->boolean('promo')->default(false);
            $table->json('prices');
            $table->boolean('reprint')->default(false);
            $table->date('released_at');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('cards');
    }
};
