<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Project extends Model
{
    use HasFactory;

//    TODO: do something with relationship with user. Owner, viewer etc

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function projectUsers(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function criteria(): HasManyThrough
    {
        return $this->hasManyThrough(Criterion::class, Dataset::class);
    }

    public function electreOnes(): HasMany
    {
        return $this->hasMany(ElectreOne::class);
    }

    public function dataset(): HasOne
    {
        return $this->hasOne(Dataset::class);
    }
}
