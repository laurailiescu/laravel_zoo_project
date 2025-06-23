<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Enclosure extends Model
{
    /** @use HasFactory<\Database\Factories\EnclosureFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'limit',
        'feeding_at',
    ];

    /**
     * The users that belong to the Enclosure
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    /**
     * Get all of the animals for the Enclosure
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function animals(): HasMany
    {
        return $this->hasMany(Animal::class);
    }
}
