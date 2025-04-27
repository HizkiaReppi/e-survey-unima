<?php

namespace App\Http\Requests;

use App\Models\Department;
use App\Models\Lecturer;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
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
        $isNIMExist = Student::where('nim', $this->nim)->exists();
        $isEmailExist = User::where('email', $this->email)->exists();

        $rules = [
            'fullname' => ['required', 'string', 'max:255', 'min:2', 'regex:/^[a-zA-Z\s]*$/'],
            'batch' => ['required', 'integer', 'digits:4', 'min:1900', 'max:' . (date('Y'))],
            'phone_number' => ['nullable', 'string', 'min:9', 'max:20', 'regex:/^08[0-9]*$/'],
            'address' => ['nullable', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
            'department_id' => ['required', 'exists:' . Department::class . ',id'],
        ];

        if($this->nim && !$isNIMExist) {
            $rules['nim'] = ['required', 'string', 'max:10', 'min:4', 'unique:' . Student::class, 'regex:/^[0-9]*$/'];
        }

        if($this->email && !$isEmailExist) {
            $rules['email'] = ['required', 'string', 'email', 'max:255', 'min:4', 'unique:' . User::class];
        }

        return $rules;
    }

    /**
     * Get the validation messages that apply to the request.
     */
    public function messages(): array
    {
        return [
            'fullname.regex' => 'The nim field must be alphabet.',
            'nim.unique' => 'The nim field must be unique.',
            'nim.regex' => 'The nim field must be number.',
            'phone_number.regex' => 'The nim field must be number telephone.',
            'batch.digits' => 'The angkatan field must be 4 digits.',
            'batch.min' => 'The angkatan field must be greater than or equal to 8 years ago.',
            'batch.max' => 'The angkatan field must be less than or equal to the current year.',
            'phone_number.min' => 'The no hp field must be at least 9 characters.',
            'phone_number.max' => 'The no hp field must be at most 20 characters.',
            'phone_number.regex' => 'The no hp field must be start with 08.',
            'department_id.exists' => 'The selected department is invalid.',
            'department_id.required' => 'The department field is required.',
            'email.unique' => 'The email field must be unique.',
            'email.regex' => 'The email field must be a valid email address.',
            'email.required' => 'The email field is required.',
            'email.string' => 'The email field must be a string.',
            'email.max' => 'The email field must be at most 255 characters.',
        ];
    }
}
