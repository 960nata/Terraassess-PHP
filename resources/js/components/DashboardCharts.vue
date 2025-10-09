<template>
  <div class="dashboard-charts">
    <!-- Row 1: Tugas Completion & Jumlah Tugas -->
    <div class="charts-row">
      <div class="chart-container">
        <div class="chart-header">
          <h3 class="chart-title">Persentase Siswa Mengerjakan Tugas</h3>
          <div class="chart-actions">
            <select v-model="selectedClass" @change="updateChartData" class="chart-select">
              <option value="all">Semua Kelas</option>
              <option v-for="kelas in classes" :key="kelas.id" :value="kelas.id">
                {{ kelas.name }}
              </option>
            </select>
          </div>
        </div>
        <div class="chart-wrapper">
          <div id="tugasCompletionChart" class="chart"></div>
        </div>
      </div>
      
      <div class="chart-container">
        <div class="chart-header">
          <h3 class="chart-title">Jumlah Tugas per Kelas</h3>
        </div>
        <div class="chart-wrapper">
          <div id="tugasCountChart" class="chart"></div>
        </div>
      </div>
    </div>

    <!-- Row 2: Realisasi Tugas & Ujian -->
    <div class="charts-row">
      <div class="chart-container">
        <div class="chart-header">
          <h3 class="chart-title">Realisasi Tugas</h3>
        </div>
        <div class="chart-wrapper">
          <div id="tugasRealisasiChart" class="chart"></div>
        </div>
      </div>
      
      <div class="chart-container">
        <div class="chart-header">
          <h3 class="chart-title">Status Ujian</h3>
        </div>
        <div class="chart-wrapper">
          <div id="ujianStatusChart" class="chart"></div>
        </div>
      </div>
    </div>

    <!-- Row 3: Aktivitas Terbaru -->
    <div class="charts-row">
      <div class="chart-container full-width">
        <div class="chart-header">
          <h3 class="chart-title">Aktivitas Terbaru</h3>
          <div class="chart-actions">
            <select v-model="activityPeriod" @change="updateActivityData" class="chart-select">
              <option value="7">7 Hari Terakhir</option>
              <option value="30">30 Hari Terakhir</option>
              <option value="90">90 Hari Terakhir</option>
            </select>
          </div>
        </div>
        <div class="chart-wrapper">
          <div id="activityChart" class="chart"></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import ApexCharts from 'apexcharts'

export default {
  name: 'DashboardCharts',
  props: {
    initialData: {
      type: Object,
      default: () => ({})
    },
    userRole: {
      type: String,
      default: 'student'
    },
    classes: {
      type: Array,
      default: () => []
    }
  },
  data() {
    return {
      selectedClass: 'all',
      activityPeriod: '7',
      charts: {},
      chartData: {
        tugasCompletion: {
          series: [],
          labels: []
        },
        tugasCount: {
          series: [],
          labels: []
        },
        tugasRealisasi: {
          series: [],
          labels: []
        },
        ujianStatus: {
          series: [],
          labels: []
        },
        activity: {
          series: [],
          labels: []
        }
      }
    }
  },
  mounted() {
    this.initializeCharts()
    this.loadInitialData()
  },
  methods: {
    initializeCharts() {
      this.initTugasCompletionChart()
      this.initTugasCountChart()
      this.initTugasRealisasiChart()
      this.initUjianStatusChart()
      this.initActivityChart()
    },

    initTugasCompletionChart() {
      const options = {
        series: [0, 0],
        chart: {
          type: 'donut',
          height: 300,
          background: 'transparent',
          foreColor: '#ffffff'
        },
        labels: ['Mengerjakan', 'Belum Mengerjakan'],
        colors: ['#8b5cf6', '#ef4444'],
        plotOptions: {
          pie: {
            donut: {
              size: '70%',
              labels: {
                show: true,
                total: {
                  show: true,
                  label: 'Total Siswa',
                  color: '#ffffff',
                  formatter: function (w) {
                    return w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                  }
                }
              }
            }
          }
        },
        legend: {
          position: 'bottom',
          labels: {
            colors: '#ffffff'
          }
        },
        responsive: [{
          breakpoint: 480,
          options: {
            chart: {
              height: 250
            }
          }
        }]
      }

      this.charts.tugasCompletion = new ApexCharts(document.querySelector("#tugasCompletionChart"), options)
      this.charts.tugasCompletion.render()
    },

    initTugasCountChart() {
      const options = {
        series: [{
          name: 'Jumlah Tugas',
          data: []
        }],
        chart: {
          type: 'bar',
          height: 300,
          background: 'transparent',
          foreColor: '#ffffff'
        },
        colors: ['#3b82f6'],
        xaxis: {
          categories: [],
          labels: {
            style: {
              colors: '#ffffff'
            }
          }
        },
        yaxis: {
          labels: {
            style: {
              colors: '#ffffff'
            }
          }
        },
        grid: {
          borderColor: 'rgba(255, 255, 255, 0.1)'
        },
        dataLabels: {
          enabled: true,
          style: {
            colors: ['#ffffff']
          }
        },
        responsive: [{
          breakpoint: 480,
          options: {
            chart: {
              height: 250
            }
          }
        }]
      }

      this.charts.tugasCount = new ApexCharts(document.querySelector("#tugasCountChart"), options)
      this.charts.tugasCount.render()
    },

    initTugasRealisasiChart() {
      const options = {
        series: [{
          name: 'Tugas Selesai',
          data: []
        }, {
          name: 'Tugas Belum Selesai',
          data: []
        }],
        chart: {
          type: 'line',
          height: 300,
          background: 'transparent',
          foreColor: '#ffffff'
        },
        colors: ['#10b981', '#f59e0b'],
        xaxis: {
          categories: [],
          labels: {
            style: {
              colors: '#ffffff'
            }
          }
        },
        yaxis: {
          labels: {
            style: {
              colors: '#ffffff'
            }
          }
        },
        grid: {
          borderColor: 'rgba(255, 255, 255, 0.1)'
        },
        stroke: {
          curve: 'smooth',
          width: 3
        },
        responsive: [{
          breakpoint: 480,
          options: {
            chart: {
              height: 250
            }
          }
        }]
      }

      this.charts.tugasRealisasi = new ApexCharts(document.querySelector("#tugasRealisasiChart"), options)
      this.charts.tugasRealisasi.render()
    },

    initUjianStatusChart() {
      const options = {
        series: [{
          name: 'Ujian',
          data: []
        }],
        chart: {
          type: 'area',
          height: 300,
          background: 'transparent',
          foreColor: '#ffffff'
        },
        colors: ['#06b6d4'],
        xaxis: {
          categories: [],
          labels: {
            style: {
              colors: '#ffffff'
            }
          }
        },
        yaxis: {
          labels: {
            style: {
              colors: '#ffffff'
            }
          }
        },
        grid: {
          borderColor: 'rgba(255, 255, 255, 0.1)'
        },
        fill: {
          type: 'gradient',
          gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.7,
            opacityTo: 0.3,
            stops: [0, 90, 100]
          }
        },
        responsive: [{
          breakpoint: 480,
          options: {
            chart: {
              height: 250
            }
          }
        }]
      }

      this.charts.ujianStatus = new ApexCharts(document.querySelector("#ujianStatusChart"), options)
      this.charts.ujianStatus.render()
    },

    initActivityChart() {
      const options = {
        series: [{
          name: 'Aktivitas',
          data: []
        }],
        chart: {
          type: 'line',
          height: 300,
          background: 'transparent',
          foreColor: '#ffffff'
        },
        colors: ['#ec4899'],
        xaxis: {
          categories: [],
          labels: {
            style: {
              colors: '#ffffff'
            }
          }
        },
        yaxis: {
          labels: {
            style: {
              colors: '#ffffff'
            }
          }
        },
        grid: {
          borderColor: 'rgba(255, 255, 255, 0.1)'
        },
        stroke: {
          curve: 'smooth',
          width: 3
        },
        markers: {
          size: 6,
          colors: ['#ec4899']
        },
        responsive: [{
          breakpoint: 480,
          options: {
            chart: {
              height: 250
            }
          }
        }]
      }

      this.charts.activity = new ApexCharts(document.querySelector("#activityChart"), options)
      this.charts.activity.render()
    },

    loadInitialData() {
      if (this.initialData) {
        this.updateAllCharts(this.initialData)
      }
    },

    updateChartData() {
      // Fetch data berdasarkan kelas yang dipilih
      this.fetchChartData()
    },

    updateActivityData() {
      // Fetch data aktivitas berdasarkan periode
      this.fetchActivityData()
    },

    updateAllCharts(data) {
      // Update Tugas Completion Chart
      if (data.tugasCompletion) {
        this.charts.tugasCompletion.updateSeries(data.tugasCompletion.series)
        this.charts.tugasCompletion.updateOptions({
          labels: data.tugasCompletion.labels
        })
      }

      // Update Tugas Count Chart
      if (data.tugasCount) {
        this.charts.tugasCount.updateSeries([{
          data: data.tugasCount.series
        }])
        this.charts.tugasCount.updateOptions({
          xaxis: {
            categories: data.tugasCount.labels
          }
        })
      }

      // Update Tugas Realisasi Chart
      if (data.tugasRealisasi) {
        this.charts.tugasRealisasi.updateSeries(data.tugasRealisasi.series)
        this.charts.tugasRealisasi.updateOptions({
          xaxis: {
            categories: data.tugasRealisasi.labels
          }
        })
      }

      // Update Ujian Status Chart
      if (data.ujianStatus) {
        this.charts.ujianStatus.updateSeries([{
          data: data.ujianStatus.series
        }])
        this.charts.ujianStatus.updateOptions({
          xaxis: {
            categories: data.ujianStatus.labels
          }
        })
      }

      // Update Activity Chart
      if (data.activity) {
        this.charts.activity.updateSeries([{
          data: data.activity.series
        }])
        this.charts.activity.updateOptions({
          xaxis: {
            categories: data.activity.labels
          }
        })
      }
    },

    fetchChartData() {
      // Implementasi fetch data berdasarkan kelas
      console.log('Fetching data for class:', this.selectedClass)
    },

    fetchActivityData() {
      // Implementasi fetch data aktivitas
      console.log('Fetching activity data for period:', this.activityPeriod)
    }
  },

  beforeUnmount() {
    // Cleanup charts
    Object.values(this.charts).forEach(chart => {
      if (chart) {
        chart.destroy()
      }
    })
  }
}
</script>

<style scoped>
.dashboard-charts {
  padding: 1.5rem;
  background: transparent;
}

.charts-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1.5rem;
  margin-bottom: 1.5rem;
}

.chart-container {
  background: rgba(255, 255, 255, 0.05);
  backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 1rem;
  padding: 1.5rem;
  transition: all 0.3s ease;
}

.chart-container:hover {
  background: rgba(255, 255, 255, 0.08);
  transform: translateY(-2px);
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
}

.chart-container.full-width {
  grid-column: 1 / -1;
}

.chart-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.chart-title {
  font-size: 1.125rem;
  font-weight: 600;
  color: #ffffff;
  margin: 0;
}

.chart-actions {
  display: flex;
  gap: 0.5rem;
}

.chart-select {
  background: rgba(255, 255, 255, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 0.5rem;
  padding: 0.5rem 0.75rem;
  color: #ffffff;
  font-size: 0.875rem;
}

.chart-select:focus {
  outline: none;
  border-color: #8b5cf6;
  box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
}

.chart-wrapper {
  position: relative;
}

.chart {
  width: 100%;
}

@media (max-width: 768px) {
  .charts-row {
    grid-template-columns: 1fr;
  }
  
  .chart-container {
    padding: 1rem;
  }
  
  .chart-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 0.5rem;
  }
}
</style>
