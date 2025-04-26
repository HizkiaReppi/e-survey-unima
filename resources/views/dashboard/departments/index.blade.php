<x-dashboard-layout title="Manajemen Program Studi">
    <x-slot name="header">
        Manajemen Program Studi
    </x-slot>

    <div class="card">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-header">Daftar Program Studi</h5>
            <button data-bs-toggle="modal" data-bs-target="#staticBackdrop" class="btn btn-primary me-4">Tambah Program
                Studi</button>
        </div>
        <div class="table-responsive text-nowrap px-4 pb-4">
            <table class="table" id="table-prodi">
                <thead>
                    <tr>
                        <th class="text-center">Fakultas</th>
                        <th class="text-center">Nama Program Studi</th>
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
                    <form id="tambah-prodi" method="post" action="{{ route('dashboard.departments.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="faculty_id" class="form-label">Fakultas <span
                                    style="font-size:14px;color:red">*</span></label>
                            <x-select :options="$faculties" key="name" placeholders="Pilih Fakultas" id="faculty_id"
                                name="faculty_id" required />
                            <x-input-error class="mt-2" :messages="$errors->get('faculty_id')" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="fullname">Nama Program Studi <span
                                    style="font-size:14px;color:red">*</span></label>
                            <input type="text"
                                class="form-control {{ $errors->get('fullname') ? 'border-danger' : '' }}"
                                id="fullname" name="fullname" placeholder="Nama Fakultas" value="{{ old('fullname') }}"
                                autocomplete="name" autofocus required />
                            <x-input-error class="mt-2" :messages="$errors->get('fullname')" />
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" form="tambah-prodi">Tambah Data</button>
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
                    <h1 class="modal-title fs-5" id="modalEditDataLabel">Edit Data Program Studi</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="edit-prodi" method="post" action="">
                        @csrf
                        @method('put')
                        <div class="mb-3">
                            <label for="edit-faculty_id" class="form-label">Fakultas <span
                                    style="font-size:14px;color:red">*</span></label>
                            <x-select :options="$faculties" key="name" placeholders="Pilih Fakultas"
                                id="edit-faculty_id" name="faculty_id" required />
                            <x-input-error class="mt-2" :messages="$errors->get('faculty_id')" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="edit-fullname">Nama Program Studi <span
                                    style="font-size:14px;color:red">*</span></label>
                            <input type="text" class="form-control" id="edit-fullname" name="fullname"
                                placeholder="Nama Program Studi" required />
                            <x-input-error class="mt-2" :messages="$errors->get('fullname')" />
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" form="edit-prodi">Simpan Perubahan</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#table-prodi').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('dashboard.departments.index') }}',
                columns: [{
                        data: 'faculty',
                        name: 'faculty',
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

            $('#table-prodi').on('click', '.btn-edit', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');
                const facultyId = $(this).data('faculty-id');

                $('#edit-id').val(id);
                $('#edit-fullname').val(name);
                $('#edit-faculty_id').val(facultyId);

                let formAction = "{{ route('dashboard.departments.update', ':id') }}";
                formAction = formAction.replace(':id', id);
                $('#edit-prodi').attr('action', formAction);

                $('#modal-edit-data').modal('show');
            });
        });
    </script>
</x-dashboard-layout>