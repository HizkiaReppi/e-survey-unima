<x-dashboard-layout title="Manajemen Fakultas">
    <x-slot name="header">
        Manajemen Fakultas
    </x-slot>

    <div class="card">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-header">Daftar Fakultas</h5>
            <button class="btn btn-primary me-4" data-bs-toggle="modal"
                data-bs-target="#staticBackdrop">Tambah Fakultas</button>
        </div>
        <div class="table-responsive text-nowrap px-4 pb-4">
            <table class="table" id="table-fakultas">
                <thead>
                    <tr>
                        <th class="text-center">Nama</th>
                        <th class="text-center">Jumlah Prodi</th>
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
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Data Fakultas</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="tambah-fakultas" method="post" action="{{ route('dashboard.faculties.store') }}">
                        @csrf
                        <div>
                            <label class="form-label" for="fullname">Nama Fakultas <span
                                style="font-size:14px;color:red">*</span></label>
                            <input type="text" class="form-control {{ $errors->get('fullname') ? 'border-danger' : '' }}"
                                id="fullname" name="fullname" placeholder="Nama Fakultas" value="{{ old('fullname') }}"
                                autocomplete="name" autofocus required />
                            <x-input-error class="mt-2" :messages="$errors->get('fullname')" />
                        </div>
                        <div class="mb-3 mt-3">
                            <label class="form-label" for="shortname">Singkatan <span
                                style="font-size:14px;color:red">*</span></label>
                            <input type="text" class="form-control {{ $errors->get('shortname') ? 'border-danger' : '' }}"
                                id="shortname" name="shortname" placeholder="Singkatan" value="{{ old('shortname') }}"
                                autocomplete="name" autofocus required />
                            <x-input-error class="mt-2" :messages="$errors->get('shortname')" />
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" form="tambah-fakultas">Tambah Data</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Data -->
    <div class="modal fade" id="modal-edit-data" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="modalEditDataLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalEditDataLabel">Edit Data Fakultas</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="edit-fakultas" method="post" action="">
                        @csrf
                        @method('put')
                        <div>
                            <label class="form-label" for="edit-fullname">Nama Fakultas <span
                                style="font-size:14px;color:red">*</span></label>
                            <input type="text" class="form-control" id="edit-fullname" name="fullname"
                                placeholder="Nama Fakultas" required />
                        </div>
                        <div class="mb-3 mt-3">
                            <label class="form-label" for="edit-shortname">Singkatan <span
                                style="font-size:14px;color:red">*</span></label>
                            <input type="text" class="form-control" id="edit-shortname" name="shortname"
                                placeholder="Singkatan" required />
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" form="edit-fakultas">Simpan Perubahan</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#table-fakultas').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('dashboard.faculties.index') }}',
                columns: [{
                        data: 'fullname',
                        name: 'fullname'
                    },
                    {
                        data: 'jumlahProdi',
                        name: 'jumlahProdi',
                        className: 'text-center'
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

            @if ($errors->has('fullname'))
                $('#staticBackdrop').modal('show');
            @endif

            $('#table-fakultas').on('click', '.btn-edit', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');
                const shortname = $(this).data('shortname');

                $('#edit-id').val(id);
                $('#edit-fullname').val(name);
                $('#edit-shortname').val(shortname);

                let formAction = "{{ route('dashboard.faculties.update', ':id') }}";
                formAction = formAction.replace(':id', id);
                $('#edit-fakultas').attr('action', formAction);

                $('#modal-edit-data').modal('show');
            });
        });
    </script>
</x-dashboard-layout>