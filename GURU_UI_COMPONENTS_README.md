# Guru UI Components - Same as Super Admin

Dokumentasi komponen UI yang digunakan untuk halaman guru, dengan desain yang sama persis dengan super admin dashboard.

## Komponen yang Tersedia

### 1. Layout Components

#### `layouts/guru-layout.blade.php`
Layout utama untuk halaman guru dengan sidebar dan header yang sama dengan super admin.

**Fitur:**
- Sidebar responsif dengan menu yang sama dengan super admin
- Header dengan logo, notifikasi, dan profil pengguna
- Layout yang konsisten di semua halaman

#### `components/page-header.blade.php`
Header halaman dengan breadcrumb dan action buttons.

**Penggunaan:**
```php
@include('components.page-header', [
    'title' => 'Data Pengajar',
    'description' => 'Kelola data pengajar dalam sistem',
    'icon' => 'fas fa-chalkboard-teacher',
    'breadcrumbs' => [
        ['text' => 'Dashboard', 'url' => route('dashboard')],
        ['text' => 'Data Pengajar']
    ],
    'actions' => [
        [
            'text' => 'Tambah Data',
            'icon' => 'fas fa-plus',
            'class' => 'btn-primary',
            'onclick' => 'openCreateModal()'
        ]
    ]
])
```

### 2. Data Display Components

#### `components/stats-grid.blade.php`
Grid statistik dengan kartu yang menampilkan data numerik.

**Penggunaan:**
```php
@include('components/stats-grid', [
    'stats' => [
        [
            'icon' => 'fas fa-users',
            'value' => $totalUsers,
            'label' => 'Total Pengguna'
        ],
        [
            'icon' => 'fas fa-check-circle',
            'value' => $activeUsers,
            'label' => 'Pengguna Aktif'
        ]
    ]
])
```

#### `components/data-table.blade.php`
Tabel data dengan fitur sorting, filtering, dan action buttons.

**Penggunaan:**
```php
@include('components.data-table', [
    'title' => 'Daftar Pengguna',
    'columns' => [
        ['label' => 'Nama', 'class' => 'font-medium'],
        ['label' => 'Email', 'class' => 'text-center'],
        ['label' => 'Status', 'class' => 'text-center'],
        ['label' => 'Aksi', 'class' => 'text-center']
    ],
    'data' => $users,
    'actions' => [
        [
            'text' => 'Tambah Data',
            'icon' => 'fas fa-plus',
            'class' => 'btn-primary'
        ]
    ]
])
```

#### `components/info-card.blade.php`
Kartu informasi dengan header, body, dan footer.

**Penggunaan:**
```php
@include('components.info-card', [
    'header' => [
        'icon' => 'fas fa-chart-line',
        'title' => 'Statistik',
        'subtitle' => 'Data terbaru'
    ],
    'actions' => [
        [
            'text' => 'Lihat Detail',
            'class' => 'btn-outline'
        ]
    ]
])
    <!-- Card content -->
@endcomponent
```

### 3. Interactive Components

#### `components/modal.blade.php`
Modal dialog dengan header, body, dan footer.

**Penggunaan:**
```php
@include('components.modal', [
    'id' => 'createModal',
    'title' => 'Tambah Data',
    'icon' => 'fas fa-plus',
    'actions' => [
        [
            'text' => 'Batal',
            'class' => 'btn-secondary',
            'onclick' => 'closeModal()'
        ],
        [
            'text' => 'Simpan',
            'class' => 'btn-primary',
            'onclick' => 'saveData()'
        ]
    ]
])
    <!-- Modal content -->
@endcomponent
```

#### `components/search-filter.blade.php`
Komponen pencarian dan filter dengan multiple filter options.

**Penggunaan:**
```php
@include('components.search-filter', [
    'placeholder' => 'Cari data...',
    'filters' => [
        [
            'name' => 'status',
            'label' => 'Status',
            'placeholder' => 'Semua Status',
            'options' => [
                'active' => 'Aktif',
                'inactive' => 'Tidak Aktif'
            ]
        ]
    ],
    'actions' => [
        [
            'text' => 'Export',
            'icon' => 'fas fa-download',
            'class' => 'btn-outline'
        ]
    ]
])
```

## Styling

### CSS Files
- `resources/css/guru-components.css` - Styling utama untuk komponen guru
- `resources/css/toast.css` - Styling untuk notifikasi toast

### CSS Classes

#### Utility Classes
```css
.flex, .items-center, .justify-center
.gap-1, .gap-2, .gap-3, .gap-4
.w-8, .w-10, .w-12, .h-8, .h-10, .h-12
.text-xs, .text-sm, .text-base, .text-lg
.font-medium, .font-semibold, .font-bold
.rounded, .rounded-lg, .rounded-full
.px-2, .px-3, .px-4, .py-1, .py-2, .py-3
```

#### Color Classes
```css
.text-gray-400, .text-gray-500, .text-gray-600
.text-blue-600, .text-green-600, .text-red-600
.bg-gray-50, .bg-gray-100, .bg-white
.bg-blue-100, .bg-green-100, .bg-red-100
```

#### Button Classes
```css
.btn, .btn-sm, .btn-lg
.btn-primary, .btn-secondary, .btn-success, .btn-danger, .btn-outline
```

#### Status Badges
```css
.status-badge, .status-active, .status-inactive, .status-pending
```

## JavaScript

### `resources/js/guru-dashboard.js`
JavaScript utama untuk interaksi UI guru dengan fitur:
- Sidebar toggle
- Modal management
- Search dan filter
- Data table sorting
- Toast notifications
- Keyboard shortcuts

### Global Functions
```javascript
openModal(modalId)     // Membuka modal
closeModal(modalId)    // Menutup modal
showToast(title, message, type)  // Menampilkan notifikasi
```

## Halaman yang Tersedia

### 1. Dashboard Guru
- **Route:** `/guru/dashboard`
- **View:** `dashboard/guru-dashboard.blade.php`
- **Controller:** `GuruController@dashboard`

### 2. Data Pengajar
- **Route:** `/guru/data-pengajar`
- **View:** `guru/data-pengajar.blade.php`
- **Controller:** `GuruController@dataPengajar`

### 3. Data Siswa
- **Route:** `/guru/data-siswa`
- **View:** `guru/data-siswa.blade.php`
- **Controller:** `GuruController@dataSiswa`

### 4. Data Kelas
- **Route:** `/guru/data-kelas`
- **View:** `guru/data-kelas.blade.php`
- **Controller:** `GuruController@dataKelas`

### 5. Data Mata Pelajaran
- **Route:** `/guru/data-mapel`
- **View:** `guru/data-mapel.blade.php`
- **Controller:** `GuruController@dataMapel`

## Pembatasan Akses

### Middleware
- `guru` - Memastikan hanya guru yang bisa mengakses
- `restrict.guru.create` - Membatasi operasi create untuk guru

### Routes yang Dibatasi
Guru **TIDAK BISA** mengakses:
- `/data-pengajar/new-pengajar-1`
- `/data-pengajar/new-pengajar-2`
- `/data-kelas/tambah-kelas`
- `/data-siswa/tambah-siswa`
- Dan route create lainnya

## Responsive Design

Semua komponen sudah responsive dan akan menyesuaikan dengan ukuran layar:
- **Desktop:** Layout penuh dengan sidebar
- **Tablet:** Sidebar dapat di-toggle
- **Mobile:** Sidebar tersembunyi, layout stack

## Browser Support

- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

## Dependencies

- Font Awesome 6.4.0
- Phosphor Icons 2.0.6
- Inter Font (Google Fonts)

## Customization

Untuk menyesuaikan styling, edit file:
- `resources/css/guru-components.css` - Styling utama
- `resources/css/toast.css` - Styling notifikasi

Untuk menyesuaikan JavaScript, edit file:
- `resources/js/guru-dashboard.js` - JavaScript utama
