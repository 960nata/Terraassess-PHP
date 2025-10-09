@extends('layouts.unified-layout')

@section('title', 'Laporan Admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Laporan Admin</h1>
        <p class="text-gray-600 dark:text-gray-400">Kelola dan lihat laporan sistem Terra Assessment</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Laporan Pengguna -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
                    <i class="fas fa-users text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
                <span class="text-sm text-gray-500 dark:text-gray-400">Total</span>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Laporan Pengguna</h3>
            <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">Lihat statistik dan data pengguna sistem</p>
            <button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                Lihat Laporan
            </button>
        </div>

        <!-- Laporan Tugas -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
                    <i class="fas fa-tasks text-green-600 dark:text-green-400 text-xl"></i>
                </div>
                <span class="text-sm text-gray-500 dark:text-gray-400">Total</span>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Laporan Tugas</h3>
            <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">Analisis performa dan statistik tugas</p>
            <button class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                Lihat Laporan
            </button>
        </div>

        <!-- Laporan Ujian -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-lg">
                    <i class="fas fa-clipboard-check text-purple-600 dark:text-purple-400 text-xl"></i>
                </div>
                <span class="text-sm text-gray-500 dark:text-gray-400">Total</span>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Laporan Ujian</h3>
            <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">Statistik dan hasil ujian siswa</p>
            <button class="w-full bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                Lihat Laporan
            </button>
        </div>
    </div>

    <!-- Tabel Laporan Terbaru -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Laporan Terbaru</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Jenis Laporan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Tanggal Dibuat
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                            Laporan Pengguna Bulanan
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ date('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                Selesai
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="#" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3">Lihat</a>
                            <a href="#" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">Download</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
