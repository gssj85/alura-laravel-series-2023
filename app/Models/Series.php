<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Series extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'cover'];

    protected $appends = ['links'];

    public function seasons()
    {
        return $this->hasMany(Season::class, 'series_id');
    }

    public function episodes()
    {
        return $this->hasManyThrough(Episode::class, Season::class);
    }

    /**
     * Perform any actions required after the model boots.
     *
     * @return void
     */
    protected static function booted(): void
    {
        self::addGlobalScope('ordered', static function (Builder $queryBuilder) {
            $queryBuilder->orderBy('nome', 'asc');
        });
    }

    public function links(): Attribute
    {
        return new Attribute(
            get: fn() => [
                [
                    'rel' => 'self',
                    'url' => "/api/series/$this->id"
                ],
                [
                    'rel' => 'seasons',
                    'url' => "/api/series/$this->id/seasons"
                ],
                [
                    'rel' => 'self',
                    'url' => "/api/series/$this->id/episodes"
                ]
            ]
        );
    }
}
