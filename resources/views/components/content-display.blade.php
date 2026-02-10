{{-- Content Display Component for Students --}}
{{-- Menampilkan konten materi, tugas, atau ujian dengan dukungan gambar dan video YouTube --}}

<div class="content-display">
    {!! $content !!}
</div>

<style>
.content-display {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    line-height: 1.6;
    color: #374151;
}

.content-display h1 {
    font-size: 1.875rem;
    font-weight: 700;
    margin: 0 0 1rem 0;
    color: #1e293b;
}

.content-display h2 {
    font-size: 1.5rem;
    font-weight: 600;
    margin: 0 0 0.75rem 0;
    color: #1e293b;
}

.content-display h3 {
    font-size: 1.25rem;
    font-weight: 600;
    margin: 0 0 0.5rem 0;
    color: #1e293b;
}

.content-display p {
    margin: 0 0 0.75rem 0;
    color: #374151;
}

.content-display ul, .content-display ol {
    margin: 0 0 0.75rem 0;
    padding-left: 1.5rem;
}

.content-display li {
    margin: 0.25rem 0;
    color: #374151;
}

.content-display blockquote {
    border-left: 4px solid #3b82f6;
    padding-left: 1rem;
    margin: 0 0 0.75rem 0;
    font-style: italic;
    color: #64748b;
    background: #f8fafc;
    padding: 1rem;
    border-radius: 0 8px 8px 0;
}

.content-display code {
    background: #f1f5f9;
    padding: 0.125rem 0.25rem;
    border-radius: 0.25rem;
    font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
    font-size: 0.875rem;
    color: #e11d48;
}

.content-display pre {
    background: #f8fafc;
    padding: 1rem;
    border-radius: 0.5rem;
    overflow-x: auto;
    margin: 0 0 0.75rem 0;
    border: 1px solid #e2e8f0;
}

.content-display pre code {
    background: none;
    padding: 0;
    color: #374151;
}

.content-display img {
    max-width: 100%;
    height: auto;
    border-radius: 0.5rem;
    margin: 0.5rem 0;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    display: block;
}

.content-display a {
    color: #3b82f6;
    text-decoration: underline;
}

.content-display a:hover {
    color: #1d4ed8;
}

/* YouTube video styling */
.content-display .youtube-video-container {
    position: relative;
    width: 100%;
    height: 0;
    padding-bottom: 56.25%; /* 16:9 aspect ratio */
    margin: 1rem 0;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    background: #000;
}

.content-display .youtube-video-container iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border: none;
}

/* Video placeholder for loading */
.content-display .youtube-placeholder {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: #f3f4f6;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6b7280;
    font-size: 14px;
    border: 2px dashed #d1d5db;
}

.content-display .youtube-placeholder i {
    font-size: 24px;
    margin-right: 8px;
}

/* Responsive design */
@media (max-width: 768px) {
    .content-display h1 {
        font-size: 1.5rem;
    }
    
    .content-display h2 {
        font-size: 1.25rem;
    }
    
    .content-display h3 {
        font-size: 1.125rem;
    }
    
    .content-display .youtube-video-container {
        margin: 0.5rem 0;
    }
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .content-display {
        color: #e5e7eb;
    }
    
    .content-display h1, .content-display h2, .content-display h3 {
        color: #f9fafb;
    }
    
    .content-display p, .content-display li {
        color: #d1d5db;
    }
    
    .content-display blockquote {
        background: #374151;
        color: #9ca3af;
    }
    
    .content-display code {
        background: #374151;
        color: #fbbf24;
    }
    
    .content-display pre {
        background: #374151;
        border-color: #4b5563;
    }
    
    .content-display pre code {
        color: #e5e7eb;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Lazy load YouTube videos for better performance
    const youtubeContainers = document.querySelectorAll('.youtube-video-container');
    
    youtubeContainers.forEach(container => {
        const iframe = container.querySelector('iframe');
        if (iframe) {
            // Add loading placeholder
            const placeholder = document.createElement('div');
            placeholder.className = 'youtube-placeholder';
            placeholder.innerHTML = '<i class="fab fa-youtube"></i>Loading video...';
            container.appendChild(placeholder);
            
            // Load video when user scrolls to it
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        iframe.style.display = 'block';
                        placeholder.style.display = 'none';
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1 });
            
            observer.observe(container);
        }
    });
    
    // Add click-to-play functionality for images
    const images = document.querySelectorAll('.content-display img');
    images.forEach(img => {
        img.style.cursor = 'pointer';
        img.addEventListener('click', function() {
            // Create modal for image viewing
            const modal = document.createElement('div');
            modal.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.9);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 1000;
                cursor: pointer;
            `;
            
            const modalImg = document.createElement('img');
            modalImg.src = this.src;
            modalImg.style.cssText = `
                max-width: 90%;
                max-height: 90%;
                border-radius: 8px;
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            `;
            
            modal.appendChild(modalImg);
            document.body.appendChild(modal);
            
            modal.addEventListener('click', () => {
                document.body.removeChild(modal);
            });
        });
    });
});
</script>
