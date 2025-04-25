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
                        {{-- <th class="text-center">No</th>
                        <th class="text-center">Nama Responden</th>
                        <th class="text-center">Total Jawaban</th>
                        <th class="text-center">Rata-rata Nilai</th>
                        <th class="text-center">Aksi</th> --}}
                         <th>No</th>
                <th>Nama Responden</th>
                <th>Rata-rata Nilai Kategori</th>
                <th>Rata-rata Hasil</th>
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
            ajax: '{{ route('dashboard.survey.results.index') }}',
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'respondent_name', name: 'respondent_name'},
                {data: 'category_averages', name: 'category_averages'},
                {data: 'total_average', name: 'total_average'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });
    });
</script>
</x-dashboard-layout>
