<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Animal extends Model
{
    /** @use HasFactory<\Database\Factories\AnimalFactory> */
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'species',
        'is_predator',
        'born_at',
        'enclosure_id',
        'filename',
        'filename_hash',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_predator' => 'boolean',
        ];
    }

    /**
     * Get the enclosure that owns the Animal
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function enclosure(): BelongsTo
    {
        return $this->belongsTo(Enclosure::class);
    }
}
