<x-dashboard-layout title="Dashboard">
    <x-slot name="header">
        Dashboard
    </x-slot>

    <div class="row">
        <div class="col-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Total Responden</h5>
                    <h2 id="respondentsCount">Loading...</h2>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Rata-rata Skor per Kategori</h5>
                    <div id="categoryChart" style="height: 400px;"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('{{ route('dashboard.survey-data') }}')
                .then(response => response.json())
                .then(data => {
                    // Update total responden
                    document.getElementById('respondentsCount').textContent = data.respondents + " Mahasiswa";

                    // Prepare chart
                    const categories = data.categories.map(item => item.category);
                    const averages = data.categories.map(item => item.average);

                    var options = {
                        chart: {
                            type: 'bar',
                            height: 400
                        },
                        plotOptions: {
                            bar: {
                                borderRadius: 8,
                                horizontal: false,
                            }
                        },
                        dataLabels: {
                            enabled: true
                        },
                        xaxis: {
                            categories: categories
                        },
                        yaxis: {
                            min: 0,
                            max: 5,
                            title: {
                                text: 'Skor Rata-rata'
                            }
                        },
                        series: [{
                            name: 'Rata-rata Skor',
                            data: averages
                        }],
                        colors: ['#00E396']
                    };

                    var chart = new ApexCharts(document.querySelector("#categoryChart"), options);
                    chart.render();
                })
                .catch(error => console.error('Gagal mengambil data survey:', error));
        });
    </script>
</x-dashboard-layout>
