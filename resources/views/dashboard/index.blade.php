<x-dashboard-layout title="Dashboard Admin">
    <x-slot name="header">Dashboard Survey Kepuasan Mahasiswa</x-slot>

    <div class="row mb-4">
        <div class="col-md-3">
            <select id="periodFilter" class="form-select">
                <option value="">-- Semua Periode --</option>
                @foreach ($periods as $period)
                    <option value="{{ $period->id }}">{{ $period->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select id="lecturerFilter" class="form-select">
                <option value="">-- Semua Dosen --</option>
                @foreach ($lecturers as $lecturer)
                    <option value="{{ $lecturer->id }}">{{ $lecturer->fullname }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select id="courseFilter" class="form-select">
                <option value="">-- Semua Mata Kuliah --</option>
                @foreach ($courses as $course)
                    <option value="{{ $course->id }}">{{ $course->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <button id="filterButton" class="btn btn-primary w-100">Terapkan Filter</button>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div id="loading" style="display: none;">Memuat data...</div>
            <div id="lecturerSurveyChart" style="height: 400px;"></div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 my-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Rata-rata Skor per Mata Kuliah</h5>
                    <div id="courseAverageChart" style="height: 350px;"></div>
                </div>
            </div>
        </div>

        <div class="col-md-6 my-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Top 10 Dosen Terbaik</h5>
                    <div id="topLecturersChart" style="height: 350px;"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        $(document).ready(function() {
            $('#courseFilter').select2({
                theme: 'bootstrap-5',
                placeholder: "Pilih Mata Kuliah",
                allowClear: true
            });
            $('#lecturerFilter').select2({
                theme: 'bootstrap-5',
                placeholder: "Pilih Dosen",
                allowClear: true
            });
            $('#periodFilter').select2({
                theme: 'bootstrap-5',
                placeholder: "Pilih Periode",
                allowClear: true
            });
        });
    </script>

    <script>
        let chart = null;

        function fetchChartData() {
            document.getElementById('loading').style.display = 'block';
            const period = document.getElementById('periodFilter').value;
            const lecturer = document.getElementById('lecturerFilter').value;
            const course = document.getElementById('courseFilter').value;

            let url = '{{ route('dashboard.survey.chart-data') }}';
            const params = new URLSearchParams();
            if (period) params.append('period_id', period);
            if (lecturer) params.append('lecturer_id', lecturer);
            if (course) params.append('course_id', course);

            fetch(url + '?' + params.toString())
                .then(response => response.json())
                .then(result => {
                    document.getElementById('loading').style.display = 'none';
                    renderChart(result.data);
                })
                .catch(error => {
                    console.error('Gagal mengambil data chart:', error);
                    document.getElementById('loading').innerHTML = 'Gagal memuat data.';
                });
        }

        function renderChart(data) {
            const categories = data.map(item => item.lecturer_name);
            const averages = data.map(item => item.avg_score);

            if (chart) {
                chart.updateOptions({
                    xaxis: {
                        categories: categories
                    },
                    series: [{
                        name: 'Rata-rata Skor',
                        data: averages
                    }]
                });
            } else {
                var options = {
                    chart: {
                        type: 'bar',
                        height: 400
                    },
                    plotOptions: {
                        bar: {
                            horizontal: true,
                            borderRadius: 8
                        }
                    },
                    dataLabels: {
                        enabled: true
                    },
                    xaxis: {
                        categories: categories,
                    },
                    yaxis: {
                        min: 0,
                        max: 5,
                    },
                    series: [{
                        name: 'Rata-rata Skor',
                        data: averages
                    }],
                    colors: ['#00E396'],
                };

                chart = new ApexCharts(document.querySelector("#lecturerSurveyChart"), options);
                chart.render();
            }
        }

        document.getElementById('filterButton').addEventListener('click', function() {
            fetchChartData();
        });

        async function loadChart(id, url, type = 'bar', horizontal = false) {
            const response = await fetch(url);
            const result = await response.json();

            const categories = result.data.map(d => Object.values(d)[0]);
            const scores = result.data.map(d => Object.values(d)[1]);

            const options = {
                chart: {
                    type: type,
                    height: 350
                },
                plotOptions: {
                    bar: {
                        horizontal: horizontal,
                        borderRadius: 8,
                    }
                },
                dataLabels: {
                    enabled: true
                },
                xaxis: {
                    categories: categories,
                },
                series: [{
                    name: 'Rata-rata Skor',
                    data: scores
                }],
                colors: ['#008FFB']
            };

            const chart = new ApexCharts(document.querySelector(id), options);
            chart.render();
        }

        document.addEventListener('DOMContentLoaded', function() {
            fetchChartData();
            loadChart('#courseAverageChart', '{{ route('dashboard.analytics.course-average') }}', 'bar', true);
            loadChart('#topLecturersChart', '{{ route('dashboard.analytics.top-lecturers') }}', 'bar', true);
        });
    </script>
</x-dashboard-layout>
