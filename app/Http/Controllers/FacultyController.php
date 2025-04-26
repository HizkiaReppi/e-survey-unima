<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class FacultyController extends Controller
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
            $model = Faculty::with('prodi');

            return DataTables::of($model)
                ->addIndexColumn()
                ->addColumn('fullname', function ($row) {
                    return $row->name;
                })
                ->addColumn('jumlahProdi', function ($row) {
                    return $row->prodi->count();
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
                        route('dashboard.faculties.show', $row->id) .
                        '">
                                            <i class="bx bxs-user-detail me-1"></i> Detail
                                        </a>
                                        <button class="dropdown-item btn-edit"
                                             data-bs-toggle="modal" data-bs-target="#modal-edit-data" data-id="' .
                        $row->id .
                        '" data-name="' .
                        $row->name .
                        '">
                                            <i class="bx bx-edit-alt me-1"></i> Edit
                                        </button>
                                        <a class="dropdown-item"
                                            href="' .
                        route('dashboard.faculties.destroy', $row->id) .
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

        return view('dashboard.faculties.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'fullname' => ['required', 'string', 'max:255', 'min:2', 'regex:/^[a-zA-Z\s]*$/'],
            'shortname' => ['required', 'string', 'max:255', 'min:1', 'regex:/^[a-zA-Z\s]*$/'],
        ]);

        DB::beginTransaction();

        try {
            $faculty = new Faculty();
            $faculty->name = $validatedData['fullname'];
            $faculty->short_name = $validatedData['shortname'];

            $faculty->save();
            DB::commit();
            return redirect()->route('dashboard.faculties.index')->with('toast_success', 'Fakultas berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('toast_error', 'Gagal menambahkan fakultas.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Faculty $fakulta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Faculty $fakulta): RedirectResponse
    {
        $validatedData = $request->validate([
            'fullname' => ['required', 'string', 'max:255', 'min:2', 'regex:/^[a-zA-Z\s]*$/'],
            'shortname' => ['required', 'string', 'max:255', 'min:1', 'regex:/^[a-zA-Z\s]*$/'],
        ]);

        DB::beginTransaction();

        try {
            $fakulta->name = $validatedData['fullname'];
            $fakulta->short_name = $validatedData['shortname'];

            $fakulta->save();
            DB::commit();
            return redirect()->route('dashboard.faculties.index')->with('toast_success', 'Fakultas berhasil diperbaharui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('toast_error', 'Gagal memperbaharui data fakultas.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Faculty $fakulta): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $fakulta->delete();
            DB::commit();

            return redirect()->route('dashboard.faculties.index')->with('toast_success', 'Fakultas berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('toast_error', 'Gagal menghapus fakultas.');
        }
    }
}