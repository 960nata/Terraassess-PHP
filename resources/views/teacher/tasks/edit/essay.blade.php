<div class="space-y-6">
    <!-- Essay Questions Card -->
    <div class="bg-gray-800 rounded-xl shadow-2xl overflow-hidden border border-gray-700 transition-all duration-300 hover:shadow-blue-900/20">
        <div class="bg-gradient-to-r from-blue-600 to-cyan-600 px-6 py-4 border-b border-gray-600 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-pen-nib mr-2"></i>
                    Soal {{ $tipe == 2 ? 'Essay' : 'Mandiri' }}
                </h3>
                <p class="text-blue-100 text-sm mt-1">Edit soal yang sudah ada atau tambah soal baru</p>
            </div>
            <button type="button" onclick="addEssayQuestion()" class="px-4 py-2 bg-white text-blue-600 rounded-lg text-sm font-semibold hover:bg-gray-100 transition duration-200 shadow-md">
                <i class="fas fa-plus mr-1"></i> Tambah Soal
            </button>
        </div>
        
        <div class="p-6">
            @if($tugas->TugasMandiri->count() == 0)
                <div class="alert alert-info mb-6 p-4 rounded-lg bg-blue-900/50 border border-blue-800 text-blue-200 flex items-start">
                    <i class="fas fa-info-circle mt-1 mr-3 text-xl"></i>
                    <div>
                        <strong class="font-bold block mb-1">Belum ada soal</strong>
                        <p>Tugas ini mungkin dibuat sebelum fitur soal essay ditambahkan. Silakan tambah soal baru dengan klik tombol "Tambah Soal" di atas.</p>
                    </div>
                </div>
            @endif
            
            <div id="essayQuestionsContainer" class="space-y-6">
                @foreach($tugas->TugasMandiri as $index => $question)
                    <div class="bg-gray-750 rounded-xl border border-gray-600 p-6 transition-all duration-200 hover:border-blue-500" id="essay-question-{{ $index + 1 }}">
                        <div class="flex justify-between items-center mb-6 border-b border-gray-700 pb-4">
                            <h4 class="text-lg font-bold text-white flex items-center">
                                <span class="bg-blue-600 text-white w-8 h-8 rounded-full flex items-center justify-center text-sm mr-3">{{ $index + 1 }}</span>
                                Soal #{{ $index + 1 }}
                            </h4>
                            <div class="flex items-center space-x-3">
                                <div class="flex items-center bg-gray-700 rounded-lg px-3 py-1">
                                    <span class="text-gray-300 text-sm mr-2">Poin:</span>
                                    <input type="number" name="essay_questions[{{ $index + 1 }}][points]" 
                                           class="w-16 bg-transparent border-none text-white text-right focus:ring-0 p-0" 
                                           value="{{ old('essay_questions.' . ($index + 1) . '.points', $question->poin) }}" min="1" max="100" required>
                                </div>
                                <button type="button" onclick="removeEssayQuestion({{ $index + 1 }})" class="text-red-400 hover:text-red-300 transition duration-200 p-2 rounded-full hover:bg-red-400/10">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Question Editor -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Pertanyaan</label>
                            <div class="bg-gray-900 rounded-lg border border-gray-700 overflow-hidden">
                                <div id="quill-editor-essay-{{ $index + 1 }}" class="quill-editor-dark h-32"></div>
                                <textarea name="essay_questions[{{ $index + 1 }}][question]" id="quill-textarea-essay-{{ $index + 1 }}" class="hidden" required>{!! old('essay_questions.' . ($index + 1) . '.question', $question->pertanyaan) !!}</textarea>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-8 flex justify-center">
                <button type="button" onclick="addEssayQuestion()" class="group flex items-center px-6 py-3 bg-gray-700 border border-gray-600 text-white rounded-xl hover:bg-gray-600 transition duration-200 shadow-lg hover:shadow-xl">
                    <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center mr-3 group-hover:scale-110 transition duration-200">
                        <i class="fas fa-plus text-sm"></i>
                    </div>
                    <span>Tambah Soal Essay Baru</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Submission Options Card -->
    <div class="bg-gray-800 rounded-xl shadow-2xl overflow-hidden border border-gray-700 transition-all duration-300 hover:shadow-indigo-900/20">
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4 border-b border-gray-600">
            <h3 class="text-xl font-bold text-white flex items-center">
                <i class="fas fa-cog mr-2"></i>
                Opsi Pengumpulan
            </h3>
            <p class="text-indigo-100 text-sm mt-1">Konfigurasi bagaimana siswa mengumpulkan tugas</p>
        </div>
        
        <div class="p-6 space-y-6">
            @php
                $taskConfig = json_decode($tugas->content, true);
                if (!$taskConfig) {
                    $taskConfig = [];
                }
            @endphp
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Text Input Option -->
                <div class="bg-gray-750 p-4 rounded-lg border border-gray-700 hover:border-indigo-500 transition duration-200">
                    <label class="flex items-start space-x-3 cursor-pointer">
                        <div class="flex items-center h-5 mt-1">
                            <input type="checkbox" name="allow_text_input" value="1" 
                                   {{ old('allow_text_input', $taskConfig['allow_text_input'] ?? true) ? 'checked' : '' }} 
                                   class="w-5 h-5 text-indigo-600 bg-gray-700 border-gray-600 rounded focus:ring-indigo-500 focus:ring-offset-gray-800">
                        </div>
                        <div>
                            <span class="block text-white font-medium">Izinkan ketik langsung</span>
                            <span class="block text-gray-400 text-sm mt-1">Siswa dapat mengetik jawaban langsung di editor teks.</span>
                        </div>
                    </label>
                </div>

                <!-- File Upload Option -->
                <div class="bg-gray-750 p-4 rounded-lg border border-gray-700 hover:border-indigo-500 transition duration-200">
                    <label class="flex items-start space-x-3 cursor-pointer">
                        <div class="flex items-center h-5 mt-1">
                            <input type="checkbox" name="allow_file_upload" value="1" 
                                   {{ old('allow_file_upload', $taskConfig['allow_file_upload'] ?? false) ? 'checked' : '' }} 
                                   class="w-5 h-5 text-indigo-600 bg-gray-700 border-gray-600 rounded focus:ring-indigo-500 focus:ring-offset-gray-800">
                        </div>
                        <div>
                            <span class="block text-white font-medium">Izinkan upload file</span>
                            <span class="block text-gray-400 text-sm mt-1">Siswa dapat mengunggah dokumen (PDF, Word, Gambar).</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- File Types Configuration -->
            <div id="fileTypesContainer" class="{{ old('allow_file_upload', $taskConfig['allow_file_upload'] ?? false) ? '' : 'hidden' }} mt-6 animate-fade-in-down">
                <div class="bg-indigo-900/30 border border-indigo-800 rounded-lg p-4">
                    <label class="block text-sm font-medium text-indigo-200 mb-3">
                        Jenis File yang Diizinkan
                    </label>
                    <div class="flex flex-wrap gap-3">
                        @foreach(['pdf', 'docx', 'jpg', 'png', 'txt'] as $type)
                            <label class="inline-flex items-center px-3 py-2 rounded-lg border border-gray-600 bg-gray-700 hover:bg-gray-600 cursor-pointer transition duration-200">
                                <input type="checkbox" name="file_types[]" value="{{ $type }}" 
                                       {{ in_array($type, old('file_types', $taskConfig['file_types'] ?? ['pdf', 'docx', 'jpg', 'png'])) ? 'checked' : '' }}
                                       class="w-4 h-4 text-indigo-500 bg-gray-800 border-gray-500 rounded focus:ring-indigo-400">
                                <span class="ml-2 text-white font-medium uppercase text-sm">.{{ $type }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
