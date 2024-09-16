<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model {
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'lang',
        'layout',
        'oracle_id',
        'arena_id',
        'cmc',
        'color_identity',
        'colors',
        'mana_cost',
        'oracle_text',
        'power',
        'toughness',
        'type_line',
        'keywords',
        'legalities',
        'set',
        'set_name',
        'collector_number',
        'rarity',
        'digital',
        'image_uris',
        'booster',
        'games',
        'promo',
        'prices',
        'reprint',
        'released_at'
    ];

    protected $casts = [
        'color_identity' => 'array',
        'colors' => 'array',
        'keywords' => 'array',
        'legalities' => 'array',
        'image_uris' => 'array',
        'games' => 'array',
        'prices' => 'array',
        'digital' => 'boolean',
        'booster' => 'boolean',
        'promo' => 'boolean',
        'reprint' => 'boolean',
        'released_at' => 'date',
    ];

    public function decks(): BelongsToMany {
        return $this->belongsToMany(Deck::class, 'deck_cards')
            ->withPivot('quantity', 'location')
            ->withTimestamps();
    }
}
