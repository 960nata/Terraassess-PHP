/**
 * Grading Helper JavaScript
 * Fungsi-fungsi untuk membantu proses penilaian guru
 */

// Global variables
let gradingDrafts = {};
let autoSaveInterval;

/**
 * Insert quick comment ke textarea berdasarkan index
 * @param {number} index - Index siswa dalam form
 * @param {string} comment - Komentar yang akan diinsert
 */
function insertComment(index, comment) {
    const textarea = document.querySelector(`textarea[name="komentar[]"]:nth-of-type(${index + 1})`);
    if (textarea) {
        textarea.value = comment;
        textarea.style.borderColor = '#28a745';
        textarea.style.borderWidth = '2px';
        
        // Visual feedback
        setTimeout(() => {
            textarea.style.borderColor = '';
            textarea.style.borderWidth = '';
        }, 1000);
        
        autoSaveGrading();
        showToast('Komentar berhasil diinsert!', 'success');
    }
}

/**
 * Auto-save progress ke localStorage
 * @param {string} tugasId - ID tugas (optional, akan diambil dari URL)
 */
function autoSaveGrading(tugasId = null) {
    if (!tugasId) {
        // Extract tugas ID from URL or form action
        const form = document.getElementById('gradingForm') || document.querySelector('form[action*="siswaUpdateNilai"]');
        if (form) {
            const action = form.getAttribute('action');
            const match = action.match(/token=([^&]+)/);
            if (match) {
                tugasId = match[1];
            }
        }
    }
    
    if (!tugasId) return;
    
    const formData = {};
    const nilaiInputs = document.querySelectorAll('input[name="nilai[]"]');
    const komentarTextareas = document.querySelectorAll('textarea[name="komentar[]"]');
    
    nilaiInputs.forEach((input, index) => {
        formData[index] = {
            nilai: input.value,
            komentar: komentarTextareas[index] ? komentarTextareas[index].value : ''
        };
    });
    
    localStorage.setItem(`grading_draft_${tugasId}`, JSON.stringify(formData));
    gradingDrafts[tugasId] = formData;
    
    // Show auto-save status
    showAutoSaveStatus();
}

/**
 * Load saved progress dari localStorage
 * @param {string} tugasId - ID tugas
 */
function loadGradingDraft(tugasId = null) {
    if (!tugasId) {
        const form = document.getElementById('gradingForm') || document.querySelector('form[action*="siswaUpdateNilai"]');
        if (form) {
            const action = form.getAttribute('action');
            const match = action.match(/token=([^&]+)/);
            if (match) {
                tugasId = match[1];
            }
        }
    }
    
    if (!tugasId) return;
    
    const saved = localStorage.getItem(`grading_draft_${tugasId}`);
    if (saved) {
        try {
            const data = JSON.parse(saved);
            
            document.querySelectorAll('input[name="nilai[]"]').forEach((input, index) => {
                if (data[index] && data[index].nilai) {
                    input.value = data[index].nilai;
                }
            });
            
            document.querySelectorAll('textarea[name="komentar[]"]').forEach((textarea, index) => {
                if (data[index] && data[index].komentar) {
                    textarea.value = data[index].komentar;
                }
            });
            
            showToast('Draft berhasil dimuat!', 'info');
        } catch (error) {
            console.error('Error loading draft:', error);
            showToast('Error loading draft', 'error');
        }
    } else {
        showToast('Tidak ada draft tersimpan', 'warning');
    }
}

/**
 * Quick grade function untuk memberikan nilai dan komentar sekaligus
 * @param {number} index - Index siswa
 * @param {number} nilai - Nilai yang akan diberikan
 * @param {string} komentar - Komentar yang akan diberikan
 */
function quickGrade(index, nilai, komentar) {
    const nilaiInput = document.querySelectorAll('input[name="nilai[]"]')[index];
    const komentarTextarea = document.querySelectorAll('textarea[name="komentar[]"]')[index];
    
    if (nilaiInput) {
        nilaiInput.value = nilai;
        nilaiInput.style.borderColor = '#28a745';
        nilaiInput.style.borderWidth = '2px';
    }
    
    if (komentarTextarea) {
        komentarTextarea.value = komentar;
        komentarTextarea.style.borderColor = '#28a745';
        komentarTextarea.style.borderWidth = '2px';
    }
    
    // Visual feedback
    setTimeout(() => {
        if (nilaiInput) {
            nilaiInput.style.borderColor = '';
            nilaiInput.style.borderWidth = '';
        }
        if (komentarTextarea) {
            komentarTextarea.style.borderColor = '';
            komentarTextarea.style.borderWidth = '';
        }
    }, 1000);
    
    autoSaveGrading();
    showToast(`Quick grade: ${nilai} - ${komentar}`, 'success');
}

/**
 * Batch grading untuk memberikan nilai yang sama ke beberapa siswa
 * @param {Array} indices - Array index siswa yang akan dinilai
 * @param {number} nilai - Nilai yang akan diberikan
 * @param {string} komentar - Komentar yang akan diberikan
 */
function batchGrade(indices, nilai, komentar) {
    indices.forEach(index => {
        quickGrade(index, nilai, komentar);
    });
    
    showToast(`Batch grading: ${indices.length} siswa dinilai`, 'success');
}

/**
 * Validasi form sebelum submit
 * @returns {boolean} - True jika valid
 */
function validateGradingForm() {
    const nilaiInputs = document.querySelectorAll('input[name="nilai[]"]');
    const komentarTextareas = document.querySelectorAll('textarea[name="komentar[]"]');
    let isValid = true;
    let errorMessages = [];
    
    nilaiInputs.forEach((input, index) => {
        const nilai = parseInt(input.value);
        if (input.value && (nilai < 0 || nilai > 100)) {
            isValid = false;
            errorMessages.push(`Nilai siswa ${index + 1} harus antara 0-100`);
            input.style.borderColor = '#dc3545';
        } else {
            input.style.borderColor = '';
        }
    });
    
    if (!isValid) {
        showToast('Validasi gagal: ' + errorMessages.join(', '), 'error');
    }
    
    return isValid;
}

/**
 * Show auto-save status
 */
function showAutoSaveStatus() {
    let statusElement = document.getElementById('autoSaveStatus');
    if (!statusElement) {
        statusElement = document.createElement('div');
        statusElement.id = 'autoSaveStatus';
        statusElement.className = 'alert alert-success mt-2';
        statusElement.style.position = 'fixed';
        statusElement.style.top = '20px';
        statusElement.style.right = '20px';
        statusElement.style.zIndex = '9999';
        statusElement.style.minWidth = '250px';
        document.body.appendChild(statusElement);
    }
    
    statusElement.textContent = '✅ Progress tersimpan otomatis';
    statusElement.style.display = 'block';
    
    setTimeout(() => {
        statusElement.style.display = 'none';
    }, 2000);
}

/**
 * Show toast notification
 * @param {string} message - Pesan yang akan ditampilkan
 * @param {string} type - Tipe toast (success, error, warning, info)
 */
function showToast(message, type = 'info') {
    const toastContainer = document.getElementById('toastContainer') || createToastContainer();
    
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type === 'error' ? 'danger' : type} border-0`;
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">${message}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    toastContainer.appendChild(toast);
    
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
    
    // Remove toast element after it's hidden
    toast.addEventListener('hidden.bs.toast', () => {
        toast.remove();
    });
}

/**
 * Create toast container if it doesn't exist
 * @returns {HTMLElement} - Toast container element
 */
function createToastContainer() {
    const container = document.createElement('div');
    container.id = 'toastContainer';
    container.className = 'toast-container position-fixed top-0 end-0 p-3';
    container.style.zIndex = '9999';
    document.body.appendChild(container);
    return container;
}

/**
 * Calculate grading statistics
 * @returns {Object} - Statistics object
 */
function calculateGradingStats() {
    const nilaiInputs = document.querySelectorAll('input[name="nilai[]"]');
    const nilai = [];
    
    nilaiInputs.forEach(input => {
        if (input.value) {
            nilai.push(parseInt(input.value));
        }
    });
    
    if (nilai.length === 0) {
        return { total: 0, average: 0, graded: 0, pending: 0 };
    }
    
    const total = nilai.length;
    const average = nilai.reduce((sum, n) => sum + n, 0) / total;
    const graded = nilai.filter(n => n > 0).length;
    const pending = total - graded;
    
    return { total, average: average.toFixed(1), graded, pending };
}

/**
 * Show grading statistics
 */
function showGradingStats() {
    const stats = calculateGradingStats();
    const message = `
        Total Siswa: ${stats.total}
        Sudah Dinilai: ${stats.graded}
        Belum Dinilai: ${stats.pending}
        Rata-rata: ${stats.average}
    `;
    
    showToast(message, 'info');
}

/**
 * Initialize grading helper
 */
function initGradingHelper() {
    // Auto-save every 30 seconds
    autoSaveInterval = setInterval(() => {
        autoSaveGrading();
    }, 30000);
    
    // Check for saved draft on page load
    const form = document.getElementById('gradingForm') || document.querySelector('form[action*="siswaUpdateNilai"]');
    if (form) {
        const action = form.getAttribute('action');
        const match = action.match(/token=([^&]+)/);
        if (match) {
            const tugasId = match[1];
            const saved = localStorage.getItem(`grading_draft_${tugasId}`);
            if (saved) {
                showToast('📂 Draft tersimpan tersedia - klik "Load Draft" untuk memuat', 'warning');
            }
        }
    }
    
    // Add form validation on submit
    const gradingForm = document.getElementById('gradingForm') || document.querySelector('form[action*="siswaUpdateNilai"]');
    if (gradingForm) {
        gradingForm.addEventListener('submit', function(e) {
            if (!validateGradingForm()) {
                e.preventDefault();
                return false;
            }
            
            // Clear saved draft after successful submission
            const action = this.getAttribute('action');
            const match = action.match(/token=([^&]+)/);
            if (match) {
                localStorage.removeItem(`grading_draft_${match[1]}`);
            }
        });
    }
    
    // Add keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl+S untuk save draft
        if (e.ctrlKey && e.key === 's') {
            e.preventDefault();
            autoSaveGrading();
            showToast('Draft tersimpan!', 'success');
        }
        
        // Ctrl+L untuk load draft
        if (e.ctrlKey && e.key === 'l') {
            e.preventDefault();
            loadGradingDraft();
        }
    });
}

/**
 * Cleanup function
 */
function cleanupGradingHelper() {
    if (autoSaveInterval) {
        clearInterval(autoSaveInterval);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', initGradingHelper);

// Cleanup when page is unloaded
window.addEventListener('beforeunload', cleanupGradingHelper);

// Export functions for global use
window.gradingHelper = {
    insertComment,
    loadGradingDraft,
    quickGrade,
    batchGrade,
    validateGradingForm,
    showGradingStats,
    calculateGradingStats,
    autoSaveGrading
};
