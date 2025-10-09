# Modal Component - TerraAssessment

## Overview
Komponen modal yang dapat digunakan kembali dengan desain modern dan fitur lengkap untuk aplikasi TerraAssessment.

## Spesifikasi Desain

### Visual Design
- **Background**: White (#ffffff)
- **Border-radius**: 12px
- **Shadow**: 0 25px 50px -12px rgba(0, 0, 0, 0.25)
- **Position**: Fixed, centered
- **Padding**: 24px
- **Max-height**: 90vh
- **Overflow**: Auto

### Animasi
- **Scale**: 0.95 → 1.0
- **Fade-in**: 200ms ease
- **Backdrop blur**: 4px

### Ukuran
- **Default**: max-width 512px (32rem)
- **Large**: max-width 768px (48rem)  
- **XL**: max-width 1024px (64rem)

## Penggunaan

### Basic Usage
```blade
<x-modal id="myModal" title="My Modal">
    <p>Your content here</p>
</x-modal>

<button onclick="openModal('myModal')">Open Modal</button>
```

### With Different Sizes
```blade
<!-- Default (512px) -->
<x-modal id="defaultModal" title="Default" size="default">
    <p>Default size content</p>
</x-modal>

<!-- Large (768px) -->
<x-modal id="largeModal" title="Large" size="large">
    <p>Large size content</p>
</x-modal>

<!-- XL (1024px) -->
<x-modal id="xlModal" title="Extra Large" size="xl">
    <p>XL size content</p>
</x-modal>
```

### With Footer
```blade
<x-modal id="formModal" title="Form Modal" size="large">
    <form>
        <!-- form content -->
    </form>
    
    <x-slot name="footer">
        <button onclick="closeModal('formModal')">Cancel</button>
        <button type="submit">Save</button>
    </x-slot>
</x-modal>
```

### Without Header
```blade
<x-modal id="alertModal" :showCloseButton="false">
    <div class="text-center">
        <h3>Alert!</h3>
        <p>This is an alert message.</p>
        <button onclick="closeModal('alertModal')">OK</button>
    </div>
</x-modal>
```

## Props/Parameters

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `id` | string | 'modal' | Unique identifier for the modal |
| `size` | string | 'default' | Modal size: 'default', 'large', 'xl' |
| `title` | string | '' | Modal title (optional) |
| `showCloseButton` | boolean | true | Show close button in header |
| `closeOnBackdrop` | boolean | true | Close modal when clicking backdrop |
| `closeOnEscape` | boolean | true | Close modal with ESC key |

## JavaScript Functions

### openModal(modalId)
Membuka modal dengan ID yang ditentukan.

```javascript
openModal('myModal');
```

### closeModal(modalId)
Menutup modal dengan ID yang ditentukan.

```javascript
closeModal('myModal');
```

### Events
Modal mengirim custom events yang dapat didengarkan:

```javascript
// Modal opened event
document.addEventListener('modal:opened', function(e) {
    console.log('Modal opened:', e.detail.modalId);
});

// Modal closed event
document.addEventListener('modal:closed', function(e) {
    console.log('Modal closed:', e.detail.modalId);
});
```

## Styling

### CSS Classes
- `.modal-overlay` - Overlay background
- `.modal-container` - Modal container
- `.modal-header` - Header section
- `.modal-title` - Title text
- `.modal-close-btn` - Close button
- `.modal-body` - Content area
- `.modal-footer` - Footer section

### Custom Styling
Modal menggunakan Tailwind CSS classes dan dapat dikustomisasi dengan mudah:

```css
/* Custom modal styling */
.modal-container {
    border: 2px solid #3b82f6;
}

.modal-title {
    color: #1f2937;
    font-weight: 700;
}
```

## Responsive Design

Modal secara otomatis responsive dan menyesuaikan dengan ukuran layar:

- **Desktop**: Full size dengan padding normal
- **Tablet**: Slightly smaller padding
- **Mobile**: Reduced padding, full width with margins

## Accessibility

### Keyboard Navigation
- **ESC**: Close modal
- **Tab**: Navigate through focusable elements
- **Enter/Space**: Activate buttons

### Focus Management
- Modal container receives focus when opened
- Focus returns to trigger element when closed
- Focus trap within modal when open

### ARIA Labels
- Proper ARIA labels for close button
- Modal role and attributes
- Screen reader friendly

## Examples

### Confirmation Modal
```blade
<x-modal id="confirmModal" title="Confirm Action">
    <p>Are you sure you want to delete this item?</p>
    
    <x-slot name="footer">
        <button onclick="closeModal('confirmModal')" class="btn btn-secondary">
            Cancel
        </button>
        <button onclick="deleteItem()" class="btn btn-danger">
            Delete
        </button>
    </x-slot>
</x-modal>
```

### Form Modal
```blade
<x-modal id="userModal" title="Add User" size="large">
    <form id="userForm">
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" class="form-control" name="name" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" required>
        </div>
    </form>
    
    <x-slot name="footer">
        <button onclick="closeModal('userModal')" class="btn btn-secondary">
            Cancel
        </button>
        <button onclick="saveUser()" class="btn btn-primary">
            Save User
        </button>
    </x-slot>
</x-modal>
```

### Data Display Modal
```blade
<x-modal id="dataModal" title="User Details" size="xl">
    <div class="row">
        <div class="col-md-6">
            <h5>Personal Information</h5>
            <p><strong>Name:</strong> John Doe</p>
            <p><strong>Email:</strong> john@example.com</p>
        </div>
        <div class="col-md-6">
            <h5>Statistics</h5>
            <p><strong>Login Count:</strong> 42</p>
            <p><strong>Last Login:</strong> 2 hours ago</p>
        </div>
    </div>
    
    <x-slot name="footer">
        <button onclick="closeModal('dataModal')" class="btn btn-primary">
            Close
        </button>
    </x-slot>
</x-modal>
```

## Browser Support

- Chrome 60+
- Firefox 55+
- Safari 12+
- Edge 79+

## Performance

- Lightweight: ~2KB CSS + ~1KB JavaScript
- Smooth 60fps animations
- No external dependencies
- Optimized for mobile devices

## Demo

Akses halaman demo untuk melihat semua contoh penggunaan:
`/demo/modal`

## File Locations

- Component: `resources/views/components/modal.blade.php`
- Examples: `resources/views/components/modal-examples.blade.php`
- Demo Page: `resources/views/demo/modal-demo.blade.php`
- Route: `/demo/modal`
