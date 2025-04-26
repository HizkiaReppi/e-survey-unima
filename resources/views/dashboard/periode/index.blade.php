<x-dashboard-layout title="Manajemen Periode">
    <x-slot name="header">
        Manajemen Periode
    </x-slot>

    <div class="card">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-header">Daftar Periode</h5>
            <button class="btn btn-primary me-4" data-bs-toggle="modal"
                data-bs-target="#staticBackdrop">Tambah Periode</button>
        </div>
        <div class="table-responsive text-nowrap px-4 pb-4">
            <table class="table" id="table-periode">
                <thead>
                    <tr>
                        <th class="text-center">Nama</th>
                        <th class="text-center">Tanggal Mulai</th>
                        <th class="text-center">Tanggal Berakhir</th>
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
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Data Periode</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="tambah-periode" method="post" action="{{ route('dashboard.periode.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="code">Kode Periode <span
                                style="font-size:14px;color:red">*</span></label>
                            <input type="text" class="form-control {{ $errors->get('code') ? 'border-danger' : '' }}"
                                id="code" name="code" placeholder="Kode Periode" value="{{ old('code') }}"
                                autocomplete="code" autofocus required />
                            <x-input-error class="mt-2" :messages="$errors->get('code')" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="name">Nama Periode <span
                                style="font-size:14px;color:red">*</span></label>
                            <input type="text" class="form-control {{ $errors->get('name') ? 'border-danger' : '' }}"
                                id="name" name="name" placeholder="Nama Periode" value="{{ old('name') }}"
                                autocomplete="name" required />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="start_date">Tanggal Mulai <span
                                style="font-size:14px;color:red">*</span></label>
                            <input type="date" class="form-control {{ $errors->get('start_date') ? 'border-danger' : '' }}"
                                id="start_date" name="start_date" placeholder="Nama Periode" value="{{ old('start_date') }}"
                                autocomplete="name" required />
                            <x-input-error class="mt-2" :messages="$errors->get('start_date')" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="end_date">Tanggal Berakhir <span
                                style="font-size:14px;color:red">*</span></label>
                            <input type="date" class="form-control {{ $errors->get('end_date') ? 'border-danger' : '' }}"
                                id="end_date" name="end_date" placeholder="Nama Periode" value="{{ old('end_date') }}"
                                autocomplete="name" required />
                            <x-input-error class="mt-2" :messages="$errors->get('end_date')" />
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" form="tambah-periode">Tambah Data</button>
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
                    <h1 class="modal-title fs-5" id="modalEditDataLabel">Edit Data Periode</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="edit-periode" method="post" action="">
                        @csrf
                        @method('put')
                        <div class="mb-3">
                            <label class="form-label" for="edit-code">Kode Periode <span
                                style="font-size:14px;color:red">*</span></label>
                            <input type="text" class="form-control {{ $errors->get('code') ? 'border-danger' : '' }}"
                                id="edit-code" name="code" placeholder="Nama Periode" value="{{ old('code') }}"
                                autocomplete="code" autofocus required />
                            <x-input-error class="mt-2" :messages="$errors->get('code')" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="edit-name">Nama Periode <span
                                style="font-size:14px;color:red">*</span></label>
                            <input type="text" class="form-control {{ $errors->get('name') ? 'border-danger' : '' }}"
                                id="edit-name" name="name" placeholder="Nama Periode" value="{{ old('name') }}"
                                autocomplete="name" required />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="edit-start_date">Tanggal Mulai <span
                                style="font-size:14px;color:red">*</span></label>
                            <input type="date" class="form-control {{ $errors->get('start_date') ? 'border-danger' : '' }}"
                                id="edit-start_date" name="start_date" placeholder="Nama Periode" value="{{ old('start_date') }}"
                                autocomplete="name" required />
                            <x-input-error class="mt-2" :messages="$errors->get('start_date')" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="edit-end_date">Tanggal Berakhir <span
                                style="font-size:14px;color:red">*</span></label>
                            <input type="date" class="form-control {{ $errors->get('end_date') ? 'border-danger' : '' }}"
                                id="edit-end_date" name="end_date" placeholder="Nama Periode" value="{{ old('end_date') }}"
                                autocomplete="name" required />
                            <x-input-error class="mt-2" :messages="$errors->get('end_date')" />
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" form="edit-periode">Simpan Perubahan</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#table-periode').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('dashboard.periode.index') }}',
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'startDate',
                        name: 'startDate',
                        className: 'text-center'
                    },
                    {
                        data: 'endDate',
                        name: 'endDate',
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

            $('#table-periode').on('click', '.btn-edit', function() {
                const id = $(this).data('id');
                const code = $(this).data('code');
                const name = $(this).data('name');
                const startDate = $(this).data('start');
                const endDate = $(this).data('end');

                $('#edit-id').val(id);
                $('#edit-code').val(code);
                $('#edit-name').val(name);
                $('#edit-start_date').val(startDate);
                $('#edit-end_date').val(endDate);

                let formAction = "{{ route('dashboard.periode.update', ':id') }}";
                formAction = formAction.replace(':id', id);
                $('#edit-periode').attr('action', formAction);

                $('#modal-edit-data').modal('show');
            });
        });
    </script>
</x-dashboard-layout>
