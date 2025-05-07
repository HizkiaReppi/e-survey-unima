<?php

namespace App\Http\Controllers;

use App\Models\Period;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class PeriodController extends Controller
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
    public function index(Request $request): JsonResponse|View
    {
        $title = 'Apakah anda yakin?';
        $text = 'Anda tidak akan bisa mengembalikannya!';
        confirmDelete($title, $text);

        if ($request->ajax()) {
            $model = Period::all();

            return DataTables::of($model)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return $row->name;
                })
                ->addColumn('startDate', function ($row) {
                    $parseStartDate = date('d F Y', strtotime($row->start_date));
                    return $parseStartDate;
                })
                ->addColumn('endDate', function ($row) {
                    $parseEndDate = date('d F Y', strtotime($row->end_date));
                    return $parseEndDate;
                })
                ->addColumn('status', function ($row) {
                    $badge = $row->status === 'active'
                        ? '<span class="badge bg-success">Aktif</span>'
                        : '<span class="badge bg-secondary">Tidak Aktif</span>';
                    return $badge;
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
                        '" data-code="' .
                        $row->code .
                        '" data-start="' .
                        $row->start_date . 
                        '" data-status="' . 
                        $row->status  .
                        '" data-end="' . 
                        $row->end_date . 
                        '">
                                            <i class="bx bx-edit-alt me-1"></i> Edit
                                        </button>
                                        <a class="dropdown-item"
                                            href="' .
                        route('dashboard.periode.destroy', $row->id) .
                        '"
                                            data-confirm-delete="true">
                                            <i class="bx bx-trash me-1"></i> Delete
                                        </a>
                                    </div>
                                </div>';
                    return $btn;
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        return view('dashboard.periode.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255', 'min:2'],
            'code' => ['required', 'string', 'max:50', 'min:2', 'unique:' . Period::class],
            'start_date' => ['required', 'date', 'before:end_date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        DB::beginTransaction();

        try {
            $period = new Period();
            $period->code = $validatedData['code'];
            $period->name = $validatedData['name'];
            $period->start_date = $validatedData['start_date'];
            $period->end_date = $validatedData['end_date'];
            $period->status = $validatedData['status'];

            if ($validatedData['status'] === 'active') {
                Period::where('status', 'active')->update(['status' => 'inactive']);
            }

            $period->save();
            DB::commit();
            return redirect()->route('dashboard.periode.index')->with('toast_success', 'Periode berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('toast_error', 'Gagal menambahkan Periode.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Period $periode): RedirectResponse
    {
        $rules = [
            'name' => ['required', 'string', 'max:255', 'min:2'],
            'start_date' => ['required', 'date', 'before:end_date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'status' => ['required', 'in:active,inactive'],
        ];

        if ($periode->code != $request->code) {
            $rules['code'] = ['required', 'string', 'max:50', 'min:2', 'unique:' . Period::class];
        }

        $validatedData = $request->validate($rules);

        DB::beginTransaction();

        try {
            if ($validatedData['status'] === 'active') {
                Period::where('id', '!=', $periode->id)
                    ->where('status', 'active')
                    ->update(['status' => 'inactive']);
            }

            $periode->name = $validatedData['name'];

            if(isset($validatedData['code'])) {
                $periode->code = $validatedData['code'];
            }

            $periode->start_date = $validatedData['start_date'];
            $periode->end_date = $validatedData['end_date'];
            $periode->status = $validatedData['status'];

            $periode->save();
            DB::commit();
            return redirect()->route('dashboard.periode.index')->with('toast_success', 'Periode berhasil diperbaharui.');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return redirect()->back()->withInput()->with('toast_error', 'Gagal memperbaharui Periode.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Period $periode): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $periode->delete();
            DB::commit();

            return redirect()->route('dashboard.periode.index')->with('toast_success', 'Periode berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('toast_error', 'Gagal menghapus Periode.');
        }
    }
}