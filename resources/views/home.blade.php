@extends('layouts.app')

@section('navbar')
<nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm">
    <div class="container px-4">
        <a class="navbar-brand" href="#">
            <img src="{{ asset('assets/images/logo_BenchMarkeat.svg') }}" alt="Logo BenchMarkeat" style="max-width: 200px; height: auto;">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
            aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
  
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="#home">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#about">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#comparison">Perbandingan</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
@endsection

@section('content')
<section class="home-section" id="home">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="fw-bold title">Analisis Sentimen Platform Food Delivery</h1>
                <p class="desc">Membantu tenant memperbaiki layanan dan merchant mengambil keputusan tepat berdasarkan analisis ulasan di Play Store dan Platform X.</p>
                <a href="#" class="btn btn-danger">Lihat Analisis</a>
            </div>
            <div class="col-md-6 text-center">
                <img src="{{ asset('assets/images/home_illustration.svg') }}" alt="Ilustrasi" class="img-fluid"  style="max-width: 500px; height: auto;">
            </div>
        </div>
    </div>
</section>

<section class="hero" id="about">
    <div class="container">
        <div class="how-it-work">
            <h1 class="text-center">Bagaimana <span class="highlight">BenchMarkeat</span> bekerja?</h1>
            <p class="sub-title text-center">Kami menyediakan wawasan untuk mengidentifikasi area perbaikan Tenant dan memudahkan Merchant mengambil keputusan bisnis lebih cerdas.</p>
            <div class="row mt-4">
                <div class="col-md-6">
                    <h5 class="fw-bold">Manfaat bagi Tenant</h5>
                    <ul class="sub-title">
                        <li>Identifikasi masalah yang perlu diperbaiki berdasarkan analisis ulasan pengguna.</li>
                        <li>Peroleh insight mendalam dengan kategori yang disoroti user mulai dari human, bug reports, feature requests, dan market.</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h5 class="fw-bold">Manfaat bagi Merchant</h5>
                    <ul class="sub-title">
                        <li>Bandingkan tenant secara objektif untuk menemukan platform yang paling cocok dengan bisnis anda.</li>
                        <li>Optimalkan keputusan bisnis anda dengan rekomendasi berbasis data yang akurat.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="comparison">
    <div class="container py-5">
        <div class="row" style="padding-top:4rem; padding-bottom:4rem;">
            <div class="col-10">
                <h3>Perbandingan Tenant</h3>
                <p>Solusi Data-Driven untuk Tenant dan Merchant dalam Mengoptimalkan Strategi Layanan dan Penjualan</p>
            </div>
            <div class="col-2">
                <div class="text-center mb-4">
                    <a href="#" class="btn btn-danger">Lihat Selengkapnya</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <!-- Radar Chart -->
                    <div class="col-md-6 d-flex align-items-center justify-content-center mb-4 mb-md-0">
                        <div class="w-100" style="max-width: 400px;">
                            <canvas id="radarChart"></canvas>
                        </div>
                    </div>

                    <!-- Testimoni -->
                    <div class="col-md-6">
                        <h5 class="mb-4 fw-bold">Apa kata mereka tentang GoBiz ?</h5>
                        
                        <div class="mb-3">
                            <div class="text-muted mb-1 small">Operational Experience (ease of order management, menu updates, etc)</div>
                            <div class="bg-white p-3 rounded shadow-sm">Pengaturan menunya memudahkan, tapi sering banget tiba-tiba error.</div>
                        </div>

                        <div class="mb-3">
                            <div class="text-muted mb-1 small">Financial Aspects (fees, commissions, payments, etc)</div>
                            <div class="bg-white p-3 rounded shadow-sm">Suka banget ada fitur support pemula, ada modal nya juga, terimakasih.</div>
                        </div>

                        <div>
                            <div class="text-muted mb-1 small">Financial Aspects (fees, commissions, payments, etc)</div>
                            <div class="bg-white p-3 rounded shadow-sm">Suka banget ada fitur support pemula, ada modal nya juga, terimakasih.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container py-5">
        <div class="row">
            <!-- Table Side -->
            <div class="col-md-6">
                <h5 class="mb-4 fw-bold">Food Delivery</h5>
                <table class="table" style="border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #FFFCF5;">
                            <th style="width: 5%;"></th>
                            <th style="width: 75%;">Tenant</th>
                            <th style="width: 20%;" class="text-end">reviews</th>
                        </tr>
                    </thead>
                    @php
                        $maxCount = max(array_column($results, 'count'));
                    @endphp

                    <tbody>
                        @foreach ($results as $index => $item)
                            @php
                                $rank = $index + 1;
                                $percentage = round(($item['count'] / $maxCount) * 100);
                                $rowBg = $index % 2 === 1 ? 'style=background-color:#FFFCF5;' : '';
                            @endphp
                            <tr {!! $rowBg !!}>
                                <td>{{ $rank }}</td>
                                <td {!! $rowBg !!}>
                                    <div class="bg-warning d-flex align-items-center rounded px-2" style="height: 28px; width: {{ $percentage }}%;">
                                        <span class="fw-semibold text-dark">{{ $item['label'] }}</span>
                                    </div>
                                </td>
                                <td class="text-end align-middle text-muted fw-medium" {!! $rowBg !!}>{{ $item['count'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>

            <!-- Chart Side -->
            <div class="col-md-6">
                <h5 class="mb-1 fw-bold">Distribusi sentimen</h5>
                <p class="text-muted small mb-3">Data from 2022 - 2024</p>
                <div style="position: relative; height: 200px;">
                    <canvas id="sentimentChart"></canvas>
                </div>

                <!-- Custom Legend -->
                <div class="d-flex mt-3 gap-4">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle" style="width:10px; height:10px; background-color:#FACC15;"></div>
                        <small class="text-muted">Positive</small>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle" style="width:10px; height:10px; background-color:#F43F5E;"></div>
                        <small class="text-muted">Negative</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>
@endsection

@section('script')
<script>
    const radarCtx = document.getElementById('radarChart').getContext('2d');
    const radarChart = new Chart(radarCtx, {
        type: 'radar',
        data: {
            labels: @json($labels),
            datasets: @json($datasets)
        },
        options: {
            responsive: true,
            scales: {
                r: {
                    angleLines: { display: true },
                    suggestedMin: 0,
                    suggestedMax: 100,
                    ticks: {
                        stepSize: 20,
                        backdropColor: 'transparent',
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.raw + ' %';
                        }
                    }
                },
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 20,
                        padding: 15
                    }
                }
            }
        }
    });
</script>


<script>
let sentimentChartInstance;

document.addEventListener('DOMContentLoaded', function () {
    const sentimentCtx = document.getElementById('sentimentChart').getContext('2d');

    if (sentimentChartInstance) {
        sentimentChartInstance.destroy();
    }

    sentimentChartInstance = new Chart(sentimentCtx, {
        type: 'bar',
        data: {
            labels: ['GrabFood', 'ShopeeFood', 'GoFood'],
            datasets: [
                {
                    label: 'Positive',
                    data: [68, 54, 65],
                    backgroundColor: '#FACC15',
                    barThickness: 25,
                    borderRadius: 5,
                },
                {
                    label: 'Negative',
                    data: [43, 72, 35],
                    backgroundColor: '#F43F5E',
                    barThickness: 25,
                    borderRadius: 5,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    enabled: true,
                    callbacks: {
                        label: function (context) {
                            return `${context.dataset.label}: ${context.parsed.y}`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    stacked: false,
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#6c757d',
                        font: {
                            size: 12
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#f5f5f5',
                        drawBorder: false
                    },
                    ticks: {
                        display: true,
                    }
                }
            }
        }
    });
});
</script>


@endsection

@section('style')
<style>
    #sentimentChart {
        height: 100% !important;
        width: 100% !important;
    }

    #radarChart {
        width: 100% !important;
        max-width: 500px;
        aspect-ratio: 1 / 1;
    }

    body {
        scroll-behavior: smooth;
    }
    .navbar {
        z-index: 1000;
    }
    .home-section {
        padding: 10rem 10rem;
    }
    .how-it-work {
        padding: 2rem 4rem;
    }
    .hero {
        background-color: #fff8f4;
        padding: 60px;
        margin-top:50px;
    }
    .btn-danger {
        background-color: #f44336;
        border: none;
    }
    .highlight {
        color: #f44336;
    }
    .title {
        font-size:50px;
    }
    .desc{
        padding-top:20px;
        padding-bottom:20px;
        font-size:20px;
    }
    .sub-title{
        padding-top:30px;
        padding-bottom:30px;
        font-size:20px;
    }
</style>
@endsection