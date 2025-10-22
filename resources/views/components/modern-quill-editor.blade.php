{{-- Modern Quill Editor Component - Clean Version --}}
<div class="modern-quill-editor" data-editor-id="{{ $editorId ?? 'modern-editor' }}">
    <!-- Editor Container with Built-in Toolbar -->
    <div id="{{ $editorId ?? 'modern-editor' }}" class="quill-editor" 
         data-min-height="{{ $height ?? '300px' }}" 
         data-max-height="{{ $maxHeight ?? '600px' }}">
        {!! $content ?? '' !!}
    </div>

    <!-- Hidden Input -->
    <textarea name="{{ $name ?? 'content' }}" id="{{ $name ?? 'content' }}_input" 
              class="d-none" required="{{ $required ?? false }}"></textarea>

    <!-- Word Count -->
    <div class="editor-footer">
        <div class="word-count">
            <span class="word-count-text">Words: <span class="word-count-number">0</span></span>
            <span class="char-count-text">Characters: <span class="char-count-number">0</span></span>
        </div>
        <div class="auto-save-status">
            <span class="auto-save-indicator"></span>
        </div>
        <div class="editor-actions">
            <button type="button" class="btn-save-draft" title="Save Draft">
                <i class="fas fa-save"></i>
            </button>
            <button type="button" class="btn-fullscreen" title="Toggle Fullscreen">
                <i class="fas fa-expand"></i>
            </button>
        </div>
    </div>
</div>

<style>
.modern-quill-editor {
    border: 2px solid rgba(51, 65, 85, 0.5);
    border-radius: 12px;
    overflow: hidden;
    background: rgba(30, 41, 59, 0.8);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
    transition: all 0.3s ease;
}

.modern-quill-editor:focus-within {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.quill-editor {
    background: rgba(15, 23, 42, 0.8);
    color: #e2e8f0;
    border: none;
    outline: none;
    padding: 16px;
    font-size: 14px;
    line-height: 1.6;
    resize: vertical;
    overflow-y: auto;
}

.quill-editor::-webkit-scrollbar {
    width: 8px;
}

.quill-editor::-webkit-scrollbar-track {
    background: rgba(51, 65, 85, 0.3);
    border-radius: 4px;
}

.quill-editor::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

.quill-editor::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

.editor-footer {
    background: rgba(15, 23, 42, 0.8);
    border-top: 1px solid rgba(51, 65, 85, 0.5);
    padding: 12px 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
}

.word-count {
    display: flex;
    gap: 16px;
    font-size: 12px;
    color: #94a3b8;
}

.word-count-text, .char-count-text {
    font-weight: 500;
}

.word-count-number, .char-count-number {
    color: #e2e8f0;
    font-weight: 600;
}

.auto-save-status {
    display: flex;
    align-items: center;
    gap: 8px;
}

.auto-save-indicator {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #10b981;
    animation: pulse 2s infinite;
}

.auto-save-indicator.saving {
    background: #f59e0b;
    animation: spin 1s linear infinite;
}

.auto-save-indicator.error {
    background: #ef4444;
    animation: none;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.editor-actions {
    display: flex;
    gap: 8px;
}

.btn-save-draft, .btn-fullscreen {
    background: rgba(51, 65, 85, 0.5);
    border: 1px solid rgba(51, 65, 85, 0.7);
    color: #e2e8f0;
    padding: 6px 10px;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 12px;
    display: flex;
    align-items: center;
    gap: 4px;
}

.btn-save-draft:hover, .btn-fullscreen:hover {
    background: rgba(59, 130, 246, 0.2);
    border-color: #3b82f6;
    color: #3b82f6;
    transform: translateY(-1px);
}

.btn-save-draft:active, .btn-fullscreen:active {
    transform: translateY(0);
}

.d-none {
    display: none !important;
}

/* Dark Mode Support - Always Active for Superadmin */

/* Quill Dark Mode Styling */
.ql-toolbar {
    background: #1e293b !important;
    border-color: rgba(51, 65, 85, 0.5) !important;
}

.ql-toolbar .ql-picker-options {
    background: #1e293b !important;
    border-color: rgba(51, 65, 85, 0.5) !important;
}

.ql-toolbar .ql-picker-item {
    color: #e2e8f0 !important;
}

.ql-toolbar .ql-picker-item:hover {
    background: rgba(51, 65, 85, 0.5) !important;
}

.ql-toolbar .ql-tooltip {
    background: #1e293b !important;
    color: #ffffff !important;
    border-color: rgba(51, 65, 85, 0.5) !important;
}

.ql-toolbar .ql-tooltip::before {
    border-top-color: #1e293b !important;
}

/* Quill Editor Content Styling */
.ql-editor {
    background: rgba(15, 23, 42, 0.8) !important;
    color: #e2e8f0 !important;
    font-size: 14px !important;
    line-height: 1.6 !important;
    padding: 16px !important;
}

.ql-editor.ql-blank::before {
    color: #94a3b8 !important;
    font-style: normal !important;
}

.ql-editor h1, .ql-editor h2, .ql-editor h3 {
    color: #e2e8f0 !important;
    margin-top: 0 !important;
    margin-bottom: 0.5em !important;
}

.ql-editor h1 {
    font-size: 1.5em !important;
    font-weight: 600 !important;
}

.ql-editor h2 {
    font-size: 1.3em !important;
    font-weight: 600 !important;
}

.ql-editor h3 {
    font-size: 1.1em !important;
    font-weight: 600 !important;
}

.ql-editor p {
    margin-bottom: 1em !important;
    color: #e2e8f0 !important;
}

.ql-editor blockquote {
    border-left: 4px solid #3b82f6 !important;
    background: rgba(59, 130, 246, 0.1) !important;
    color: #e2e8f0 !important;
    padding: 8px 16px !important;
    margin: 16px 0 !important;
}

.ql-editor code {
    background: rgba(51, 65, 85, 0.5) !important;
    color: #fbbf24 !important;
    padding: 2px 6px !important;
    border-radius: 4px !important;
    font-family: 'Courier New', monospace !important;
}

.ql-editor pre {
    background: rgba(51, 65, 85, 0.5) !important;
    color: #e2e8f0 !important;
    padding: 16px !important;
    border-radius: 8px !important;
    overflow-x: auto !important;
    margin: 16px 0 !important;
}

.ql-editor pre code {
    background: none !important;
    padding: 0 !important;
    color: #e2e8f0 !important;
}

.ql-editor ul, .ql-editor ol {
    padding-left: 1.5em !important;
    margin-bottom: 1em !important;
}

.ql-editor li {
    color: #e2e8f0 !important;
    margin-bottom: 0.25em !important;
}

.ql-editor a {
    color: #3b82f6 !important;
    text-decoration: underline !important;
}

.ql-editor a:hover {
    color: #60a5fa !important;
}

.ql-editor img {
    max-width: 100% !important;
    height: auto !important;
    border-radius: 8px !important;
    margin: 8px 0 !important;
}

.ql-editor table {
    border-collapse: collapse !important;
    width: 100% !important;
    margin: 16px 0 !important;
    background: rgba(15, 23, 42, 0.8) !important;
}

.ql-editor table td, .ql-editor table th {
    border: 1px solid rgba(51, 65, 85, 0.5) !important;
    padding: 8px 12px !important;
    color: #e2e8f0 !important;
}

.ql-editor table th {
    background: rgba(51, 65, 85, 0.3) !important;
    font-weight: 600 !important;
}

/* Responsive Design */
@media (max-width: 768px) {
    .modern-quill-editor {
        border-radius: 8px;
    }
    
    .quill-editor {
        padding: 12px;
        font-size: 13px;
    }
    
    .editor-footer {
        padding: 8px 12px;
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
    
    .word-count {
        gap: 12px;
        font-size: 11px;
    }
    
    .editor-actions {
        width: 100%;
        justify-content: flex-end;
    }
    
    .btn-save-draft, .btn-fullscreen {
        padding: 4px 8px;
        font-size: 11px;
    }
}

@media (max-width: 480px) {
    .modern-quill-editor {
        border-radius: 6px;
    }
    
    .quill-editor {
        padding: 8px;
        font-size: 12px;
    }
    
    .editor-footer {
        padding: 6px 8px;
    }
    
    .word-count {
        gap: 8px;
        font-size: 10px;
    }
    
    .btn-save-draft, .btn-fullscreen {
        padding: 3px 6px;
        font-size: 10px;
    }
}
</style>

@php
    $editorId = $editorId ?? 'modern-editor';
    $inputName = $name ?? 'content';
@endphp

<script>
(function() {
    'use strict';
    
    const editorId = '{{ $editorId }}';
    const inputName = '{{ $inputName }}';
    
    // Check if this editor is already initialized
    if (window.quillEditors && window.quillEditors[editorId]) {
        return;
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        const inputElement = document.getElementById(inputName + '_input');
        const editorContainer = document.querySelector(`[data-editor-id="${editorId}"]`);
        
        if (!inputElement || !editorContainer) {
            console.warn(`Quill editor elements not found for ${editorId}`);
            return;
        }
        
        // Initialize Quill editor
        const quill = new Quill('#' + editorId, {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'align': [] }],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'indent': '-1'}, { 'indent': '+1' }],
                    ['blockquote', 'code-block'],
                    ['link', 'image'],
                    ['clean']
                ],
                history: {
                    delay: 2000,
                    maxStack: 500,
                    userOnly: true
                },
                clipboard: {
                    matchVisual: false,
                }
            },
            placeholder: '{{ $placeholder ?? "Tuliskan konten di sini..." }}'
        });
        
        // Store editor instance globally
        if (!window.quillEditors) {
            window.quillEditors = {};
        }
        window.quillEditors[editorId] = quill;
        
        // Add image upload handler with compression
        const quillToolbar = quill.getModule('toolbar');
        quillToolbar.addHandler('image', function() {
            selectLocalImage();
        });
    
    // Fungsi untuk kompres gambar base64
    function compressImage(base64Str, maxWidth = 800, quality = 0.7) {
        return new Promise((resolve) => {
            const img = new Image();
            img.onload = function() {
                const canvas = document.createElement('canvas');
                let width = img.width;
                let height = img.height;
                
                // Resize jika lebih besar dari maxWidth
                if (width > maxWidth) {
                    height = (height * maxWidth) / width;
                    width = maxWidth;
                }
                
                canvas.width = width;
                canvas.height = height;
                
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, width, height);
                
                // Kompres dengan quality
                const compressedBase64 = canvas.toDataURL('image/jpeg', quality);
                resolve(compressedBase64);
            };
            img.src = base64Str;
        });
    }
    
    // Image upload handler with compression
    function selectLocalImage() {
        const input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/*');
        input.click();

        input.onchange = async function() {
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = async function(e) {
                    const base64 = e.target.result;
                    
                    try {
                        // Kompres gambar
                        const compressed = await compressImage(base64, 800, 0.7);
                        
                        // Insert ke Quill
                        const range = quill.getSelection();
                        quill.insertEmbed(range.index, 'image', compressed);
                    } catch (error) {
                        console.error('Error compressing image:', error);
                        // Fallback: insert original image
                        const range = quill.getSelection();
                        quill.insertEmbed(range.index, 'image', base64);
                    }
                };
                reader.readAsDataURL(file);
            }
        };
    }
    
    // Set initial content
    const initialContent = `{!! $content ?? '' !!}`;
    if (initialContent) {
        quill.root.innerHTML = initialContent;
        inputElement.value = initialContent;
        updateWordCount();
    }
    
    // Update hidden input on content change
    quill.on('text-change', function() {
        const content = quill.root.innerHTML;
        inputElement.value = content;
        updateWordCount();
        autoSave();
    });
    
    // Word count function
    function updateWordCount() {
        const text = quill.getText();
        const words = text.trim().split(/\s+/).filter(word => word.length > 0).length;
        const characters = text.length;
        
        const wordCountElement = editorContainer.querySelector('.word-count-number');
        const charCountElement = editorContainer.querySelector('.char-count-number');
        
        if (wordCountElement) wordCountElement.textContent = words;
        if (charCountElement) charCountElement.textContent = characters;
    }
    
    // Auto-save function
    function autoSave() {
        const content = quill.root.innerHTML;
        localStorage.setItem(`draft_${inputName}`, content);
        
        const indicator = editorContainer.querySelector('.auto-save-indicator');
        if (indicator) {
            indicator.classList.add('saving');
            setTimeout(() => {
                indicator.classList.remove('saving');
            }, 1000);
        }
    }
    
    // Load draft on page load
    const savedDraft = localStorage.getItem(`draft_${inputName}`);
    if (savedDraft && !inputElement.value) {
        quill.root.innerHTML = savedDraft;
        inputElement.value = savedDraft;
        updateWordCount();
    }
    
    // Set initial value for form validation
    if (inputElement.value) {
        quill.root.innerHTML = inputElement.value;
        updateWordCount();
    }
    
        // Initialize word count
        updateWordCount();
        
        // Save draft button
        const saveDraftBtn = editorContainer.querySelector('.btn-save-draft');
        if (saveDraftBtn) {
            saveDraftBtn.addEventListener('click', function() {
                autoSave();
                this.style.background = '#10b981';
                this.style.borderColor = '#10b981';
                this.style.color = '#ffffff';
                setTimeout(() => {
                    this.style.background = '';
                    this.style.borderColor = '';
                    this.style.color = '';
                }, 2000);
            });
        }
        
        // Fullscreen button
        const fullscreenBtn = editorContainer.querySelector('.btn-fullscreen');
        if (fullscreenBtn) {
            fullscreenBtn.addEventListener('click', function() {
                editorContainer.classList.toggle('fullscreen');
                const icon = this.querySelector('i');
                if (editorContainer.classList.contains('fullscreen')) {
                    icon.className = 'fas fa-compress';
                    this.title = 'Exit Fullscreen';
                } else {
                    icon.className = 'fas fa-expand';
                    this.title = 'Toggle Fullscreen';
                }
            });
        }
    });
})();
</script>