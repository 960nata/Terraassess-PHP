@if(session('access_denied'))
    <div class="alert alert-danger alert-dismissible fade show position-fixed" 
         style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;" 
         role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>Akses Ditolak!</strong> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <script>
        // Auto hide after 5 seconds
        setTimeout(function() {
            const alert = document.querySelector('.alert-danger');
            if (alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 5000);
    </script>
@endif
