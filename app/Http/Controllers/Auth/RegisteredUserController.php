<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Lecturer;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $departments = Department::all();
        return view('auth.register', compact('departments'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'fullname' => ['required', 'string', 'max:255', 'min:2', 'regex:/^[a-zA-Z\s]*$/'],
            'nim' => ['required', 'string', 'max:10', 'min:4', 'unique:' . Student::class, 'regex:/^[0-9]*$/'],
            'email' => ['required', 'string', 'email', 'max:255', 'min:4', 'unique:' . User::class],
            'angkatan' => ['required', 'integer', 'digits:4', 'min:1900', 'max:' . (date('Y'))],
            'department_id' => ['required', 'exists:' . Department::class . ',id'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'fullname.regex' => 'The nim field must be alphabet.',
            'nim.unique' => 'The nim field must be unique.',
            'nim.regex' => 'The nim field must be number.',
        ]);

        DB::beginTransaction();

        try {
            $user = new User();
            $user->name = $validatedData['fullname'];
            $user->username = $validatedData['nim'];
            $user->email = $validatedData['email'];
            $user->password = Hash::make($validatedData['password']);
            $user->role = 'student';
            $user->save();

            $student = new Student();
            $student->user_id = $user->id;
            $student->nim = $validatedData['nim'];
            $student->batch = $validatedData['angkatan'];
            $student->department_id = $validatedData['department_id'];

            $student->save();

            DB::commit();

            Auth::login($user);

            return redirect(route('dashboard.survey.create', absolute: false));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('toast_error', 'Failed to add Student. Please try again.');
        }
    }
}