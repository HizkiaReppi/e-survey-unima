<?php

namespace App\Models;

use App\Enums\AcademicRank;
use App\Enums\CertificationStatus;
use App\Enums\FunctionalPosition;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lecturer extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fullname',
        'department_id',
        'functional_position',
        'academic_rank',
        'employee_status',
        'certification_status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'functional_position' => FunctionalPosition::class,
            'academic_rank' => AcademicRank::class,
            'certification_status' => CertificationStatus::class,
        ];
    }

    /**
     * Get the department that owns the lecturer.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
