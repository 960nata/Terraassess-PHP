{{-- Success/Error Messages --}}
@if(session('success'))
<div class="alert alert-success mb-4 p-4 rounded-lg bg-green-500 text-white flex items-center shadow-lg">
    <i class="fas fa-check-circle mr-3 text-xl"></i>
    <div>
        <strong class="font-bold">Berhasil!</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger mb-4 p-4 rounded-lg bg-red-500 text-white flex items-center shadow-lg">
    <i class="fas fa-exclamation-circle mr-3 text-xl"></i>
    <div>
        <strong class="font-bold">Error!</strong>
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
</div>
@endif

@if($errors->any())
<div class="alert alert-danger mb-4 p-4 rounded-lg bg-red-500 text-white shadow-lg">
    <div class="flex items-center mb-2">
        <i class="fas fa-exclamation-triangle mr-3 text-xl"></i>
        <strong class="font-bold">Validasi Gagal!</strong>
    </div>
    <ul class="list-disc list-inside ml-8">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
