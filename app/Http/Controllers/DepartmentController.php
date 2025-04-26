<?php

namespace App\Http\Controllers;

use App\Helpers\StatusHelper;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rules;

class DepartmentController extends Controller
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

        $faculties = Faculty::all();

        if ($request->ajax()) {
            $model = Department::with(['faculty']);

            return DataTables::of($model)
                ->addIndexColumn()
                ->addColumn('faculty', function ($row) {
                    return $row->faculty->name;
                })
                ->addColumn('name', function ($row) {
                    return $row->name;
                })
                ->addColumn('action', function ($row) {
                    $btn =
                        '<div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <button class="dropdown-item btn-edit"
                                             data-bs-toggle="modal" data-bs-target="#modal-edit-data" data-id="' .
                        $row->id .
                        '" data-name="' .
                        $row->name .
                        '" data-faculty-id="' .
                        $row->faculty->id .
                        '">
                                            <i class="bx bx-edit-alt me-1"></i> Edit
                                        </button>
                                        <a class="dropdown-item"
                                            href="' .
                        route('dashboard.departments.destroy', $row->id) .
                        '"
                                            data-confirm-delete="true">
                                            <i class="bx bx-trash me-1"></i> Delete
                                        </a>
                                    </div>
                                </div>';
                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('dashboard.departments.index', compact('faculties'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate(
            [
                'fullname' => ['required', 'string', 'max:255', 'min:2', 'regex:/^[a-zA-Z\s0-9]*$/'],
                'faculty_id' => ['required', 'exists:' . Faculty::class . ',id'],
            ],
            [
                'fullname.regex' => 'The name field must be alphabet.',
            ],
        );

        DB::beginTransaction();

        try {
            $department = new Department();
            $department->name = $validatedData['fullname'];
            $department->faculty_id = $validatedData['faculty_id'];

            $department->save();

            DB::commit();
            return redirect()->route('dashboard.departments.index')->with('toast_success', 'Program Studi berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('toast_error', 'Gagal menambahkan Program Studi.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $program_studi)
    {
        $validatedData = $request->validate(
            [
                'fullname' => ['required', 'string', 'max:255', 'min:2', 'regex:/^[a-zA-Z\s0-9]*$/'],
                'faculty_id' => ['required', 'exists:' . Faculty::class . ',id'],
            ],
            [
                'fullname.regex' => 'The name field must be alphabet.',
            ],
        );

        DB::beginTransaction();

        try {
            $program_studi->name = $validatedData['fullname'];
            $program_studi->faculty_id = $validatedData['faculty_id'];

            $program_studi->save();

            DB::commit();
            return redirect()->route('dashboard.departments.index')->with('toast_success', 'Program Studi berhasil diperbaharui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('toast_error', 'Gagal memperbaharui Program Studi.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $program_studi)
    {
        DB::beginTransaction();

        try {
            $program_studi->delete();
            DB::commit();

            return redirect()->route('dashboard.departments.index')->with('toast_success', 'Program Studi berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('toast_error', 'Gagal menghapus Program Studi.');
        }
    }
}