<x-dashboard-layout title="Manajemen Mata Kuliah">
    <x-slot name="header">
        Manajemen Mata Kuliah
    </x-slot>

    <div class="card">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-header">Daftar Mata Kuliah</h5>
            <button data-bs-toggle="modal" data-bs-target="#staticBackdrop" class="btn btn-primary me-4">Tambah Mata
                Kuliah</button>
        </div>
        <div class="table-responsive text-nowrap px-4 pb-4">
            <table class="table" id="table-mk">
                <thead>
                    <tr>
                        <th class="text-center">Program Studi</th>
                        <th class="text-center">Kode Mata Kuliah</th>
                        <th class="text-center">Nama Mata Kuliah</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0"></tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah Data -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Data Program Studi</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="add-course" method="post" action="{{ route('dashboard.courses.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="department_id" class="form-label">Program Studi <span
                                    style="font-size:14px;color:red">*</span></label>
                            <x-select :options="$departments" key="name" placeholders="Pilih Program Studi"
                                id="department_id" name="department_id" required />
                            <x-input-error class="mt-2" :messages="$errors->get('department_id')" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="kode-mk">Kode Mata Kuliah <span
                                    style="font-size:14px;color:red">*</span></label>
                            <input type="text"
                                class="form-control {{ $errors->get('code') ? 'border-danger' : '' }}" id="code"
                                name="code" placeholder="Kode Mata Kuliah" value="{{ old('code') }}"
                                autocomplete="name" autofocus required />
                            <x-input-error class="mt-2" :messages="$errors->get('code')" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="nama-mk">Nama Mata Kuliah <span
                                    style="font-size:14px;color:red">*</span></label>
                            <input type="text"
                                class="form-control {{ $errors->get('name') ? 'border-danger' : '' }}" id="name"
                                name="name" placeholder="Nama Mata Kuliah" value="{{ old('name') }}"
                                autocomplete="name" autofocus required />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>
                        <div class="mb-3">
                            <label for="period_id" class="form-label">Tahun Akademik <span
                                    style="font-size:14px;color:red">*</span></label>
                            <x-select :options="$periods" key="name" placeholders="Pilih Tahun Akademik" id="period_id"
                                name="period_id" required />
                            <x-input-error class="mt-2" :messages="$errors->get('period_id')" />
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" form="add-course">Tambah Data</button>
                </div>
            </div>
        </div>
    </div>

    <!--  Modal Edit Form -->
    <div class="modal fade" id="modal-edit-data" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="modalEditDataLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalEditDataLabel">Edit Data Mata Kuliah</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="edit-course" method="post" action="">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="edit-department_id" class="form-label">Program Studi <span
                                    style="font-size:14px;color:red">*</span></label>
                            <x-select :options="$departments" key="name" placeholders="Pilih Program Studi"
                                id="edit-department_id" name="department_id" required />
                            <x-input-error class="mt-2" :messages="$errors->get('department_id')" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="edit-code">Kode Mata Kuliah <span
                                    style="font-size:14px;color:red">*</span></label>
                            <input type="text" class="form-control" id="edit-code" name="code"
                                placeholder="Kode Mata Kuliah" required />
                            <x-input-error class="mt-2" :messages="$errors->get('code')" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="edit-name">Nama Mata Kuliah <span
                                    style="font-size:14px;color:red">*</span></label>
                            <input type="text" class="form-control" id="edit-name" name="name"
                                placeholder="Nama Mata Kuliah" required />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>
                        <div class="mb-3">
                            <label for="edit-period_id" class="form-label">Tahun Akademik <span
                                    style="font-size:14px;color:red">*</span></label>
                            <x-select :options="$periods" key="name" placeholders="Pilih Tahun Akademik"
                                id="edit-period_id" name="period_id" required />
                            <x-input-error class="mt-2" :messages="$errors->get('period_id')" />
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" form="edit-course">Simpan Perubahan</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#table-mk').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('dashboard.courses.index') }}',
                columns: [{
                        data: 'department',
                        name: 'department',
                    },
                    {
                        data: 'code',
                        name: 'code'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ]
            });

            @if ($errors->has('fullname', 'faculty_id'))
                $('#staticBackdrop').modal('show');
            @endif

            $('#table-mk').on('click', '.btn-edit', function() {
                const name = $(this).data('name');
                const code = $(this).data('code');
                const department = $(this).data('department');
                const period = $(this).data('period');

                $('#edit-name').val(name);
                $('#edit-code').val(code);
                $('#edit-department_id').val(department);
                $('#edit-period_id').val(period);

                let formAction = "{{ route('dashboard.courses.update', ':code') }}";
                formAction = formAction.replace(':code', code);
                $('#edit-course').attr('action', formAction);

                $('#modal-edit-data').modal('show');
            });
        });
    </script>
</x-dashboard-layout>