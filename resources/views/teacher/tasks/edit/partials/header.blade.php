<div class="bg-gray-800 rounded-xl shadow-2xl overflow-hidden border border-gray-700 mb-6 transition-all duration-300 hover:shadow-blue-900/20">
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4 border-b border-gray-600">
        <h3 class="text-xl font-bold text-white flex items-center">
            <i class="fas fa-info-circle mr-2"></i>
            Informasi Dasar
        </h3>
        <p class="text-blue-100 text-sm mt-1">Atur informasi dasar mengenai tugas ini</p>
    </div>
    
    <div class="p-6 space-y-6">
        <!-- Judul Tugas -->
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Judul Tugas <span class="text-red-400">*</span>
            </label>
            <input type="text" name="name" 
                   class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 @error('name') border-red-500 @enderror" 
                   value="{{ old('name', $tugas->name) }}" 
                   placeholder="Masukkan judul tugas" required>
            @error('name')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Kelas & Mata Pelajaran -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">
                    Kelas Tujuan <span class="text-red-400">*</span>
                </label>
                <div class="relative">
                    <select name="kelas_id" 
                            class="w-full pl-4 pr-10 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent appearance-none transition duration-200 @error('kelas_id') border-red-500 @enderror" 
                            required>
                        <option value="">Pilih Kelas</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}" 
                                    {{ (old('kelas_id') ?? ($tugas->KelasMapel->kelas_id ?? null)) == $k->id ? 'selected' : '' }}>
                                {{ $k->name }} - {{ $k->level }}
                            </option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                        <i class="fas fa-chevron-down text-xs"></i>
                    </div>
                </div>
                @error('kelas_id')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">
                    Mata Pelajaran <span class="text-red-400">*</span>
                </label>
                <div class="relative">
                    <select name="mapel_id" 
                            class="w-full pl-4 pr-10 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent appearance-none transition duration-200 @error('mapel_id') border-red-500 @enderror" 
                            required>
                        <option value="">Pilih Mata Pelajaran</option>
                        @foreach($mapel as $m)
                            <option value="{{ $m->id }}" 
                                    {{ (old('mapel_id') ?? ($tugas->KelasMapel->mapel_id ?? null)) == $m->id ? 'selected' : '' }}>
                                {{ $m->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                        <i class="fas fa-chevron-down text-xs"></i>
                    </div>
                </div>
                @error('mapel_id')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Deskripsi / Instruksi -->
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Deskripsi/Instruksi <span class="text-red-400">*</span>
            </label>
            <textarea name="content" class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 @error('content') border-red-500 @enderror" rows="4" 
                      placeholder="Tuliskan instruksi yang jelas untuk siswa..." required>{{ old('content', $tugas->content) }}</textarea>
            @error('content')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Tanggal Tenggat -->
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Tanggal Tenggat
            </label>
            <div class="relative">
                <input type="datetime-local" name="due" 
                       class="w-full pl-10 pr-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 @error('due') border-red-500 @enderror" 
                       value="{{ old('due', $tugas->due ? $tugas->due->format('Y-m-d\TH:i') : '') }}">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-3 text-gray-400">
                    <i class="far fa-calendar-alt"></i>
                </div>
            </div>
            @error('due')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        {{-- DEBUG INFO (Hidden by default, can be toggled via JS if needed for dev) --}}
        <div class="hidden">
            <strong>DEBUG INFO:</strong><br>
            Kelas Mapel ID: {{ $tugas->kelas_mapel_id }}<br>
            Kelas ID: {{ $tugas->KelasMapel->kelas_id ?? 'NULL' }}<br>
            Mapel ID: {{ $tugas->KelasMapel->mapel_id ?? 'NULL' }}<br>
        </div>
    </div>
</div>
