<?php

namespace App\Models\Concerns;

use App\Services\CurrentDemo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait BelongsToDemo
{
    protected static function bootBelongsToDemo(): void
    {
        static::addGlobalScope('demo', function (Builder $builder): void {
            $builder->where($builder->getModel()->qualifyColumn('demo_slug'), app(CurrentDemo::class)->slug());
        });

        static::creating(function (Model $model): void {
            $model->setAttribute('demo_slug', app(CurrentDemo::class)->slug());
        });
    }
}
