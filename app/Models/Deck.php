<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Deck extends Model {
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'format',
        'is_public',
        'color_identity',
        'card_count',
        'card_counts',
        'game'
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'color_identity' => 'array',
        'card_counts' => 'array',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function cards(): BelongsToMany {
        return $this->belongsToMany(Card::class, 'deck_cards')
            ->withPivot('quantity', 'location')
            ->withTimestamps();
    }

    public function createdBy() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy() {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
