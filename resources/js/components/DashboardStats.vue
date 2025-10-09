<template>
  <div class="space-stats-grid">
    <!-- Total Siswa -->
    <div class="space-stat-card space-fade-in">
      <div class="flex items-center justify-between mb-4">
        <div class="space-stat-icon">
          <i class="ph-student text-2xl"></i>
        </div>
        <div class="text-right">
          <div class="space-stat-value">{{ stats.totalSiswa || 0 }}</div>
          <div class="space-stat-label">Total Siswa</div>
        </div>
      </div>
      
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
          <i class="ph-trend-up text-green-400 text-sm"></i>
          <span class="text-green-400 text-sm font-medium">{{ stats.siswaGrowth || 0 }}%</span>
        </div>
        <div class="text-xs text-gray-400">vs last month</div>
      </div>
    </div>

    <!-- Total Pengajar -->
    <div class="space-stat-card space-fade-in">
      <div class="flex items-center justify-between mb-4">
        <div class="space-stat-icon">
          <i class="ph-chalkboard-teacher text-2xl"></i>
        </div>
        <div class="text-right">
          <div class="space-stat-value">{{ stats.totalPengajar || 0 }}</div>
          <div class="space-stat-label">Total Pengajar</div>
        </div>
      </div>
      
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
          <i class="ph-trend-up text-green-400 text-sm"></i>
          <span class="text-green-400 text-sm font-medium">{{ stats.pengajarGrowth || 0 }}%</span>
        </div>
        <div class="text-xs text-gray-400">vs last month</div>
      </div>
    </div>

    <!-- Total Kelas -->
    <div class="space-stat-card space-fade-in">
      <div class="flex items-center justify-between mb-4">
        <div class="space-stat-icon">
          <i class="ph-school text-2xl"></i>
        </div>
        <div class="text-right">
          <div class="space-stat-value">{{ stats.totalKelas || 0 }}</div>
          <div class="space-stat-label">Total Kelas</div>
        </div>
      </div>
      
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
          <i class="ph-trend-up text-green-400 text-sm"></i>
          <span class="text-green-400 text-sm font-medium">{{ stats.kelasGrowth || 0 }}%</span>
        </div>
        <div class="text-xs text-gray-400">vs last month</div>
      </div>
    </div>

    <!-- Total Mapel -->
    <div class="space-stat-card space-fade-in">
      <div class="flex items-center justify-between mb-4">
        <div class="space-stat-icon">
          <i class="ph-book text-2xl"></i>
        </div>
        <div class="text-right">
          <div class="space-stat-value">{{ stats.totalMapel || 0 }}</div>
          <div class="space-stat-label">Total Mapel</div>
        </div>
      </div>
      
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
          <i class="ph-trend-up text-green-400 text-sm"></i>
          <span class="text-green-400 text-sm font-medium">{{ stats.mapelGrowth || 0 }}%</span>
        </div>
        <div class="text-xs text-gray-400">vs last month</div>
      </div>
    </div>

    <!-- Total Materi -->
    <div class="space-stat-card space-fade-in">
      <div class="flex items-center justify-between mb-4">
        <div class="space-stat-icon">
          <i class="ph-file-text text-2xl"></i>
        </div>
        <div class="text-right">
          <div class="space-stat-value">{{ stats.totalMateri || 0 }}</div>
          <div class="space-stat-label">Total Materi</div>
        </div>
      </div>
      
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
          <i class="ph-trend-up text-green-400 text-sm"></i>
          <span class="text-green-400 text-sm font-medium">{{ stats.materiGrowth || 0 }}%</span>
        </div>
        <div class="text-xs text-gray-400">vs last month</div>
      </div>
    </div>

    <!-- Total Tugas -->
    <div class="space-stat-card space-fade-in">
      <div class="flex items-center justify-between mb-4">
        <div class="space-stat-icon">
          <i class="ph-clipboard-text text-2xl"></i>
        </div>
        <div class="text-right">
          <div class="space-stat-value">{{ stats.totalTugas || 0 }}</div>
          <div class="space-stat-label">Total Tugas</div>
        </div>
      </div>
      
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
          <i class="ph-trend-up text-green-400 text-sm"></i>
          <span class="text-green-400 text-sm font-medium">{{ stats.tugasGrowth || 0 }}%</span>
        </div>
        <div class="text-xs text-gray-400">vs last month</div>
      </div>
    </div>

    <!-- Total Ujian -->
    <div class="space-stat-card space-fade-in">
      <div class="flex items-center justify-between mb-4">
        <div class="space-stat-icon">
          <i class="ph-exam text-2xl"></i>
        </div>
        <div class="text-right">
          <div class="space-stat-value">{{ stats.totalUjian || 0 }}</div>
          <div class="space-stat-label">Total Ujian</div>
        </div>
      </div>
      
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
          <i class="ph-trend-up text-green-400 text-sm"></i>
          <span class="text-green-400 text-sm font-medium">{{ stats.ujianGrowth || 0 }}%</span>
        </div>
        <div class="text-xs text-gray-400">vs last month</div>
      </div>
    </div>

    <!-- Active Users -->
    <div class="space-stat-card space-fade-in">
      <div class="flex items-center justify-between mb-4">
        <div class="space-stat-icon">
          <i class="ph-user-check text-2xl"></i>
        </div>
        <div class="text-right">
          <div class="space-stat-value">{{ stats.activeUsers || 0 }}</div>
          <div class="space-stat-label">Pengguna Aktif</div>
        </div>
      </div>
      
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
          <i class="ph-trend-up text-green-400 text-sm"></i>
          <span class="text-green-400 text-sm font-medium">{{ stats.activeUsersGrowth || 0 }}%</span>
        </div>
        <div class="text-xs text-gray-400">vs last month</div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'DashboardStats',
  props: {
    stats: {
      type: Object,
      required: true
    }
  },
  mounted() {
    this.animateNumbers();
  },
  methods: {
    animateNumbers() {
      const numbers = this.$el.querySelectorAll('.stat-number');
      numbers.forEach(number => {
        const target = parseInt(number.textContent);
        let current = 0;
        const increment = target / 50;
        const timer = setInterval(() => {
          current += increment;
          if (current >= target) {
            current = target;
            clearInterval(timer);
          }
          number.textContent = Math.floor(current);
        }, 20);
      });
    }
  }
}
</script>

<style scoped>
/* Space theme styles are handled by space-minimal.css */
/* This component now uses Tailwind classes and space theme CSS */
</style>
