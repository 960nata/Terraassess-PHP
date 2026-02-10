<div class="bg-gray-800 rounded-xl shadow-2xl overflow-hidden border border-gray-700 mb-6 transition-all duration-300 hover:shadow-purple-900/20">
    <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-4 border-b border-gray-600 flex justify-between items-center">
        <div>
            <h3 class="text-xl font-bold text-white flex items-center">
                <i class="fas fa-list-ul mr-2"></i>
                Soal Pilihan Ganda
            </h3>
            <p class="text-purple-100 text-sm mt-1">Edit soal yang sudah ada atau tambah soal baru</p>
        </div>
        <button type="button" onclick="addQuestion()" class="px-4 py-2 bg-white text-purple-600 rounded-lg text-sm font-semibold hover:bg-gray-100 transition duration-200">
            <i class="fas fa-plus mr-1"></i> Tambah Soal
        </button>
    </div>
    
    <div class="p-6">
        <div id="questionsContainer" class="space-y-6">
            @foreach($tugas->TugasMultiple as $index => $question)
                <div class="bg-gray-750 rounded-xl border border-gray-600 p-6 transition-all duration-200 hover:border-purple-500" id="question-{{ $index + 1 }}">
                    <div class="flex justify-between items-center mb-6 border-b border-gray-700 pb-4">
                        <h4 class="text-lg font-bold text-white flex items-center">
                            <span class="bg-purple-600 text-white w-8 h-8 rounded-full flex items-center justify-center text-sm mr-3">{{ $index + 1 }}</span>
                            Soal #{{ $index + 1 }}
                        </h4>
                        <div class="flex items-center space-x-3">
                            <div class="flex items-center bg-gray-700 rounded-lg px-3 py-1">
                                <span class="text-gray-300 text-sm mr-2">Poin:</span>
                                <input type="number" name="questions[{{ $index + 1 }}][points]" 
                                       class="w-16 bg-transparent border-none text-white text-right focus:ring-0 p-0" 
                                       value="{{ old('questions.' . ($index + 1) . '.points', $question->poin) }}" min="1" required>
                            </div>
                            <button type="button" onclick="removeQuestion({{ $index + 1 }})" class="text-red-400 hover:text-red-300 transition duration-200 p-2 rounded-full hover:bg-red-400/10">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="space-y-6">
                        <!-- Question Editor -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Pertanyaan</label>
                            <div class="bg-gray-900 rounded-lg border border-gray-700 overflow-hidden">
                                <div id="quill-editor-question-{{ $index + 1 }}" class="quill-editor-dark h-32"></div>
                                <textarea name="questions[{{ $index + 1 }}][question]" id="quill-textarea-question-{{ $index + 1 }}" class="hidden" required>{!! old('questions.' . ($index + 1) . '.question', $question->soal) !!}</textarea>
                            </div>
                        </div>
                        
                        <!-- Options -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-3">Pilihan Jawaban</label>
                            <div id="options-{{ $index + 1 }}" class="space-y-4">
                                @php
                                    $options = [
                                        'a' => $question->a,
                                        'b' => $question->b,
                                        'c' => $question->c,
                                        'd' => $question->d,
                                        'e' => $question->e
                                    ];
                                    $correctAnswer = $question->jawaban;
                                @endphp
                                @foreach($options as $key => $value)
                                    @if($value)
                                        <div class="flex items-start space-x-3 group">
                                            <div class="pt-3">
                                                <input type="radio" name="questions[{{ $index + 1 }}][correct_answer]" 
                                                       value="{{ $key }}" {{ old('questions.' . ($index + 1) . '.correct_answer', $correctAnswer) == $key ? 'checked' : '' }} 
                                                       class="w-5 h-5 text-purple-600 bg-gray-700 border-gray-600 focus:ring-purple-500 cursor-pointer" required>
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-center mb-1">
                                                    <span class="text-xs font-semibold text-gray-400 uppercase w-6">{{ $key }}</span>
                                                </div>
                                                <div class="bg-gray-900 rounded-lg border border-gray-700 overflow-hidden group-hover:border-purple-500/50 transition duration-200">
                                                    <div id="quill-editor-option-{{ strtoupper($key) }}-{{ $index + 1 }}" class="quill-editor-dark h-16"></div>
                                                    <textarea name="questions[{{ $index + 1 }}][options][{{ $key }}]" 
                                                              id="quill-textarea-option-{{ strtoupper($key) }}-{{ $index + 1 }}" 
                                                              class="hidden" required>{!! old('questions.' . ($index + 1) . '.options.' . $key, $value) !!}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <button type="button" onclick="addOption({{ $index + 1 }})" class="mt-4 text-sm text-purple-400 hover:text-purple-300 font-medium flex items-center">
                                <i class="fas fa-plus-circle mr-2"></i> Tambah Pilihan
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="mt-6 flex justify-center">
            <button type="button" onclick="addQuestion()" class="group flex items-center px-6 py-3 bg-gray-700 border border-gray-600 text-white rounded-xl hover:bg-gray-600 transition duration-200">
                <div class="w-8 h-8 rounded-full bg-purple-600 flex items-center justify-center mr-3 group-hover:scale-110 transition duration-200">
                    <i class="fas fa-plus text-sm"></i>
                </div>
                <span>Tambah Soal Baru</span>
            </button>
        </div>
    </div>
</div>
