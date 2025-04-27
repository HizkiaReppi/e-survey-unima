<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLecturerRequest;
use App\Models\Department;
use App\Models\Lecturer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Facades\DataTables;

class LecturerController extends Controller
{
    public function __construct()
    {
        if (!Gate::allows('admin') && !Gate::allows('super-admin') && !Gate::allows('HoD')) {
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
            $model = Lecturer::with(['department']);

            return DataTables::of($model)
                ->addIndexColumn()
                ->addColumn('fullname', function ($row) {
                    return $row->fullname;
                })
                ->addColumn('functional_position', function ($row) {
                    return $row->functional_position ?? 'Data belum dilengkapi';
                })
                ->addColumn('academic_rank', function ($row) {
                    return $row->academic_rank ?? 'Data belum dilengkapi';
                })
                ->addColumn('employee_status', function ($row) {
                    return $row->employee_status ?? 'Data belum dilengkapi';
                })
                ->addColumn('certification_status', function ($row) {
                    return $row->certification_status ?? 'Data belum dilengkapi';
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
                        route('dashboard.lecturers.edit', $row->id) .
                        '">
                                            <i class="bx bx-edit-alt me-1"></i> Edit
                                        </a>
                                        <a class="dropdown-item"
                                            href="' .
                        route('dashboard.lecturers.destroy', $row->id) .
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

        return view('dashboard.lecturers.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $departments = Department::all();
        return view('dashboard.lecturers.create', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLecturerRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        DB::beginTransaction();

        try {
            $lecturer = new Lecturer();
            $lecturer->fullname = $validatedData['fullname'];
            $lecturer->department_id = $validatedData['department_id'];
            $lecturer->functional_position = $validatedData['functional_position'];
            $lecturer->academic_rank = $validatedData['academic_rank'];
            $lecturer->employee_status = $validatedData['employee_status'];
            $lecturer->certification_status = $validatedData['certification_status'];
            $lecturer->save();

            DB::commit();

            return redirect()->route('dashboard.lecturers.index')->with('toast_success', 'Lecturer added successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return redirect()->back()->withInput()->with('error', 'Failed to add Lecturer. Please try again.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lecturer $dosen): View
    {
        $departments = Department::all();
        return view('dashboard.lecturers.edit', compact('dosen', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreLecturerRequest $request, Lecturer $dosen): RedirectResponse
    {
        $validatedData = $request->validated();

        DB::beginTransaction();

        try {
            $dosen->fullname = $validatedData['fullname'];
            $dosen->department_id = $validatedData['department_id'];
            $dosen->functional_position = $validatedData['functional_position'];
            $dosen->academic_rank = $validatedData['academic_rank'];
            $dosen->employee_status = $validatedData['employee_status'];
            $dosen->certification_status = $validatedData['certification_status'];
            $dosen->save();

            DB::commit();

            return redirect()->route('dashboard.lecturers.index')->with('toast_success', 'Lecturer updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput()->with('error', 'Failed to update Lecturer. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lecturer $dosen)
    {
        DB::beginTransaction();

        try {
            $dosen->delete();

            DB::commit();
            return redirect()->route('dashboard.lecturers.index')->with('toast_success', 'Lecturer deleted successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to delete Lecturer. Please try again.');
        }
    }
}