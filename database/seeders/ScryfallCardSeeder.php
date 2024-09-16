<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ScryfallCardSeeder extends Seeder {
    public function run() {
        ini_set('memory_limit', '1024M');
        $filePath = Storage::disk('local')->path('oracle-cards.json');

        DB::disableQueryLog();
        Schema::disableForeignKeyConstraints();

        $batchSize = 1000;
        $batch = [];
        $count = 0;
        $errorCount = 0;

        $handle = fopen($filePath, 'r');

        // Read the opening bracket
        fread($handle, 1);

        while (!feof($handle)) {
            $chunk = '';
            $openBraces = 0;

            do {
                $char = fgetc($handle);
                if ($char === '{') $openBraces++;
                if ($char === '}') $openBraces--;
                $chunk .= $char;
            } while ($openBraces > 0 && !feof($handle));

            // Remove trailing comma if present
            $chunk = rtrim($chunk, ",");

            if (trim($chunk) !== '') {
                $card = json_decode($chunk, true);
                if (is_array($card)) {
                    try {
                        $cardData = $this->extractCardData($card);
                        $batch[] = $cardData;
                        $count++;

                        if (count($batch) >= $batchSize) {
                            $this->insertBatch($batch);
                            $batch = [];
                            $this->command->info("Processed $count cards");
                        }
                    } catch (\Exception $e) {
                        $errorCount++;
                        $this->command->error("Error processing card: " . $e->getMessage());
                        Log::error("Error processing card: " . $e->getMessage(), ['card' => $card]);
                    }
                } else {
                    $errorCount++;
                    $this->command->error("Failed to decode JSON for a card");
                    Log::error("Failed to decode JSON for a card", ['chunk' => $chunk]);
                }
            }
        }

        fclose($handle);

        // Insert any remaining cards
        if (!empty($batch)) {
            $this->insertBatch($batch);
        }

        Schema::enableForeignKeyConstraints();

        $this->command->info("Seeding completed. Total cards processed: $count");
        $this->command->info("Total errors encountered: $errorCount");
    }

    private function extractCardData($card) {
        return [
            'id' => $card['id'],
            'name' => $card['name'],
            'lang' => $card['lang'],
            'layout' => $card['layout'],
            'oracle_id' => $card['oracle_id'] ?? null,
            'arena_id' => $card['arena_id'] ?? null,
            'cmc' => $card['cmc'],
            'color_identity' => json_encode($card['color_identity']),
            'colors' => json_encode($card['colors'] ?? null),
            'mana_cost' => $card['mana_cost'] ?? null,
            'oracle_text' => $card['oracle_text'] ?? null,
            'power' => $card['power'] ?? null,
            'toughness' => $card['toughness'] ?? null,
            'type_line' => $card['type_line'],
            'keywords' => json_encode($card['keywords'] ?? []),
            'legalities' => json_encode($card['legalities']),
            'set' => $card['set'],
            'set_name' => $card['set_name'],
            'collector_number' => $card['collector_number'],
            'rarity' => $card['rarity'],
            'digital' => $card['digital'],
            'image_uris' => json_encode($card['image_uris'] ?? null),
            'booster' => $card['booster'] ?? false,
            'games' => json_encode($card['games'] ?? []),
            'promo' => $card['promo'] ?? false,
            'prices' => json_encode($card['prices'] ?? null),
            'reprint' => $card['reprint'] ?? false,
            'released_at' => $card['released_at'],
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    private function insertBatch($batch) {
        try {
            DB::table('cards')->insert($batch);
        } catch (\Exception $e) {
            $this->command->error("Error inserting batch: " . $e->getMessage());
            Log::error("Error inserting batch: " . $e->getMessage(), ['batch' => $batch]);
            // You might want to handle the error more gracefully here
        }
    }
}
