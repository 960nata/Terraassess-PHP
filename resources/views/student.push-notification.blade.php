@extends('layouts.unified-layout')

@section('title', 'Push Notifikasi Student')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Push Notifikasi</h1>
        <p class="text-gray-600 dark:text-gray-400">Kelola notifikasi dan pengumuman untuk siswa</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Notifikasi Terbaru -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Notifikasi Terbaru</h2>
                
                <div class="space-y-4">
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start space-x-3">
                                <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                                    <i class="fas fa-bell text-blue-600 dark:text-blue-400"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-medium text-gray-900 dark:text-white">Tugas Baru Tersedia</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        Tugas Matematika untuk kelas {{ $user->kelas_id ?? 'X' }} telah tersedia. Silakan kerjakan sebelum deadline.
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">2 jam yang lalu</p>
                                </div>
                            </div>
                            <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs rounded-full">
                                Baru
                            </span>
                        </div>
                    </div>

                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start space-x-3">
                                <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                                    <i class="fas fa-check-circle text-green-600 dark:text-green-400"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-medium text-gray-900 dark:text-white">Tugas Disetujui</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        Tugas Fisika Anda telah dinilai dan disetujui oleh guru.
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">1 hari yang lalu</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start space-x-3">
                                <div class="p-2 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                                    <i class="fas fa-exclamation-triangle text-yellow-600 dark:text-yellow-400"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-medium text-gray-900 dark:text-white">Reminder Ujian</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        Ujian Matematika akan dimulai besok pukul 08:00. Pastikan Anda sudah siap.
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">2 hari yang lalu</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <button class="w-full bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium py-2 px-4 rounded-lg transition-colors">
                        Lihat Semua Notifikasi
                    </button>
                </div>
            </div>
        </div>

        <!-- Pengaturan Notifikasi -->
        <div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Pengaturan Notifikasi</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" checked class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Tugas Baru</span>
                        </label>
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" checked class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Reminder Ujian</span>
                        </label>
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Pengumuman Umum</span>
                        </label>
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" checked class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Hasil Penilaian</span>
                        </label>
                    </div>
                </div>

                <div class="mt-6">
                    <button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                        Simpan Pengaturan
                    </button>
                </div>
            </div>

            <!-- Statistik Notifikasi -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Statistik</h3>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Total Notifikasi</span>
                        <span class="font-medium text-gray-900 dark:text-white">24</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Belum Dibaca</span>
                        <span class="font-medium text-blue-600 dark:text-blue-400">3</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Minggu Ini</span>
                        <span class="font-medium text-gray-900 dark:text-white">8</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulir Kirim Notifikasi (untuk testing) -->
    <div class="mt-8 bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Kirim Notifikasi (Testing)</h2>
        
        <form class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Judul Notifikasi
                </label>
                <input type="text" placeholder="Masukkan judul notifikasi" 
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Pesan
                </label>
                <textarea rows="4" placeholder="Tuliskan pesan notifikasi" 
                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Tipe Notifikasi
                </label>
                <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    <option value="info">Informasi</option>
                    <option value="warning">Peringatan</option>
                    <option value="success">Sukses</option>
                    <option value="error">Error</option>
                </select>
            </div>

            <div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                    Kirim Notifikasi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
