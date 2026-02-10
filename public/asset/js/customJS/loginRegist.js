// Lottie animation disabled - file data.json not found
// You can add a simple CSS animation instead
document.addEventListener('DOMContentLoaded', function() {
    // Add simple CSS animation to anim containers
    const anim1 = document.getElementById('anim');
    const anim2 = document.getElementById('anim2');
    
    if (anim1) {
        anim1.innerHTML = '<div class="space-animation-placeholder"><i class="fas fa-rocket fa-3x text-primary"></i><br><small class="text-white-75">TerraAssessment IoT System</small></div>';
    }
    
    if (anim2) {
        anim2.innerHTML = '<div class="space-animation-placeholder"><i class="fas fa-microchip fa-3x text-info"></i><br><small class="text-white-75">Smart Learning</small></div>';
    }
    
    // Add form validation and enhancement
    const loginForm = document.querySelector('form[action*="authenticate"]');
    if (loginForm) {
        // Add loading state to submit button
        loginForm.addEventListener('submit', function(e) {
            const submitBtn = loginForm.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Launching...';
                submitBtn.disabled = true;
            }
        });
        
        // Add focus effects to form inputs
        const inputs = loginForm.querySelectorAll('.form-control');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });
            
            input.addEventListener('blur', function() {
                if (!this.value) {
                    this.parentElement.classList.remove('focused');
                }
            });
        });
    }
    
    // Add smooth scroll to top when page loads
    window.scrollTo({ top: 0, behavior: 'smooth' });
});
