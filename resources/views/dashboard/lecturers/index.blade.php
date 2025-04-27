<x-dashboard-layout title="Manajemen Dosen">
    <x-slot name="header">
        Manajemen Dosen
    </x-slot>

    <div class="card">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-header">Daftar Dosen</h5>
            <a href="{{ route('dashboard.lecturers.create') }}" class="btn btn-primary me-4">Tambah Dosen</a>
        </div>
        <div class="table-responsive text-nowrap px-4 pb-4">
            <table class="table" id="table-lecturer">
                <thead>
                    <tr>
                        <th class="text-center">Nama</th>
                        <th class="text-center">Jabatan Fungsional</th>
                        <th class="text-center">Kepangkatan</th>
                        <th class="text-center">Status Pegawai</th>
                        <th class="text-center">Status Sertifikasi</th>
                        <th class="text-center">Program Studi</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0"></tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#table-lecturer').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('dashboard.lecturers.index') }}',
                columns: [
                    { data: 'fullname', name: 'fullname' },
                    { data: 'functional_position', name: 'functional_position' },
                    { data: 'academic_rank', name: 'academic_rank' },
                    { data: 'employee_status', name: 'employee_status' },
                    { data: 'certification_status', name: 'certification_status' },
                    { data: 'department', name: 'department' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'}
                ]
            });
        });
    </script>
</x-dashboard-layout>