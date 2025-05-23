<x-dashboard-layout title="Manajemen Hasil Survey">
    <x-slot name="header">
        Manajemen Hasil Survey
    </x-slot>

    <div class="card">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-header">Daftar Hasil Survey</h5>
        </div>
        <div class="table-responsive text-nowrap px-4 pb-4">
            <table class="table" id="surveyResultsTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Mata Kuliah</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0"></tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#surveyResultsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('dashboard.survey.index') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });
    </script>
</x-dashboard-layout>
