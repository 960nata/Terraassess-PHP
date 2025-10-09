<div class="access-denied-container">
    <div class="access-denied-content">
        <div class="access-denied-icon">
            <i class="ph-lock"></i>
        </div>
        <h2 class="access-denied-title">Akses Ditolak</h2>
        <p class="access-denied-message">
            Maaf, Anda tidak memiliki izin untuk melakukan operasi ini. 
            Sebagai guru, Anda hanya dapat melihat data tetapi tidak dapat membuat, mengedit, atau menghapus data master.
        </p>
        <div class="access-denied-actions">
            <a href="{{ route('dashboard') }}" class="btn btn-primary">
                <i class="ph-house"></i>
                Kembali ke Dashboard
            </a>
        </div>
    </div>
</div>

<style>
.access-denied-container {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 60vh;
    padding: 2rem;
}

.access-denied-content {
    text-align: center;
    max-width: 500px;
    padding: 2rem;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.access-denied-icon {
    font-size: 4rem;
    color: #ef4444;
    margin-bottom: 1rem;
}

.access-denied-title {
    color: #1f2937;
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 1rem;
}

.access-denied-message {
    color: #6b7280;
    font-size: 1rem;
    line-height: 1.6;
    margin-bottom: 2rem;
}

.access-denied-actions .btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background: #3b82f6;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 500;
    transition: background-color 0.2s;
}

.access-denied-actions .btn:hover {
    background: #2563eb;
}
</style>
