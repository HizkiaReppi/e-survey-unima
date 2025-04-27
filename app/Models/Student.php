<?php

namespace App\Models;

use App\Helpers\StudentHelper;
use App\Helpers\TextFormattingHelper;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['user_id', 'nim', 'batch', 'phone_number', 'address', 'department_id'];

    /**
     * Get the user that owns the student.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the department that owns the student.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the fullname of student.
     */
    public function getFullnameAttribute(): string
    {
        return $this->user->name;
    }

    /**
     * Format NIM.
     */
    public function getFormattedNIMAttribute(): string
    {
        return TextFormattingHelper::formatNIM($this->nim);
    }

    /**
     * Get Current Semester.
     */
    public function getCurrentSemesterAttribute(): string
    {
        return StudentHelper::getCurrentSemesterStudent($this->batch);
    }
}
