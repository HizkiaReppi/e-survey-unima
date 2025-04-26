<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Faculty extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'name',
        'short_name',
    ];

    public function departments(): HasMany
    {
        return $this->hasMany(Department::class, 'faculty_id');
    }
}