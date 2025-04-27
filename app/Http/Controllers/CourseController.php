<?php

namespace App\Http\Controllers;

use App\Helpers\PeriodHelper;
use App\Models\Course;
use App\Models\Department;
use App\Models\Period;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Facades\DataTables;

class CourseController extends Controller
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

        $departments = Department::all();
        $periods = Period::all();

        if ($request->ajax()) {
            $model = Course::where('period_id', PeriodHelper::getCurrentPeriod())->with(['department', 'period']);

            return DataTables::of($model)
                ->addIndexColumn()
                ->addColumn('department', function ($row) {
                    return $row->department->name;
                })
                ->addColumn('code', function ($row) {
                    return $row->code;
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
                                             data-bs-toggle="modal" data-bs-target="#modal-edit-data" data-name="' .
                        $row->name .
                        '" data-code="' .
                        $row->code .
                        '" data-department="' .
                        $row->department->id .
                        '" data-period="' .
                        $row->period->id .
                        '">
                                            <i class="bx bx-edit-alt me-1"></i> Edit
                                        </button>
                                        <a class="dropdown-item"
                                            href="' .
                        route('dashboard.courses.destroy', $row->code) .
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

        return view('dashboard.courses.index', compact('departments', 'periods'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'department_id' => ['required', 'exists:' . Department::class . ',id'],
            'period_id' => ['required', 'exists:' . Period::class . ',id'],
            'code' => ['required', 'string', 'max:20'],
            'name' => ['required', 'string', 'max:100'],
        ]);

        DB::beginTransaction();

        try {
            $course = new Course();
            $course->code = $validatedData['code'];
            $course->name = $validatedData['name'];
            $course->department_id = $validatedData['department_id'];
            $course->period_id = $validatedData['period_id'];
            $course->save();

            DB::commit();
            return redirect()->route('dashboard.courses.index')->with('toast_success', 'Mata Kuliah berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return redirect()->back()->withInput()->with('toast_error', 'Gagal menambahkan Mata Kuliah.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $mata_kuliah)
    {
        $validatedData = $request->validate([
            'department_id' => ['required', 'exists:' . Department::class . ',id'],
            'code' => ['required', 'string', 'max:20'],
            'name' => ['required', 'string', 'max:100'],
        ]);

        DB::beginTransaction();

        try {
            $mata_kuliah->code = $validatedData['code'];
            $mata_kuliah->name = $validatedData['name'];
            $mata_kuliah->department_id = $validatedData['department_id'];
            $mata_kuliah->save();

            DB::commit();
            return redirect()->route('dashboard.courses.index')->with('toast_success', 'Mata Kuliah berhasil diperbaharui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('toast_error', 'Gagal memperbaharui Mata Kuliah.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $mata_kuliah)
    {
        DB::beginTransaction();

        try {
            $mata_kuliah->delete();
            DB::commit();

            return redirect()->route('dashboard.courses.index')->with('toast_success', 'Mata Kuliah berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('toast_error', 'Gagal menghapus Mata Kuliah.');
        }
    }
}