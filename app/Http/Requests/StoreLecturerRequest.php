<?php

namespace App\Http\Requests;

use App\Enums\AcademicRank;
use App\Enums\CertificationStatus;
use App\Enums\FunctionalPosition;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreLecturerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'fullname' => ['required', 'string', 'max:255', 'min:2', 'regex:/^[a-zA-Z\s.,]*$/'],
            'department_id' => ['required', 'exists:departments,id', 'ulid'],
            'functional_position' => ['nullable', new Enum(FunctionalPosition::class)],
            'academic_rank' => ['nullable', new Enum(AcademicRank::class)],
            'employee_status' => ['nullable', 'string', 'max:50'],
            'certification_status' => ['nullable', new Enum(CertificationStatus::class)],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'department_id' => 'Department ID',
            'functional_position' => 'Functional Position',
            'academic_rank' => 'Academic Rank',
            'employee_status' => 'Employee Status',
            'certification_status' => 'Certification Status',
        ];
    }

    /**
     * Get custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'department_id.required' => 'Program Studi wajib diisi.',
            'department_id.exists' => 'Program Studi tidak ditemukan.',
            'department_id.ulid' => 'Program Studi harus berupa ULID.',
            'functional_position.enum' => 'Posisi fungsional tidak valid.',
            'academic_rank.enum' => 'Pangkat akademik tidak valid.',
            'employee_status.string' => 'Status pegawai harus berupa teks.',
            'employee_status.max' => 'Status pegawai tidak boleh lebih dari 50 karakter.',
            'certification_status.enum' => 'Status sertifikasi tidak valid.',
        ];
    }
}
