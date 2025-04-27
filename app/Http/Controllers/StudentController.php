<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Department;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Facades\DataTables;

class StudentController extends Controller
{
    public function __construct()
    {
        if (!Gate::allows('admin') && !Gate::allows('super-admin')) {
            abort(403);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = 'Apakah anda yakin?';
        $text = 'Anda tidak akan bisa mengembalikannya!';
        confirmDelete($title, $text);

        if ($request->ajax()) {
            $model = Student::with(['user', 'department']);

            return DataTables::of($model)
                ->addIndexColumn()
                ->addColumn('fullname', function ($row) {
                    return $row->fullname;
                })
                ->addColumn('nim', function ($row) {
                    return $row->formattedNIM;
                })
                ->addColumn('batch', function ($row) {
                    return $row->batch;
                })
                ->addColumn('department', function ($row) {
                    return $row->department->name;
                })
                ->addColumn('action', function ($row) {
                    $btn =
                        '<div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item"
                                            href="' .
                        route('dashboard.students.show', $row->id) .
                        '">
                                            <i class="bx bxs-user-detail me-1"></i> Detail
                                        </a>
                                        <a class="dropdown-item"
                                            href="' .
                        route('dashboard.students.edit', $row->id) .
                        '">
                                            <i class="bx bx-edit-alt me-1"></i> Edit
                                        </a>
                                        <a class="dropdown-item"
                                            href="' .
                        route('dashboard.students.destroy', $row->id) .
                        '"
                                            data-confirm-delete="true">
                                            <i class="bx bx-trash me-1"></i> Delete
                                        </a>
                                    </div>
                                </div>';
                    return $btn;
                })
                ->make(true);
        }

        return view('dashboard.students.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $departments =  Department::all();
        return view('dashboard.students.create', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudentRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        DB::beginTransaction();

        try {
            $user = new User();
            $user->name = $validatedData['fullname'];
            $user->username = $validatedData['nim'];
            $user->email = $validatedData['email'];
            $user->password = Hash::make($validatedData['nim']);
            $user->role = 'student';

            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $fileName = time() . '_mahasiswa_' . $user->username . '.' . $file->getClientOriginalExtension();

                $file->storeAs('public/images/profile-photo', $fileName);

                $user->photo = $fileName;
            }

            $user->save();

            $student = new Student();
            $student->user_id = $user->id;
            $student->department_id = $validatedData['department_id'];
            $student->nim = $validatedData['nim'];
            $student->batch = $validatedData['batch'];
            $student->phone_number = $validatedData['phone_number'];
            $student->address = $validatedData['address'];

            $student->save();

            DB::commit();
            return redirect()->route('dashboard.students.index')->with('toast_success', 'Student added successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('toast_error', 'Failed to add Student. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $mahasiswa): View
    {
        $title = 'Apakah anda yakin?';
        $text = 'Anda tidak akan bisa mengembalikannya!';
        confirmDelete($title, $text);

        $mahasiswa->load(['user', 'department']);

        return view('dashboard.students.show', compact('mahasiswa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $mahasiswa): View
    {
        $departments = Department::all();
        $mahasiswa->load(['user', 'department']);
        return view('dashboard.students.edit', compact('mahasiswa', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentRequest $request, Student $mahasiswa): RedirectResponse
    {
        $validatedData = $request->validated();

        DB::beginTransaction();

        try {
            if (isset($validatedData['nim'])) {
                $mahasiswa->user->username = $validatedData['nim'];
                $mahasiswa->nim = $validatedData['nim'];
                $mahasiswa->user->password = Hash::make($validatedData['nim']);
            }

            if (isset($validatedData['email'])) {
                $mahasiswa->user->email = $validatedData['email'];
            }

            if ($request->hasFile('photo')) {
                $oldImagePath = 'public/images/profile-photo/' . $mahasiswa->user->photo;
                if (Storage::exists($oldImagePath)) {
                    Storage::delete($oldImagePath);
                }

                $file = $request->file('photo');
                $fileName = time() . '_mahasiswa_' . $mahasiswa->user->username . '.' . $file->getClientOriginalExtension();

                $file->storeAs('public/images/profile-photo', $fileName);

                $mahasiswa->user->photo = $fileName;
            }

            $mahasiswa->user->name = $validatedData['fullname'];
            $mahasiswa->user->save();

            $mahasiswa->batch = $validatedData['batch'];
            $mahasiswa->phone_number = $validatedData['phone_number'];
            $mahasiswa->address = $validatedData['address'];

            $mahasiswa->save();

            DB::commit();
            return redirect()->route('dashboard.students.index')->with('toast_success', 'Student updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('toast_error', 'Failed to update Student. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $mahasiswa): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $mahasiswa->delete();
            $mahasiswa->user->delete();

            DB::commit();

            // delete foto
            $oldImagePath = 'public/images/profile-photo/' . $mahasiswa->user->photo;
            if (Storage::exists($oldImagePath)) {
                Storage::delete($oldImagePath);
            }

            return redirect()->route('dashboard.students.index')->with('toast_success', 'Student deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('toast_error', 'Failed to delete Student. Please try again.');
        }
    }
}
