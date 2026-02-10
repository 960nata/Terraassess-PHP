<div class="student-work-content">
    <div class="mb-4">
        <h4 class="text-lg font-medium text-white mb-2">{{ $student->name }}</h4>
        <p class="text-sm text-gray-400">{{ $student->email }}</p>
    </div>

    @if($progress)
        <div class="space-y-4">
            <!-- Status Information -->
            <div class="bg-gray-800 rounded-lg p-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-gray-400">Status</label>
                        <div class="text-white font-medium">
                            {{ ucfirst(str_replace('_', ' ', $progress->status)) }}
                        </div>
                    </div>
                    <div>
                        <label class="text-sm text-gray-400">Progres</label>
                        <div class="text-white font-medium">{{ $progress->progress_percentage }}%</div>
                    </div>
                    @if($progress->started_at)
                        <div>
                            <label class="text-sm text-gray-400">Mulai</label>
                            <div class="text-white font-medium">{{ $progress->started_at->format('d M Y, H:i') }}</div>
                        </div>
                    @endif
                    @if($progress->submitted_at)
                        <div>
                            <label class="text-sm text-gray-400">Dikumpulkan</label>
                            <div class="text-white font-medium">{{ $progress->submitted_at->format('d M Y, H:i') }}</div>
                        </div>
                    @endif
                </div>
            </div>

            @if($tugas->tipe == 1)
                <!-- Multiple Choice Results -->
                <div class="bg-gray-800 rounded-lg p-4">
                    <h5 class="text-white font-medium mb-3">Jawaban Pilihan Ganda</h5>
                    @php
                        $answers = $tugas->TugasJawabanMultiple()->where('user_id', $student->id)->get();
                    @endphp
                    
                    @if($answers->count() > 0)
                        <div class="space-y-3">
                            @foreach($answers as $answer)
                                <div class="border border-gray-600 rounded-lg p-3">
                                    <div class="text-sm text-gray-300 mb-2">
                                        <strong>Soal:</strong> {{ $answer->TugasQuiz->question }}
                                    </div>
                                    <div class="text-sm">
                                        <span class="text-gray-400">Jawaban:</span>
                                        <span class="text-white">{{ $answer->TugasMultiple->option }}</span>
                                        @if($answer->TugasMultiple->is_correct)
                                            <span class="text-green-400 ml-2">✓ Benar</span>
                                        @else
                                            <span class="text-red-400 ml-2">✗ Salah</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-400">Belum ada jawaban</p>
                    @endif
                </div>

            @elseif($tugas->tipe == 2 || $tugas->tipe == 3)
                <!-- Essay/Individual Task Results -->
                <div class="bg-gray-800 rounded-lg p-4">
                    <h5 class="text-white font-medium mb-3">Hasil Kerja</h5>
                    
                    @php
                        $taskConfig = json_decode($tugas->content, true);
                        $userFiles = $tugas->TugasFile()->where('user_id', $student->id)->get();
                    @endphp
                    
                    @if($userFiles->count() > 0)
                        <div class="space-y-3">
                            <h6 class="text-gray-300 font-medium">File yang Diupload:</h6>
                            @foreach($userFiles as $file)
                                <div class="flex items-center justify-between bg-gray-700 rounded-lg p-3">
                                    <div class="flex items-center space-x-3">
                                        <i class="ph-file text-blue-400"></i>
                                        <div>
                                            <div class="text-white">{{ $file->filename }}</div>
                                            <div class="text-sm text-gray-400">{{ $file->created_at->format('d M Y, H:i') }}</div>
                                        </div>
                                    </div>
                                    <a href="{{ Storage::url($file->file_path) }}" 
                                       class="btn btn-sm btn-outline" target="_blank">
                                        <i class="ph-download"></i>
                                        Download
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    
                    @if($progress->notes)
                        <div class="mt-4">
                            <h6 class="text-gray-300 font-medium mb-2">Catatan Siswa:</h6>
                            <div class="bg-gray-700 rounded-lg p-3">
                                <p class="text-white">{{ $progress->notes }}</p>
                            </div>
                        </div>
                    @endif
                </div>

            @elseif($tugas->tipe == 4)
                <!-- Group Task Results -->
                <div class="bg-gray-800 rounded-lg p-4">
                    <h5 class="text-white font-medium mb-3">Hasil Kerja Kelompok</h5>
                    
                    @php
                        $group = $tugas->TugasKelompok()->whereHas('AnggotaTugasKelompok', function($query) use ($student) {
                            $query->where('user_id', $student->id);
                        })->first();
                    @endphp
                    
                    @if($group)
                        <div class="space-y-3">
                            <div>
                                <label class="text-sm text-gray-400">Kelompok</label>
                                <div class="text-white font-medium">{{ $group->name }}</div>
                            </div>
                            
                            <div>
                                <label class="text-sm text-gray-400">Anggota Kelompok</label>
                                <div class="flex flex-wrap gap-2 mt-1">
                                    @foreach($group->AnggotaTugasKelompok as $member)
                                        <span class="px-2 py-1 bg-gray-700 rounded text-sm text-white">
                                            {{ $member->User->name }}
                                            @if($member->is_leader)
                                                <i class="ph-crown text-yellow-400 ml-1"></i>
                                            @endif
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            
                            @php
                                $groupFiles = $group->fileKelompok;
                            @endphp
                            
                            @if($groupFiles->count() > 0)
                                <div>
                                    <label class="text-sm text-gray-400">File Kelompok</label>
                                    <div class="space-y-2 mt-1">
                                        @foreach($groupFiles as $file)
                                            <div class="flex items-center justify-between bg-gray-700 rounded-lg p-2">
                                                <div class="flex items-center space-x-2">
                                                    <i class="ph-file text-blue-400"></i>
                                                    <span class="text-white">{{ $file->filename }}</span>
                                                </div>
                                                <a href="{{ Storage::url($file->file_path) }}" 
                                                   class="btn btn-sm btn-outline" target="_blank">
                                                    <i class="ph-download"></i>
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @else
                        <p class="text-gray-400">Siswa tidak terdaftar dalam kelompok</p>
                    @endif
                </div>
            @endif

            <!-- Existing Feedback -->
            @php
                $existingFeedback = $tugas->TugasFeedback()->where('user_id', $student->id)->first();
            @endphp
            
            @if($existingFeedback)
                <div class="bg-gray-800 rounded-lg p-4">
                    <h5 class="text-white font-medium mb-3">Feedback Sebelumnya</h5>
                    <div class="bg-gray-700 rounded-lg p-3">
                        <p class="text-white">{{ $existingFeedback->feedback }}</p>
                        <div class="text-sm text-gray-400 mt-2">
                            Oleh: {{ $existingFeedback->CreatedBy->name ?? 'Sistem' }} - 
                            {{ $existingFeedback->created_at->format('d M Y, H:i') }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @else
        <div class="text-center py-8 text-gray-400">
            <i class="ph-warning text-4xl mb-2"></i>
            <p>Belum ada progres untuk siswa ini</p>
        </div>
    @endif
</div>
