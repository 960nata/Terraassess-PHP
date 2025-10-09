@extends('layouts.unified-layout')

@section('title', 'Bantuan Admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Bantuan Admin</h1>
        <p class="text-gray-600 dark:text-gray-400">Panduan dan dukungan untuk Administrator Terra Assessment</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- FAQ -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Pertanyaan yang Sering Diajukan</h2>
                
                <div class="space-y-4">
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg">
                        <button class="w-full px-4 py-3 text-left flex justify-between items-center hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <span class="font-medium text-gray-900 dark:text-white">Bagaimana cara menambahkan pengguna baru?</span>
                            <i class="fas fa-chevron-down text-gray-500"></i>
                        </button>
                        <div class="px-4 pb-3 text-sm text-gray-600 dark:text-gray-400">
                            Untuk menambahkan pengguna baru, buka menu "Manajemen Pengguna" dan klik tombol "Tambah Pengguna". Isi formulir yang tersedia dan pastikan role pengguna sudah sesuai.
                        </div>
                    </div>

                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg">
                        <button class="w-full px-4 py-3 text-left flex justify-between items-center hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <span class="font-medium text-gray-900 dark:text-white">Bagaimana cara mengatur izin akses?</span>
                            <i class="fas fa-chevron-down text-gray-500"></i>
                        </button>
                        <div class="px-4 pb-3 text-sm text-gray-600 dark:text-gray-400">
                            Izin akses dapat diatur melalui menu "Pengaturan" > "Manajemen Role". Di sini Anda dapat mengatur hak akses untuk setiap role pengguna.
                        </div>
                    </div>

                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg">
                        <button class="w-full px-4 py-3 text-left flex justify-between items-center hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <span class="font-medium text-gray-900 dark:text-white">Bagaimana cara backup data sistem?</span>
                            <i class="fas fa-chevron-down text-gray-500"></i>
                        </button>
                        <div class="px-4 pb-3 text-sm text-gray-600 dark:text-gray-400">
                            Backup data dapat dilakukan melalui menu "Laporan" > "Backup Data". Pilih jenis data yang ingin di-backup dan klik tombol "Generate Backup".
                        </div>
                    </div>

                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg">
                        <button class="w-full px-4 py-3 text-left flex justify-between items-center hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <span class="font-medium text-gray-900 dark:text-white">Bagaimana cara mengatur notifikasi sistem?</span>
                            <i class="fas fa-chevron-down text-gray-500"></i>
                        </button>
                        <div class="px-4 pb-3 text-sm text-gray-600 dark:text-gray-400">
                            Pengaturan notifikasi dapat diakses melalui menu "Pengaturan" > "Notifikasi". Di sini Anda dapat mengatur jenis notifikasi yang akan dikirim.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kontak Dukungan -->
        <div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Kontak Dukungan</h3>
                
                <div class="space-y-4">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg mr-3">
                            <i class="fas fa-envelope text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Email</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">support@terraassessment.com</p>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg mr-3">
                            <i class="fas fa-phone text-green-600 dark:text-green-400"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Telepon</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">+62 21 1234 5678</p>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <div class="p-2 bg-purple-100 dark:bg-purple-900 rounded-lg mr-3">
                            <i class="fas fa-clock text-purple-600 dark:text-purple-400"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Jam Operasional</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Senin - Jumat, 08:00 - 17:00 WIB</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dokumentasi -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Dokumentasi</h3>
                
                <div class="space-y-3">
                    <a href="#" class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                        <i class="fas fa-book text-blue-600 dark:text-blue-400 mr-3"></i>
                        <span class="text-sm text-gray-900 dark:text-white">Panduan Pengguna</span>
                    </a>

                    <a href="#" class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                        <i class="fas fa-video text-green-600 dark:text-green-400 mr-3"></i>
                        <span class="text-sm text-gray-900 dark:text-white">Video Tutorial</span>
                    </a>

                    <a href="#" class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                        <i class="fas fa-download text-purple-600 dark:text-purple-400 mr-3"></i>
                        <span class="text-sm text-gray-900 dark:text-white">Download Manual</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulir Bantuan -->
    <div class="mt-8 bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Kirim Pertanyaan</h2>
        
        <form class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Nama
                    </label>
                    <input type="text" value="{{ $user->name ?? 'Admin' }}" 
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Email
                    </label>
                    <input type="email" value="{{ $user->email ?? 'admin@terraassessment.com' }}" 
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Subjek
                </label>
                <input type="text" placeholder="Masukkan subjek pertanyaan" 
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Pesan
                </label>
                <textarea rows="4" placeholder="Tuliskan pertanyaan atau masalah yang Anda hadapi" 
                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"></textarea>
            </div>

            <div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                    Kirim Pertanyaan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
