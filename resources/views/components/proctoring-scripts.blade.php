@props(['examId' => null])

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const examId = "{{ $examId }}";
        let violationCount = 0;
        const maxViolations = 3;
        let isExamActive = true;

        // Configuration
        const config = {
            blockRightClick: true,
            blockCopyPaste: true,
            requireFullscreen: true,
            trackFocus: true
        };

        // 1. Block Context Menu (Right Click)
        if (config.blockRightClick) {
            document.addEventListener('contextmenu', event => event.preventDefault());
        }

        // 2. Block Copy/Cut/Paste
        if (config.blockCopyPaste) {
            document.addEventListener('copy', e => e.preventDefault());
            document.addEventListener('cut', e => e.preventDefault());
            document.addEventListener('paste', e => e.preventDefault());
            
            // Disable drag and drop
            document.addEventListener('dragstart', e => e.preventDefault());
            document.addEventListener('drop', e => e.preventDefault());
        }

        // 3. Tab Focus & Visibility Detection
        if (config.trackFocus) {
            document.addEventListener('visibilitychange', () => {
                if (document.hidden && isExamActive) {
                    handleViolation('Meninggalkan halaman ujian (Tab Switch)');
                }
            });

            window.addEventListener('blur', () => {
                if (isExamActive) {
                    // Slight delay to prevent false positives from system popups
                    setTimeout(() => {
                        if (document.activeElement === document.body) {
                            // handleViolation('Kehilangan fokus window');
                        }
                    }, 100);
                }
            });
        }

        // 4. Keyboard Shortcuts Prevention (F12, Ctrl+Shift+I, Alt+Tab prevention attempt)
        document.addEventListener('keydown', function(e) {
            // Prevent F12 (DevTools)
            if (e.key === 'F12') {
                e.preventDefault();
            }
            // Prevent Ctrl+Shift+I (DevTools)
            if (e.ctrlKey && e.shiftKey && e.key === 'I') {
                e.preventDefault();
            }
            // Prevent Ctrl+C, Ctrl+V, Ctrl+X
            if (e.ctrlKey && (e.key === 'c' || e.key === 'v' || e.key === 'x')) {
                e.preventDefault();
            }
        });

        // Violation Handler
        function handleViolation(reason) {
            violationCount++;
            const remaining = maxViolations - violationCount;

            // Send violation log to server (Optional/Future)
            console.warn(`Violation detected: ${reason}. Total: ${violationCount}`);

            if (violationCount >= maxViolations) {
                isExamActive = false;
                terminateExam();
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan Pelanggaran!',
                    html: `
                        <p>Anda terdeteksi melakukan: <b>${reason}</b></p>
                        <p class="text-red-500 font-bold">Sisa toleransi: ${remaining} kali</p>
                        <p class="text-sm mt-2">Jika kuota habis, ujian akan otomatis dihentikan dan dianggap selesai.</p>
                    `,
                    confirmButtonText: 'Saya Mengerti',
                    confirmButtonColor: '#d33',
                    allowOutsideClick: false,
                    allowEscapeKey: false
                });
            }
        }

        // Terminate Exam
        function terminateExam() {
            Swal.fire({
                icon: 'error',
                title: 'Ujian Dihentikan',
                text: 'Anda telah melampaui batas toleransi pelanggaran. Jawaban Anda akan dikirim otomatis.',
                confirmButtonText: 'Kirim Jawaban',
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then(() => {
                // Trigger form submission
                const form = document.querySelector('form');
                if (form) {
                    form.submit();
                } else {
                    // Fallback for ajax based exams
                    const submitBtn = document.getElementById('submitBtn');
                    if (submitBtn) submitBtn.click();
                }
            });
        }

        // Fullscreen Enforcement (Optional - requires user interaction first)
        // We add a button overlay if not in fullscreen
        function checkFullscreen() {
            if (config.requireFullscreen && isExamActive) {
                if (!document.fullscreenElement) {
                    Swal.fire({
                        title: 'Mode Layar Penuh Diperlukan',
                        text: 'Ujian ini mewajibkan mode layar penuh. Klik tombol di bawah untuk masuk.',
                        icon: 'info',
                        confirmButtonText: 'Masuk Fullscreen',
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.documentElement.requestFullscreen().catch(e => {
                                console.error('Fullscreen failed', e);
                            });
                        }
                    });
                }
            }
        }

        // Check fullscreen on load and periodically
        setTimeout(checkFullscreen, 1000);
        document.addEventListener('fullscreenchange', checkFullscreen);
    });
</script>
