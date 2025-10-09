{{-- Rich Text Editor Component using Quill.js --}}
<div class="rich-text-editor-container">
    <div class="editor-toolbar">
        <div class="toolbar-group">
            <button type="button" class="ql-bold" title="Bold"></button>
            <button type="button" class="ql-italic" title="Italic"></button>
            <button type="button" class="ql-underline" title="Underline"></button>
            <button type="button" class="ql-strike" title="Strikethrough"></button>
        </div>
        
        <div class="toolbar-group">
            <button type="button" class="ql-header" value="1" title="Heading 1"></button>
            <button type="button" class="ql-header" value="2" title="Heading 2"></button>
            <button type="button" class="ql-header" value="3" title="Heading 3"></button>
        </div>
        
        <div class="toolbar-group">
            <button type="button" class="ql-list" value="ordered" title="Numbered List"></button>
            <button type="button" class="ql-list" value="bullet" title="Bullet List"></button>
            <button type="button" class="ql-indent" value="-1" title="Decrease Indent"></button>
            <button type="button" class="ql-indent" value="+1" title="Increase Indent"></button>
        </div>
        
        <div class="toolbar-group">
            <button type="button" class="ql-blockquote" title="Quote"></button>
            <button type="button" class="ql-code-block" title="Code Block"></button>
            <button type="button" class="ql-link" title="Insert Link"></button>
        </div>
        
        <div class="toolbar-group">
            <button type="button" class="ql-image" title="Insert Image"></button>
            <button type="button" class="ql-video" title="Insert Video"></button>
            <button type="button" class="ql-formula" title="Insert Formula"></button>
        </div>
        
        <div class="toolbar-group">
            <button type="button" class="ql-clean" title="Clear Formatting"></button>
        </div>
    </div>
    
    <div id="{{ $editorId ?? 'rich-editor' }}" class="editor-content" 
         style="min-height: {{ $height ?? '200px' }}; max-height: {{ $maxHeight ?? '400px' }};">
        {!! $content ?? '' !!}
    </div>
    
    <textarea name="{{ $name ?? 'content' }}" id="{{ $name ?? 'content' }}_input" 
              class="d-none" required="{{ $required ?? false }}"></textarea>
</div>

<style>
.rich-text-editor-container {
    border: 1px solid rgba(51, 65, 85, 0.5);
    border-radius: 8px;
    overflow: hidden;
    background: rgba(30, 41, 59, 0.8);
}

.editor-toolbar {
    background: rgba(15, 23, 42, 0.8);
    border-bottom: 1px solid rgba(51, 65, 85, 0.5);
    padding: 8px 12px;
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    align-items: center;
}

.toolbar-group {
    display: flex;
    gap: 2px;
    padding-right: 8px;
    border-right: 1px solid rgba(51, 65, 85, 0.5);
}

.toolbar-group:last-child {
    border-right: none;
}

.editor-toolbar button {
    background: none;
    border: none;
    padding: 6px 8px;
    border-radius: 4px;
    cursor: pointer;
    color: #94a3b8;
    transition: all 0.2s;
    font-size: 14px;
    min-width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.editor-toolbar button:hover {
    background: rgba(51, 65, 85, 0.5);
    color: #e2e8f0;
}

.editor-toolbar button.ql-active {
    background: #3b82f6;
    color: white;
}

.editor-content {
    padding: 12px;
    outline: none;
    overflow-y: auto;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    line-height: 1.6;
    background: rgba(15, 23, 42, 0.8);
    color: #ffffff;
}

.editor-content h1 {
    font-size: 1.875rem;
    font-weight: 700;
    margin: 0 0 1rem 0;
    color: #ffffff;
}

.editor-content h2 {
    font-size: 1.5rem;
    font-weight: 600;
    margin: 0 0 0.75rem 0;
    color: #ffffff;
}

.editor-content h3 {
    font-size: 1.25rem;
    font-weight: 600;
    margin: 0 0 0.5rem 0;
    color: #ffffff;
}

.editor-content p {
    margin: 0 0 0.75rem 0;
    color: #e2e8f0;
}

.editor-content ul, .editor-content ol {
    margin: 0 0 0.75rem 0;
    padding-left: 1.5rem;
}

.editor-content li {
    margin: 0.25rem 0;
    color: #e2e8f0;
}

.editor-content blockquote {
    border-left: 4px solid #3b82f6;
    padding-left: 1rem;
    margin: 0 0 0.75rem 0;
    font-style: italic;
    color: #94a3b8;
    background: rgba(15, 23, 42, 0.5);
    border-radius: 0 8px 8px 0;
}

.editor-content code {
    background: rgba(15, 23, 42, 0.8);
    padding: 0.125rem 0.25rem;
    border-radius: 0.25rem;
    font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
    font-size: 0.875rem;
    color: #f59e0b;
    border: 1px solid rgba(51, 65, 85, 0.5);
}

.editor-content pre {
    background: rgba(15, 23, 42, 0.8);
    padding: 1rem;
    border-radius: 0.5rem;
    overflow-x: auto;
    margin: 0 0 0.75rem 0;
    border: 1px solid rgba(51, 65, 85, 0.5);
}

.editor-content pre code {
    background: none;
    padding: 0;
    color: #e2e8f0;
}

.editor-content img {
    max-width: 100%;
    height: auto;
    border-radius: 0.5rem;
    margin: 0.5rem 0;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.editor-content a {
    color: #3b82f6;
    text-decoration: underline;
}

.editor-content a:hover {
    color: #1d4ed8;
}

/* Quill specific styles */
.ql-editor {
    min-height: {{ $height ?? '200px' }} !important;
    max-height: {{ $maxHeight ?? '400px' }} !important;
    padding: 12px !important;
    background: rgba(15, 23, 42, 0.8) !important;
    color: #ffffff !important;
}

.ql-toolbar {
    border: none !important;
    border-bottom: 1px solid rgba(51, 65, 85, 0.5) !important;
    background: rgba(30, 41, 59, 0.8) !important;
    padding: 8px 12px !important;
}

.ql-container {
    border: none !important;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
    background: rgba(15, 23, 42, 0.8) !important;
}

.ql-stroke {
    stroke: #94a3b8 !important;
}

.ql-fill {
    fill: #94a3b8 !important;
}

.ql-picker-label {
    color: #94a3b8 !important;
}

.ql-picker-options {
    background: #1e293b !important;
    border-color: rgba(51, 65, 85, 0.5) !important;
}

.ql-picker-item {
    color: #e2e8f0 !important;
}

.ql-picker-item:hover {
    background: rgba(51, 65, 85, 0.5) !important;
}

/* Ensure all toolbar icons are visible */
.editor-toolbar button i {
    color: #ffffff !important;
}

.editor-toolbar button svg {
    fill: #ffffff !important;
    stroke: #ffffff !important;
}

.editor-toolbar .ql-stroke {
    stroke: #ffffff !important;
}

.editor-toolbar .ql-fill {
    fill: #ffffff !important;
}

.editor-toolbar .ql-picker-label {
    color: #ffffff !important;
}

.editor-toolbar .ql-picker-options {
    background: #1e293b !important;
    border-color: rgba(51, 65, 85, 0.5) !important;
}

.editor-toolbar .ql-picker-item {
    color: #e2e8f0 !important;
}

.editor-toolbar .ql-picker-item:hover {
    background: rgba(51, 65, 85, 0.5) !important;
}

.editor-toolbar .ql-tooltip {
    background: #1e293b !important;
    color: #ffffff !important;
    border-color: rgba(51, 65, 85, 0.5) !important;
}

/* Global toolbar element visibility */
.editor-toolbar * {
    color: #ffffff !important;
}

.editor-toolbar svg {
    fill: #ffffff !important;
    stroke: #ffffff !important;
}

.editor-toolbar path {
    fill: #ffffff !important;
    stroke: #ffffff !important;
}

.editor-toolbar circle {
    fill: #ffffff !important;
    stroke: #ffffff !important;
}

.editor-toolbar line {
    stroke: #ffffff !important;
}

.editor-toolbar rect {
    fill: #ffffff !important;
    stroke: #ffffff !important;
}

.ql-snow .ql-tooltip {
    z-index: 1000;
}

/* Image upload progress */
.image-upload-progress {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 8px 16px;
    border-radius: 4px;
    font-size: 14px;
    z-index: 1000;
}

/* YouTube video styling */
.youtube-video-container {
    position: relative;
    width: 100%;
    height: 0;
    padding-bottom: 56.25%; /* 16:9 aspect ratio */
    margin: 1rem 0;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.youtube-video-container iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border: none;
}

.youtube-placeholder {
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

.youtube-placeholder i {
    font-size: 24px;
    margin-right: 8px;
}

/* Responsive */
@media (max-width: 768px) {
    .editor-toolbar {
        padding: 6px 8px;
        gap: 4px;
    }
    
    .toolbar-group {
        padding-right: 4px;
    }
    
    .editor-toolbar button {
        padding: 4px 6px;
        min-width: 28px;
        height: 28px;
        font-size: 12px;
    }
    
    .editor-content {
        padding: 8px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const editorId = '{{ $editorId ?? 'rich-editor' }}';
    const inputName = '{{ $name ?? 'content' }}';
    const inputElement = document.getElementById(inputName + '_input');
    
    // Initialize Quill editor
    const quill = new Quill('#' + editorId, {
        theme: 'snow',
        modules: {
            toolbar: {
                container: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'indent': '-1'}, { 'indent': '+1' }],
                    ['blockquote', 'code-block'],
                    ['link', 'image', 'video', 'formula'],
                    ['clean']
                ],
                handlers: {
                    'image': function() {
                        selectLocalImage();
                    },
                    'video': function() {
                        insertYouTubeVideo();
                    }
                }
            },
            clipboard: {
                matchVisual: false,
            }
        },
        placeholder: '{{ $placeholder ?? "Tuliskan konten di sini..." }}'
    });
    
    // Set initial content
    const initialContent = `{!! $content ?? '' !!}`;
    if (initialContent) {
        quill.root.innerHTML = initialContent;
    }
    
    // Update hidden textarea on content change
    quill.on('text-change', function() {
        const content = quill.root.innerHTML;
        inputElement.value = content;
        
        // Trigger change event for form validation
        inputElement.dispatchEvent(new Event('change', { bubbles: true }));
    });
    
    // Image upload handler
    function selectLocalImage() {
        const input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/png,image/gif,image/jpeg,image/bmp,image/x-icon');
        input.click();
        
        input.onchange = function() {
            const file = input.files[0];
            if (file) {
                // Validate file size (5MB max)
                if (file.size > 5 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar. Maksimal 5MB.');
                    return;
                }
                
                // Validate file type
                const allowedTypes = ['image/png', 'image/gif', 'image/jpeg', 'image/bmp', 'image/x-icon'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Format file tidak didukung. Gunakan PNG, JPG, GIF, BMP, atau ICO.');
                    return;
                }
                
                uploadImage(file);
            }
        };
    }
    
    // Upload image to server
    function uploadImage(file) {
        const formData = new FormData();
        formData.append('image', file);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        
        // Show progress indicator
        const progressDiv = document.createElement('div');
        progressDiv.className = 'image-upload-progress';
        progressDiv.textContent = 'Mengupload gambar...';
        document.body.appendChild(progressDiv);
        
        fetch('{{ route("upload.editor.image") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            document.body.removeChild(progressDiv);
            
            if (data.success) {
                // Insert image into editor
                const range = quill.getSelection();
                quill.insertEmbed(range.index, 'image', data.url);
                quill.setSelection(range.index + 1);
            } else {
                alert('Gagal mengupload gambar: ' + (data.message || 'Error tidak diketahui'));
            }
        })
        .catch(error => {
            document.body.removeChild(progressDiv);
            console.error('Error:', error);
            alert('Gagal mengupload gambar. Silakan coba lagi.');
        });
    }
    
    // YouTube video insertion handler
    function insertYouTubeVideo() {
        const url = prompt('Masukkan URL YouTube (contoh: https://www.youtube.com/watch?v=VIDEO_ID):');
        
        if (url) {
            const videoId = extractYouTubeId(url);
            if (videoId) {
                const embedHtml = createYouTubeEmbed(videoId);
                const range = quill.getSelection();
                quill.clipboard.dangerouslyPasteHTML(range.index, embedHtml);
                quill.setSelection(range.index + 1);
            } else {
                alert('URL YouTube tidak valid. Pastikan URL berformat: https://www.youtube.com/watch?v=VIDEO_ID');
            }
        }
    }
    
    // Extract YouTube video ID from URL
    function extractYouTubeId(url) {
        const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
        const match = url.match(regExp);
        return (match && match[2].length === 11) ? match[2] : null;
    }
    
    // Create YouTube embed HTML
    function createYouTubeEmbed(videoId) {
        return `
            <div class="youtube-video-container">
                <iframe 
                    src="https://www.youtube.com/embed/${videoId}" 
                    title="YouTube video player" 
                    frameborder="0" 
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                    allowfullscreen>
                </iframe>
            </div>
        `;
    }
    
    // Set initial value for form validation
    if (inputElement.value) {
        quill.root.innerHTML = inputElement.value;
    }
});
</script>
