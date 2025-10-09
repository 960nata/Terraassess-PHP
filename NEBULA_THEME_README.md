# 🌌 Nebula Galaxy Theme

Theme nebula dengan background galaxy, bintang-bintang berkedip, dan efek glass pada semua komponen UI.

## ✨ Fitur

- **Background Nebula**: Gradient background dengan efek nebula yang bergerak
- **Bintang Berkedip**: Animasi bintang-bintang yang berkedip di background
- **Glass Effect**: Semua card dan komponen menggunakan efek glass dengan backdrop blur
- **Text Adaptation**: Warna text yang menyesuaikan dengan background nebula
- **Responsive**: Fully responsive untuk semua ukuran layar

## 🎨 Warna Theme

```css
--nebula-primary: #1a0b2e    /* Dark purple */
--nebula-secondary: #16213e  /* Dark blue */
--nebula-accent: #0f3460     /* Deep blue */
--nebula-purple: #533483     /* Purple */
--nebula-pink: #e94560       /* Pink accent */
--nebula-cyan: #0f4c75       /* Cyan */
--nebula-gold: #ffd700       /* Gold text */
--nebula-silver: #c0c0c0     /* Silver text */
```

## 🚀 Cara Penggunaan

### 1. Include CSS
Tambahkan CSS nebula theme ke template utama:

```html
<link href="{{ url('/asset/css/nebula-theme.css') }}" rel="stylesheet">
```

### 2. Gunakan Class Glass

#### Glass Card
```html
<div class="glass-card">
    <div class="card-header">
        <h5 class="text-white">Header</h5>
    </div>
    <div class="card-body">
        <p class="text-white-75">Content dengan efek glass</p>
    </div>
</div>
```

#### Statistik Card
```html
<div class="stat-card">
    <div class="text-center">
        <h1 class="display-3 text-warning fw-bold">1,234</h1>
        <h5 class="text-white">Total Data</h5>
        <p class="text-white-75 small">Deskripsi</p>
    </div>
</div>
```

#### Glass Chart
```html
<div class="glass-chart">
    <h5 class="text-white mb-3">Chart Title</h5>
    <!-- Chart content -->
</div>
```

### 3. Text Classes

```html
<h1 class="text-white">Judul Utama</h1>
<p class="text-white-75">Text dengan opacity 75%</p>
<span class="text-white-50">Text dengan opacity 50%</span>
<hr class="border-white-25"> <!-- Border dengan opacity 25% -->
```

### 4. Form Elements

Semua form elements otomatis menggunakan glass effect:

```html
<input type="text" class="form-control" placeholder="Input dengan glass effect">
<select class="form-select">
    <option>Select dengan glass effect</option>
</select>
<button class="btn btn-glass">Glass Button</button>
```

### 5. Alert dengan Glass Effect

```html
<div class="alert alert-success">
    <i class="fas fa-check-circle me-2"></i>
    Data berhasil disimpan!
</div>
```

## 🎯 Demo

Buka file `public/nebula-demo.html` untuk melihat demo lengkap theme nebula.

## 📱 Responsive

Theme ini fully responsive dan akan menyesuaikan dengan semua ukuran layar:

- **Desktop**: Full glass effect dengan animasi lengkap
- **Tablet**: Optimized untuk layar medium
- **Mobile**: Simplified glass effect untuk performa optimal

## ⚡ Performa

- Menggunakan CSS3 backdrop-filter untuk efek glass
- Animasi GPU-accelerated untuk performa optimal
- Fallback untuk browser yang tidak support backdrop-filter

## 🔧 Customization

### Mengubah Warna
Edit variabel CSS di file `nebula-theme.css`:

```css
:root {
    --nebula-gold: #your-color;     /* Warna emas */
    --nebula-pink: #your-color;     /* Warna pink */
    --glass-bg: rgba(255, 255, 255, 0.1); /* Background glass */
}
```

### Mengubah Animasi
Edit keyframes di file CSS:

```css
@keyframes nebulaFloat {
    /* Customize nebula animation */
}

@keyframes twinkle {
    /* Customize star twinkle animation */
}
```

## 🌟 Komponen yang Didukung

- ✅ Cards (glass-card, stat-card, glass-chart)
- ✅ Forms (form-control, form-select)
- ✅ Buttons (btn-glass)
- ✅ Tables (table)
- ✅ Alerts (alert)
- ✅ Badges (badge)
- ✅ Progress bars (progress)
- ✅ Pagination (pagination)
- ✅ Breadcrumbs (breadcrumb)
- ✅ Dropdowns (dropdown-menu)
- ✅ Modals (modal-content)
- ✅ Sidebar & Topbar
- ✅ Footer

## 🎨 Tips Desain

1. **Kontras**: Gunakan text-white untuk kontras maksimal
2. **Hierarchy**: Gunakan text-white-75 dan text-white-50 untuk hierarchy
3. **Accent**: Gunakan nebula-gold untuk highlight penting
4. **Spacing**: Berikan cukup spacing antar elemen untuk efek glass yang optimal

## 🐛 Browser Support

- ✅ Chrome 76+
- ✅ Firefox 103+
- ✅ Safari 14+
- ✅ Edge 79+

## 📄 Lisensi

Theme ini dibuat khusus untuk project TerraAssessment dan dapat digunakan secara bebas dalam project ini.
