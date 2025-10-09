# Cara Menangani Null Timestamp di Laravel Blade

## Masalah
Error "Call to a member function format() on null" terjadi ketika kita mencoba memanggil method `format()` pada field timestamp yang bernilai `null`.

## Solusi

### 1. Menggunakan Null Coalescing Operator (??)
```php
{{ $data->timestamp ? $data->timestamp->format('d/m/Y H:i') : '-' }}
```

### 2. Menggunakan Blade Directive @if
```php
@if($data->timestamp)
    {{ $data->timestamp->format('d/m/Y H:i') }}
@else
    -
@endif
```

### 3. Menggunakan Optional Helper
```php
{{ optional($data->timestamp)->format('d/m/Y H:i') ?? '-' }}
```

### 4. Menggunakan Null Coalescing Assignment
```php
{{ $data->timestamp?->format('d/m/Y H:i') ?? '-' }}
```

## Contoh Implementasi yang Benar

### Di Tabel Data
```html
<table>
    <thead>
        <tr>
            <th>Nama</th>
            <th>Waktu</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td>{{ $item->timestamp ? $item->timestamp->format('d/m/Y H:i') : '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
```

### Di Card atau Widget
```html
<div class="card">
    <h3>Data Terakhir</h3>
    <p>Waktu: {{ $data->timestamp ? $data->timestamp->format('d M Y, H:i') : 'Belum ada data' }}</p>
</div>
```

### Di Form Input
```html
<input type="datetime-local" 
       value="{{ $data->timestamp ? $data->timestamp->format('Y-m-d\TH:i') : '' }}">
```

## Model Eloquent - Casting Timestamp

Untuk memastikan field timestamp selalu berupa Carbon instance, tambahkan di model:

```php
class IotReading extends Model
{
    protected $casts = [
        'timestamp' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
```

## Migration - Default Value

Untuk field timestamp yang opsional, gunakan nullable:

```php
Schema::create('iot_sensor_data', function (Blueprint $table) {
    $table->id();
    $table->timestamp('recorded_at')->nullable();
    $table->timestamps();
});
```

## Best Practices

1. **Selalu cek null** sebelum memanggil method pada timestamp
2. **Gunakan null coalescing operator** untuk kode yang lebih bersih
3. **Definisikan default value** yang sesuai dengan konteks
4. **Gunakan Carbon casting** di model untuk konsistensi
5. **Test dengan data null** untuk memastikan tidak ada error

## Contoh Lengkap

```html
<!-- ❌ SALAH - Akan error jika timestamp null -->
<td>{{ $data->timestamp->format('d/m/Y H:i') }}</td>

<!-- ✅ BENAR - Aman dari null -->
<td>{{ $data->timestamp ? $data->timestamp->format('d/m/Y H:i') : '-' }}</td>

<!-- ✅ ALTERNATIF - Menggunakan optional helper -->
<td>{{ optional($data->timestamp)->format('d/m/Y H:i') ?? '-' }}</td>

<!-- ✅ ALTERNATIF - Menggunakan null safe operator (PHP 8+) -->
<td>{{ $data->timestamp?->format('d/m/Y H:i') ?? '-' }}</td>
```
