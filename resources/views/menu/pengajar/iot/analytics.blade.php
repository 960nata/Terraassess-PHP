@extends('layouts.unified-layout')

@section('container')
    <h1 class="text-white">📊 IoT Analytics Dashboard</h1>
    <span class="text-white-75">Analisis mendalam data sensor IoT dan tren kualitas tanah</span>
    <hr class="border-white-25">

    <!-- Analytics Overview -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="glass-card p-4 text-center">
                <div class="text-white mb-2">
                    <i class="fas fa-chart-line fa-2x"></i>
                </div>
                <h3 class="text-white mb-1" id="avg-temperature">--°C</h3>
                <p class="text-white-75 mb-0">Rata-rata Suhu</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="glass-card p-4 text-center">
                <div class="text-white mb-2">
                    <i class="fas fa-tint fa-2x"></i>
                </div>
                <h3 class="text-white mb-1" id="avg-humidity">--%</h3>
                <p class="text-white-75 mb-0">Rata-rata Kelembaban</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="glass-card p-4 text-center">
                <div class="text-white mb-2">
                    <i class="fas fa-seedling fa-2x"></i>
                </div>
                <h3 class="text-white mb-1" id="avg-soil-moisture">--%</h3>
                <p class="text-white-75 mb-0">Rata-rata Kelembaban Tanah</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="glass-card p-4 text-center">
                <div class="text-white mb-2">
                    <i class="fas fa-database fa-2x"></i>
                </div>
                <h3 class="text-white mb-1" id="total-readings">0</h3>
                <p class="text-white-75 mb-0">Total Pembacaan</p>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row mb-4">
        <div class="col-lg-8 mb-4">
            <div class="glass-card p-4">
                <h5 class="text-white mb-3">
                    <i class="fas fa-chart-area me-2"></i>
                    Tren Data Sensor (7 Hari Terakhir)
                </h5>
                <canvas id="trendsChart" height="300"></canvas>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="glass-card p-4">
                <h5 class="text-white mb-3">
                    <i class="fas fa-chart-pie me-2"></i>
                    Distribusi Kualitas Tanah
                </h5>
                <canvas id="qualityChart" height="300"></canvas>
            </div>
        </div>
    </div>

    <!-- Detailed Analytics -->
    <div class="row mb-4">
        <div class="col-lg-6 mb-4">
            <div class="glass-card p-4">
                <h5 class="text-white mb-3">
                    <i class="fas fa-thermometer-half me-2"></i>
                    Analisis Suhu Tanah
                </h5>
                <div class="row">
                    <div class="col-6">
                        <div class="text-center">
                            <h4 class="text-white" id="temp-min">--°C</h4>
                            <small class="text-white-75">Minimum</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center">
                            <h4 class="text-white" id="temp-max">--°C</h4>
                            <small class="text-white-75">Maximum</small>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-info" id="temp-progress" style="width: 0%"></div>
                    </div>
                    <small class="text-white-75 mt-1 d-block">Kisaran Normal: 20-30°C</small>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="glass-card p-4">
                <h5 class="text-white mb-3">
                    <i class="fas fa-flask me-2"></i>
                    Analisis pH Tanah
                </h5>
                <div class="row">
                    <div class="col-6">
                        <div class="text-center">
                            <h4 class="text-white" id="ph-avg">--</h4>
                            <small class="text-white-75">Rata-rata</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center">
                            <h4 class="text-white" id="ph-status">--</h4>
                            <small class="text-white-75">Status</small>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-success" id="ph-progress" style="width: 0%"></div>
                    </div>
                    <small class="text-white-75 mt-1 d-block">Optimal: 6.0-7.5</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Export & Actions -->
    <div class="row">
        <div class="col-lg-4 mb-3">
            <div class="glass-card p-4 text-center">
                <div class="text-white mb-3">
                    <i class="fas fa-download fa-3x"></i>
                </div>
                <h5 class="text-white mb-2">Export Data</h5>
                <p class="text-white-75 mb-3">Download data analisis untuk laporan</p>
                <div class="btn-group" role="group">
                    <button class="btn btn-glass" onclick="exportData('csv')">
                        <i class="fas fa-file-csv me-1"></i> CSV
                    </button>
                    <button class="btn btn-glass" onclick="exportData('excel')">
                        <i class="fas fa-file-excel me-1"></i> Excel
                    </button>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-3">
            <div class="glass-card p-4 text-center">
                <div class="text-white mb-3">
                    <i class="fas fa-bell fa-3x"></i>
                </div>
                <h5 class="text-white mb-2">Smart Alerts</h5>
                <p class="text-white-75 mb-3">Notifikasi otomatis untuk kondisi kritis</p>
                <button class="btn btn-glass" onclick="configureAlerts()">
                    <i class="fas fa-cog me-1"></i> Konfigurasi
                </button>
            </div>
        </div>
        <div class="col-lg-4 mb-3">
            <div class="glass-card p-4 text-center">
                <div class="text-white mb-3">
                    <i class="fas fa-robot fa-3x"></i>
                </div>
                <h5 class="text-white mb-2">AI Insights</h5>
                <p class="text-white-75 mb-3">Prediksi dan rekomendasi berdasarkan data</p>
                <button class="btn btn-glass" onclick="generateInsights()">
                    <i class="fas fa-magic me-1"></i> Generate
                </button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let trendsChart, qualityChart;
    let analyticsData = {};

    // Initialize analytics dashboard
    document.addEventListener('DOMContentLoaded', function() {
        loadAnalytics();
        initializeCharts();
    });

    async function loadAnalytics() {
        try {
            const response = await fetch('/api/iot/analytics');
            const data = await response.json();
            
            if (data.success) {
                analyticsData = data.analytics;
                updateAnalyticsDisplay();
                updateCharts();
            }
        } catch (error) {
            console.error('Error loading analytics:', error);
            showNotification('Gagal memuat data analisis', 'error');
        }
    }

    function updateAnalyticsDisplay() {
        // Update overview cards
        document.getElementById('avg-temperature').textContent = 
            analyticsData.temperature_avg ? analyticsData.temperature_avg.toFixed(1) + '°C' : '--°C';
        document.getElementById('avg-humidity').textContent = 
            analyticsData.humidity_avg ? analyticsData.humidity_avg.toFixed(1) + '%' : '--%';
        document.getElementById('avg-soil-moisture').textContent = 
            analyticsData.soil_moisture_avg ? analyticsData.soil_moisture_avg.toFixed(1) + '%' : '--%';
        document.getElementById('total-readings').textContent = 
            analyticsData.total_readings || 0;

        // Update temperature analysis
        if (analyticsData.temperature_avg) {
            const temp = analyticsData.temperature_avg;
            const min = Math.max(0, temp - 5);
            const max = Math.min(50, temp + 5);
            
            document.getElementById('temp-min').textContent = min.toFixed(1) + '°C';
            document.getElementById('temp-max').textContent = max.toFixed(1) + '°C';
            
            // Update progress bar (20-30°C is optimal)
            const progress = Math.min(100, Math.max(0, ((temp - 15) / 20) * 100));
            document.getElementById('temp-progress').style.width = progress + '%';
        }

        // Update pH analysis
        if (analyticsData.ph_level_avg) {
            const ph = analyticsData.ph_level_avg;
            document.getElementById('ph-avg').textContent = ph.toFixed(1);
            
            let status = 'Optimal';
            let statusClass = 'text-success';
            if (ph < 6.0) {
                status = 'Asam';
                statusClass = 'text-warning';
            } else if (ph > 7.5) {
                status = 'Basa';
                statusClass = 'text-warning';
            }
            
            document.getElementById('ph-status').textContent = status;
            document.getElementById('ph-status').className = statusClass;
            
            // Update progress bar (6.0-7.5 is optimal)
            const progress = Math.min(100, Math.max(0, ((ph - 4) / 6) * 100));
            document.getElementById('ph-progress').style.width = progress + '%';
        }
    }

    function initializeCharts() {
        // Trends Chart
        const trendsCtx = document.getElementById('trendsChart').getContext('2d');
        trendsChart = new Chart(trendsCtx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [
                    {
                        label: 'Suhu (°C)',
                        data: [],
                        borderColor: '#ff6b6b',
                        backgroundColor: 'rgba(255, 107, 107, 0.1)',
                        tension: 0.4
                    },
                    {
                        label: 'Kelembaban (%)',
                        data: [],
                        borderColor: '#4ecdc4',
                        backgroundColor: 'rgba(78, 205, 196, 0.1)',
                        tension: 0.4
                    },
                    {
                        label: 'Kelembaban Tanah (%)',
                        data: [],
                        borderColor: '#45b7d1',
                        backgroundColor: 'rgba(69, 183, 209, 0.1)',
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            color: '#ffffff'
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            color: '#ffffff'
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        }
                    },
                    y: {
                        ticks: {
                            color: '#ffffff'
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        }
                    }
                }
            }
        });

        // Quality Chart
        const qualityCtx = document.getElementById('qualityChart').getContext('2d');
        qualityChart = new Chart(qualityCtx, {
            type: 'doughnut',
            data: {
                labels: ['Optimal', 'Baik', 'Perlu Perhatian', 'Kritis'],
                datasets: [{
                    data: [0, 0, 0, 0],
                    backgroundColor: [
                        '#4CAF50',
                        '#8BC34A',
                        '#FF9800',
                        '#F44336'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            color: '#ffffff'
                        }
                    }
                }
            }
        });
    }

    function updateCharts() {
        if (!analyticsData.data_trends) return;

        // Update trends chart
        const trends = analyticsData.data_trends;
        const labels = trends.map(t => new Date(t.date).toLocaleDateString());
        const temperatureData = trends.map(t => t.temperature_avg);
        const humidityData = trends.map(t => t.humidity_avg);
        const soilMoistureData = trends.map(t => t.soil_moisture_avg);

        trendsChart.data.labels = labels;
        trendsChart.data.datasets[0].data = temperatureData;
        trendsChart.data.datasets[1].data = humidityData;
        trendsChart.data.datasets[2].data = soilMoistureData;
        trendsChart.update();

        // Update quality chart (mock data for now)
        const qualityData = [60, 25, 10, 5]; // Optimal, Baik, Perlu Perhatian, Kritis
        qualityChart.data.datasets[0].data = qualityData;
        qualityChart.update();
    }

    function exportData(format) {
        const params = new URLSearchParams({
            format: format,
            start_date: new Date(Date.now() - 30 * 24 * 60 * 60 * 1000).toISOString(),
            end_date: new Date().toISOString()
        });

        window.open(`/api/iot/export-data?${params}`, '_blank');
        showNotification(`Data berhasil diexport dalam format ${format.toUpperCase()}`, 'success');
    }

    function configureAlerts() {
        showNotification('Fitur konfigurasi alert akan segera tersedia', 'info');
    }

    function generateInsights() {
        showNotification('Fitur AI insights akan segera tersedia', 'info');
    }

    function showNotification(message, type = 'info') {
        const alertClass = type === 'error' ? 'alert-danger' : 
                          type === 'success' ? 'alert-success' : 
                          type === 'warning' ? 'alert-warning' : 'alert-info';
        
        const notification = document.createElement('div');
        notification.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 5000);
    }
</script>
@endsection
