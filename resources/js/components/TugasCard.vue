<template>
  <div class="tugas-card" :class="{ 'tugas-overdue': isOverdue, 'tugas-urgent': isUrgent }">
    <div class="tugas-header">
      <div class="tugas-title">
        <h5 class="mb-1">{{ tugas.judul }}</h5>
        <small class="text-muted">{{ tugas.kelas_mapel?.mapel?.name }} - {{ tugas.kelas_mapel?.kelas?.name }}</small>
      </div>
      <div class="tugas-actions">
        <button 
          v-if="canEdit" 
          @click="editTugas" 
          class="btn btn-sm btn-outline-primary me-2"
        >
          <i class="fas fa-edit"></i>
        </button>
        <button 
          v-if="canDelete" 
          @click="deleteTugas" 
          class="btn btn-sm btn-outline-danger"
        >
          <i class="fas fa-trash"></i>
        </button>
      </div>
    </div>
    
    <div class="tugas-body">
      <p class="tugas-description">{{ truncateText(tugas.deskripsi, 100) }}</p>
      
      <div class="tugas-meta">
        <div class="meta-item">
          <i class="fas fa-calendar-alt text-primary"></i>
          <span>{{ formatDate(tugas.deadline) }}</span>
        </div>
        <div class="meta-item">
          <i class="fas fa-tag text-info"></i>
          <span class="badge bg-info">{{ getTipeTugasLabel(tugas.tipe_tugas) }}</span>
        </div>
        <div class="meta-item" v-if="tugas.tugas_files?.length">
          <i class="fas fa-paperclip text-secondary"></i>
          <span>{{ tugas.tugas_files.length }} file</span>
        </div>
      </div>
      
      <div class="tugas-progress" v-if="tugas.submission_status">
        <div class="progress mb-2">
          <div 
            class="progress-bar" 
            :class="getProgressBarClass(tugas.submission_status)"
            :style="{ width: getProgressWidth(tugas.submission_status) }"
          ></div>
        </div>
        <small class="text-muted">{{ getProgressText(tugas.submission_status) }}</small>
      </div>
    </div>
    
    <div class="tugas-footer">
      <div class="tugas-author">
        <i class="fas fa-user"></i>
        <span>{{ tugas.user?.name }}</span>
      </div>
      <div class="tugas-time">
        <i class="fas fa-clock"></i>
        <span>{{ getTimeRemaining(tugas.deadline) }}</span>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'TugasCard',
  props: {
    tugas: {
      type: Object,
      required: true
    },
    canEdit: {
      type: Boolean,
      default: false
    },
    canDelete: {
      type: Boolean,
      default: false
    }
  },
  computed: {
    isOverdue() {
      return new Date(this.tugas.deadline) < new Date() && !this.tugas.submission_status?.submitted;
    },
    isUrgent() {
      const deadline = new Date(this.tugas.deadline);
      const now = new Date();
      const diffHours = (deadline - now) / (1000 * 60 * 60);
      return diffHours <= 24 && diffHours > 0;
    }
  },
  methods: {
    truncateText(text, length) {
      if (!text) return '';
      return text.length > length ? text.substring(0, length) + '...' : text;
    },
    
    formatDate(date) {
      return new Date(date).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      });
    },
    
    getTipeTugasLabel(tipe) {
      const labels = {
        'individual': 'Individual',
        'kelompok': 'Kelompok',
        'quiz': 'Quiz',
        'multiple': 'Pilihan Ganda'
      };
      return labels[tipe] || tipe;
    },
    
    getProgressBarClass(status) {
      if (status?.submitted) return 'bg-success';
      if (status?.in_progress) return 'bg-warning';
      return 'bg-secondary';
    },
    
    getProgressWidth(status) {
      if (status?.submitted) return '100%';
      if (status?.in_progress) return '50%';
      return '0%';
    },
    
    getProgressText(status) {
      if (status?.submitted) return 'Sudah dikumpulkan';
      if (status?.in_progress) return 'Sedang dikerjakan';
      return 'Belum dimulai';
    },
    
    getTimeRemaining(deadline) {
      const deadlineDate = new Date(deadline);
      const now = new Date();
      const diff = deadlineDate - now;
      
      if (diff < 0) return 'Terlambat';
      
      const days = Math.floor(diff / (1000 * 60 * 60 * 24));
      const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      
      if (days > 0) return `${days} hari lagi`;
      if (hours > 0) return `${hours} jam lagi`;
      return 'Kurang dari 1 jam';
    },
    
    editTugas() {
      this.$emit('edit', this.tugas);
    },
    
    deleteTugas() {
      if (confirm('Apakah Anda yakin ingin menghapus tugas ini?')) {
        this.$emit('delete', this.tugas);
      }
    }
  }
}
</script>

<style scoped>
.tugas-card {
  background: white;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease;
  border-left: 4px solid #007bff;
  margin-bottom: 1rem;
}

.tugas-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
}

.tugas-card.tugas-overdue {
  border-left-color: #dc3545;
  background: #fff5f5;
}

.tugas-card.tugas-urgent {
  border-left-color: #ffc107;
  background: #fffbf0;
}

.tugas-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  padding: 1rem 1rem 0.5rem;
}

.tugas-title h5 {
  color: #2c3e50;
  font-weight: 600;
  margin: 0;
}

.tugas-actions {
  display: flex;
  gap: 0.5rem;
}

.tugas-body {
  padding: 0 1rem;
}

.tugas-description {
  color: #6c757d;
  margin-bottom: 1rem;
  line-height: 1.5;
}

.tugas-meta {
  display: flex;
  flex-wrap: wrap;
  gap: 1rem;
  margin-bottom: 1rem;
}

.meta-item {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.875rem;
  color: #6c757d;
}

.meta-item i {
  width: 16px;
  text-align: center;
}

.tugas-progress {
  margin-bottom: 1rem;
}

.tugas-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.75rem 1rem;
  background: #f8f9fa;
  border-radius: 0 0 12px 12px;
  font-size: 0.875rem;
  color: #6c757d;
}

.tugas-author,
.tugas-time {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

@media (max-width: 768px) {
  .tugas-header {
    flex-direction: column;
    gap: 0.5rem;
  }
  
  .tugas-meta {
    flex-direction: column;
    gap: 0.5rem;
  }
  
  .tugas-footer {
    flex-direction: column;
    gap: 0.5rem;
    text-align: center;
  }
}
</style>
