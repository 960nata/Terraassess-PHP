# 🎨 Terra Assessment - Unified Components Guide

## 📋 Overview

Sistem Terra Assessment sekarang menggunakan komponen unified yang 100% konsisten di seluruh aplikasi. Semua komponen menggunakan design system yang sama dan dapat digunakan di semua halaman.

---

## 🧩 **Available Components**

### **1. Layout Components**

#### **Unified Welcome Section**
```blade
<x-unified-welcome-section 
    :userName="Auth::user()->name"
    roleName="Guru"
    roleIcon="fas fa-chalkboard-teacher"
    roleColor="green"
    description="Kelola pembelajaran dan kelas Anda"
/>
```

#### **Unified Page Header**
```blade
<x-unified-page-header
    title="Dashboard"
    description="Selamat datang di Terra Assessment"
    icon="fas fa-home"
    :breadcrumbs="[
        ['label' => 'Home', 'href' => route('dashboard')],
        ['label' => 'Current Page']
    ]"
>
    <x-slot name="actions">
        <x-unified-button variant="primary" icon="fas fa-plus">Add New</x-unified-button>
    </x-slot>
</x-unified-page-header>
```

---

### **2. Dashboard Components**

#### **Unified Dashboard Card**
```blade
<x-unified-dashboard-card
    title="Manajemen Tugas"
    description="Kelola tugas yang telah Anda buat"
    icon="fas fa-tasks"
    iconColor="blue"
    :href="route('teacher.task-management')"
    badge="5"
/>
```

#### **Unified Stats Grid**
```blade
<x-unified-stats-grid :stats="[
    [
        'title' => 'Total Users',
        'value' => '1,234',
        'change' => '+12%',
        'change_type' => 'positive',
        'icon' => 'fas fa-users',
        'color' => 'primary'
    ],
    [
        'title' => 'Active Classes',
        'value' => '45',
        'change' => '+5%',
        'change_type' => 'positive',
        'icon' => 'fas fa-chalkboard',
        'color' => 'success'
    ]
]" />
```

---

### **3. Form Components**

#### **Unified Input**
```blade
<x-unified-input
    name="email"
    label="Email Address"
    type="email"
    icon="fas fa-envelope"
    placeholder="Enter your email"
    required
    :error="$errors->first('email')"
    help="We'll never share your email"
/>
```

#### **Unified Button**
```blade
<x-unified-button
    variant="primary"
    size="md"
    icon="fas fa-save"
    iconPosition="left"
    :loading="false"
    :disabled="false"
>
    Save Changes
</x-unified-button>
```

**Button Variants:**
- `primary` - Blue primary button
- `secondary` - Gray secondary button
- `success` - Green success button
- `warning` - Yellow warning button
- `danger` - Red danger button
- `outline` - Outlined button
- `ghost` - Ghost button

**Button Sizes:**
- `sm` - Small button
- `md` - Medium button (default)
- `lg` - Large button
- `xl` - Extra large button

---

### **4. Data Display Components**

#### **Unified Table**
```blade
<x-unified-table
    :headers="['Name', 'Email', 'Role', 'Actions']"
    :data="[
        ['John Doe', 'john@example.com', 'Admin', 'Edit'],
        ['Jane Smith', 'jane@example.com', 'User', 'Edit']
    ]"
    striped
    hover
    bordered
/>
```

#### **Unified Alert**
```blade
<x-unified-alert
    type="success"
    title="Success!"
    dismissible
>
    Your changes have been saved successfully.
</x-unified-alert>
```

**Alert Types:**
- `success` - Green success alert
- `error` - Red error alert
- `warning` - Yellow warning alert
- `info` - Blue info alert

---

### **5. Interactive Components**

#### **Unified Modal**
```blade
<x-unified-modal
    id="confirmModal"
    title="Confirm Action"
    size="md"
    closable
>
    <p>Are you sure you want to delete this item?</p>
    
    <x-slot name="footer">
        <x-unified-button variant="danger" onclick="closeModal('confirmModal')">
            Delete
        </x-unified-button>
        <x-unified-button variant="secondary" onclick="closeModal('confirmModal')">
            Cancel
        </x-unified-button>
    </x-slot>
</x-unified-modal>
```

**Modal Sizes:**
- `sm` - Small modal
- `md` - Medium modal (default)
- `lg` - Large modal
- `xl` - Extra large modal
- `full` - Full screen modal

---

### **6. State Components**

#### **Unified Loading**
```blade
<!-- Inline loading -->
<x-unified-loading size="md" text="Loading data..." />

<!-- Overlay loading -->
<x-unified-loading size="lg" text="Processing..." overlay />
```

#### **Unified Empty State**
```blade
<x-unified-empty-state
    icon="fas fa-inbox"
    title="No data available"
    description="There are no items to display at the moment."
>
    <x-unified-button variant="primary" icon="fas fa-plus">
        Add New Item
    </x-unified-button>
</x-unified-empty-state>
```

---

## 🎨 **Design System**

### **Color Palette**

#### **Primary Colors**
- `primary-50` to `primary-950` - Blue color scale
- Used for main actions, links, and primary elements

#### **Secondary Colors**
- `secondary-50` to `secondary-950` - Gray color scale
- Used for text, borders, and neutral elements

#### **Status Colors**
- `success-*` - Green colors for success states
- `warning-*` - Yellow colors for warning states
- `error-*` - Red colors for error states
- `info-*` - Blue colors for info states

### **Typography**
- **Font Family:** Inter (primary), system fonts (fallback)
- **Font Sizes:** xs (12px) to 5xl (48px)
- **Font Weights:** light (300) to extrabold (800)

### **Spacing**
- **Scale:** 1 (4px) to 24 (96px)
- **Consistent spacing** across all components

### **Border Radius**
- **Scale:** none (0px) to full (9999px)
- **Default:** lg (8px) for cards and buttons

### **Shadows**
- **Scale:** sm to 2xl
- **Default:** sm for cards, md for hover states

---

## 🚀 **Usage Examples**

### **Complete Dashboard Page**
```blade
@extends('layouts.unified-layout-consistent')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-description', 'Welcome to your dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <x-unified-welcome-section 
        :userName="Auth::user()->name"
        roleName="Admin"
        roleIcon="fas fa-user-shield"
        roleColor="blue"
        description="Manage your system"
    />

    <!-- Stats Grid -->
    <x-unified-stats-grid :stats="$stats" />

    <!-- Dashboard Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <x-unified-dashboard-card
            title="Users"
            description="Manage system users"
            icon="fas fa-users"
            iconColor="blue"
            :href="route('users.index')"
        />
        
        <x-unified-dashboard-card
            title="Settings"
            description="System configuration"
            icon="fas fa-cog"
            iconColor="gray"
            :href="route('settings.index')"
        />
    </div>
</div>
@endsection
```

### **Complete Form Page**
```blade
@extends('layouts.unified-layout-consistent')

@section('content')
<div class="max-w-2xl mx-auto">
    <x-unified-page-header
        title="Create User"
        description="Add a new user to the system"
        icon="fas fa-user-plus"
    />

    <div class="unified-card">
        <div class="unified-card-body">
            <form method="POST" action="{{ route('users.store') }}">
                @csrf
                
                <div class="space-y-6">
                    <x-unified-input
                        name="name"
                        label="Full Name"
                        icon="fas fa-user"
                        required
                        :error="$errors->first('name')"
                    />
                    
                    <x-unified-input
                        name="email"
                        label="Email Address"
                        type="email"
                        icon="fas fa-envelope"
                        required
                        :error="$errors->first('email')"
                    />
                    
                    <div class="flex justify-end space-x-3">
                        <x-unified-button variant="secondary" type="button">
                            Cancel
                        </x-unified-button>
                        <x-unified-button variant="primary" type="submit" icon="fas fa-save">
                            Save User
                        </x-unified-button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
```

---

## 📱 **Responsive Design**

Semua komponen sudah responsive dan akan menyesuaikan dengan ukuran layar:

- **Mobile (< 640px):** Padding dan spacing dikurangi
- **Tablet (640px - 1024px):** Layout menyesuaikan
- **Desktop (> 1024px):** Layout penuh dengan sidebar

---

## ♿ **Accessibility**

Semua komponen sudah mengikuti standar accessibility:

- **ARIA labels** untuk screen readers
- **Keyboard navigation** support
- **Focus management** yang proper
- **Color contrast** yang memenuhi standar
- **Reduced motion** support

---

## 🎯 **Best Practices**

### **1. Konsistensi**
- Selalu gunakan komponen unified
- Jangan membuat custom styling yang tidak perlu
- Gunakan design tokens yang sudah tersedia

### **2. Performance**
- Komponen sudah dioptimasi untuk performa
- Gunakan lazy loading untuk data yang besar
- Minimize custom CSS

### **3. Maintenance**
- Semua komponen terpusat di satu tempat
- Mudah di-update dan di-maintain
- Konsisten di seluruh aplikasi

---

## 🔧 **Customization**

Jika perlu kustomisasi, gunakan props yang tersedia:

```blade
<x-unified-button
    variant="primary"
    size="lg"
    icon="fas fa-custom-icon"
    class="custom-class"
>
    Custom Button
</x-unified-button>
```

**Jangan** override CSS langsung, gunakan props atau extend komponen.

---

## 📊 **Benefits**

✅ **100% Konsisten** - Semua komponen menggunakan design system yang sama  
✅ **Reusable** - Komponen dapat digunakan di mana saja  
✅ **Maintainable** - Mudah di-update dan di-maintain  
✅ **Accessible** - Mengikuti standar accessibility  
✅ **Responsive** - Bekerja di semua device  
✅ **Performance** - Dioptimasi untuk performa terbaik  

---

## 🎉 **Result**

Dengan sistem unified ini, Terra Assessment sekarang memiliki:

- **Konsistensi 100%** di seluruh aplikasi
- **Developer Experience** yang lebih baik
- **User Experience** yang konsisten
- **Maintenance** yang lebih mudah
- **Performance** yang optimal

**Sistem Terra Assessment sekarang 100% konsisten dan siap untuk production!** 🚀
