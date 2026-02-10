<div class="space-y-6">
    <!-- Group Task Configuration -->
    <div class="bg-gray-800 rounded-xl shadow-2xl overflow-hidden border border-gray-700 transition-all duration-300 hover:shadow-green-900/20">
        <div class="bg-gradient-to-r from-green-600 to-teal-600 px-6 py-4 border-b border-gray-600">
            <h3 class="text-xl font-bold text-white flex items-center">
                <i class="fas fa-users-cog mr-2"></i>
                Konfigurasi Tugas Kelompok
            </h3>
            <p class="text-green-100 text-sm mt-1">Edit pengaturan kelompok dan penilaian rekan sebaya</p>
        </div>
        
        <div class="p-6 space-y-6">
            @php
                $taskConfig = json_decode($tugas->content, true);
                if (!$taskConfig) {
                    $taskConfig = [];
                }
            @endphp
            
            <!-- Peer Assessment Due Date -->
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">
                    Tanggal Tenggat Penilaian Antar Kelompok
                </label>
                <div class="relative">
                    <input type="datetime-local" name="peer_assessment_due" 
                           class="w-full pl-10 pr-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200" 
                           value="{{ old('peer_assessment_due', $taskConfig['peer_assessment_due'] ?? '') }}">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-3 text-gray-400">
                        <i class="far fa-calendar-check"></i>
                    </div>
                </div>
                <p class="text-gray-400 text-xs mt-1">Batas waktu bagi siswa untuk menilai kelompok lain.</p>
            </div>
            
            <!-- Existing Groups List -->
            <div>
                <h4 class="text-lg font-bold text-white mb-3 flex items-center">
                    <i class="fas fa-users mr-2 text-green-400"></i>
                    Kelompok Terdaftar
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($tugas->TugasKelompok as $group)
                        <div class="bg-gray-750 rounded-xl border border-gray-600 p-4 hover:border-green-500 transition duration-200">
                            <div class="flex justify-between items-center mb-3">
                                <h5 class="text-white font-bold truncate pr-2" title="{{ $group->name }}">{{ $group->name }}</h5>
                                <span class="bg-gray-700 text-xs px-2 py-1 rounded-full text-gray-300">
                                    {{ $group->AnggotaTugasKelompok->count() }} anggota
                                </span>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                @foreach($group->AnggotaTugasKelompok as $member)
                                    <span class="inline-flex items-center px-2 py-1 bg-gray-700 rounded text-xs text-white border border-gray-600">
                                        @if($member->is_leader)
                                            <i class="fas fa-crown text-yellow-400 mr-1 text-xs"></i>
                                        @endif
                                        {{ $member->User->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Rubric Configuration -->
            <div>
                <div class="flex justify-between items-center mb-4">
                    <h4 class="text-lg font-bold text-white flex items-center">
                        <i class="fas fa-clipboard-list mr-2 text-green-400"></i>
                        Rubrik Penilaian
                    </h4>
                    <button type="button" onclick="addRubricItem()" class="px-3 py-1 bg-green-600 hover:bg-green-500 text-white rounded-lg text-sm font-semibold transition duration-200">
                        <i class="fas fa-plus mr-1"></i> Tambah Item
                    </button>
                </div>
                
                <div id="rubricContainer" class="space-y-4">
                    @if(isset($taskConfig['rubric_items']))
                        @foreach($taskConfig['rubric_items'] as $index => $item)
                            <div class="bg-gray-750 rounded-xl border border-gray-600 p-4 transition-all duration-200 hover:border-green-500" id="rubric-{{ $index + 1 }}">
                                <div class="flex justify-between items-center mb-4 border-b border-gray-700 pb-2">
                                    <h5 class="text-white font-medium">Item Penilaian #{{ $index + 1 }}</h5>
                                    <button type="button" onclick="removeRubricItem({{ $index + 1 }})" class="text-red-400 hover:text-red-300 transition duration-200">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-medium text-gray-400 mb-1">Kriteria Penilaian</label>
                                        <input type="text" name="rubric_items[{{ $index + 1 }}][item]" 
                                               class="w-full px-3 py-2 bg-gray-900 border border-gray-700 text-white rounded-lg focus:ring-1 focus:ring-green-500 transition duration-200" 
                                               value="{{ old('rubric_items.' . ($index + 1) . '.item', $item['item']) }}" placeholder="Contoh: Kerjasama Tim" required>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-xs font-medium text-gray-400 mb-1">Tipe Jawaban</label>
                                        <select name="rubric_items[{{ $index + 1 }}][type]" 
                                                class="w-full px-3 py-2 bg-gray-900 border border-gray-700 text-white rounded-lg focus:ring-1 focus:ring-green-500 transition duration-200" 
                                                onchange="updateRubricType({{ $index + 1 }})" required>
                                            <option value="yes_no" {{ old('rubric_items.' . ($index + 1) . '.type', $item['type']) == 'yes_no' ? 'selected' : '' }}>Ya/Tidak</option>
                                            <option value="scale" {{ old('rubric_items.' . ($index + 1) . '.type', $item['type']) == 'scale' ? 'selected' : '' }}>Skala (Sgt Baik - Kurang)</option>
                                            <option value="text" {{ old('rubric_items.' . ($index + 1) . '.type', $item['type']) == 'text' ? 'selected' : '' }}>Teks Bebas</option>
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-xs font-medium text-gray-400 mb-1">Poin Maksimal</label>
                                        <input type="number" name="rubric_items[{{ $index + 1 }}][points]" 
                                               class="w-full px-3 py-2 bg-gray-900 border border-gray-700 text-white rounded-lg focus:ring-1 focus:ring-green-500 transition duration-200" 
                                               value="{{ old('rubric_items.' . ($index + 1) . '.points', $item['points']) }}" min="0" required>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                
                <div class="mt-4 flex justify-center">
                    <button type="button" onclick="addRubricItem()" class="text-green-400 hover:text-green-300 text-sm font-medium flex items-center">
                        <i class="fas fa-plus-circle mr-2"></i> Tambah Item Kriteria baru
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
