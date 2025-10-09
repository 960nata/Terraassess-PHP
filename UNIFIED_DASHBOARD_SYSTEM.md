# Unified Dashboard System - Terra Assessment

## Overview
Sistem dashboard terpadu yang memungkinkan semua role (Super Admin, Admin, Guru, Siswa) menggunakan template yang sama dengan menu yang disesuaikan berdasarkan hak akses masing-masing role.

## Features

### 🎯 **Template Konsisten**
- **Single Template**: Semua dashboard menggunakan template yang sama (`dashboard-template.blade.php`)
- **Role-based Menus**: Menu sidebar disesuaikan berdasarkan role pengguna
- **Consistent UI**: Desain dan layout yang sama untuk semua role
- **Responsive Design**: Mobile-first approach dengan breakpoints yang optimal

### 📱 **Responsive Design**
- **Desktop**: > 1024px - 4 kolom kartu, sidebar selalu terlihat
- **Tablet**: 768px - 1024px - 3 kolom kartu, sidebar tersembunyi
- **Mobile**: < 768px - 2 kolom kartu, sidebar dengan overlay
- **Small Mobile**: < 480px - 2 kolom kartu, layout vertikal

### 🎨 **Visual Design**
- **Space Theme**: Konsisten dengan aplikasi Terra Assessment
- **Glass Morphism**: Modern UI dengan backdrop blur effects
- **Color Coding**: Setiap role memiliki warna dan ikon yang unik
- **Smooth Animations**: Hover effects dan transitions yang halus

## File Structure

```
resources/views/
├── layout/
│   ├── template/
│   │   └── dashboard-template.blade.php    # Template utama
│   └── navbar/
│       └── role-sidebar.blade.php          # Sidebar dinamis
├── dashboard/
│   ├── superadmin-new.blade.php            # Dashboard Super Admin
│   ├── admin-new.blade.php                 # Dashboard Admin
│   ├── teacher-new.blade.php               # Dashboard Guru
│   └── student-new.blade.php               # Dashboard Siswa

app/
├── Helpers/
│   └── DashboardHelper.php                 # Helper class
└── Providers/
    └── DashboardServiceProvider.php        # Service provider
```

## Role Configuration

### Super Admin (Role ID: 1)
- **Icon**: `fas fa-crown`
- **Color**: Gold/Yellow theme
- **Permissions**: Full system access
- **Menu Sections**: Main, Management, IoT, Analytics, Settings

### Admin (Role ID: 2)
- **Icon**: `fas fa-user-shield`
- **Color**: Blue theme
- **Permissions**: User and content management
- **Menu Sections**: Main, Management, IoT, Analytics, Settings

### Teacher (Role ID: 3)
- **Icon**: `fas fa-chalkboard-teacher`
- **Color**: Green theme
- **Permissions**: Class and content management
- **Menu Sections**: Main, IoT, Analytics, Settings

### Student (Role ID: 4)
- **Icon**: `fas fa-graduation-cap`
- **Color**: Purple theme
- **Permissions**: Learning and research access
- **Menu Sections**: Main, IoT, Settings

## Usage

### 1. Using the Template

```php
// In your controller
use App\Helpers\DashboardHelper;

public function viewDashboard()
{
    $user = auth()->user();
    $roleConfig = DashboardHelper::getRoleConfig($user->roles_id);
    
    $templateData = array_merge($roleConfig, [
        'user' => $user,
        'roleId' => $user->roles_id
    ]);
    
    return view('dashboard.superadmin-new', $templateData);
}
```

### 2. Adding New Role

1. **Update DashboardHelper.php**:
```php
// Add new role configuration
5 => [ // New Role
    'title' => 'New Role',
    'icon' => 'fas fa-icon',
    'initial' => 'NR',
    // ... other config
]
```

2. **Create Dashboard View**:
```php
// resources/views/dashboard/new-role.blade.php
@extends('layout.template.dashboard-template')

@section('dashboard-cards')
    <!-- Your dashboard cards here -->
@endsection

@php
    // Role configuration
    $roleTitle = 'New Role';
    // ... other config
@endphp
```

3. **Add Controller Method**:
```php
public function viewNewRoleDashboard()
{
    $user = auth()->user();
    $roleConfig = DashboardHelper::getRoleConfig(5);
    
    $templateData = array_merge($roleConfig, [
        'user' => $user,
        'roleId' => 5
    ]);
    
    return view('dashboard.new-role', $templateData);
}
```

### 3. Customizing Menu Items

Edit `resources/views/layout/navbar/role-sidebar.blade.php`:

```php
@if($roleId == 1) {{-- Super Admin --}}
    <a href="{{ route('superadmin.custom-route') }}" class="menu-item">
        <i class="fas fa-custom-icon"></i>
        <span class="menu-item-text">Custom Menu</span>
    </a>
@endif
```

## Dashboard Cards

### Card Structure
```html
<a href="{{ route('your.route') }}" class="card">
    <div class="card-icon blue">
        <i class="fas fa-icon"></i>
    </div>
    <h3 class="card-title">Card Title</h3>
    <p class="card-description">Card description</p>
</a>
```

### Available Card Icon Colors
- `blue` - Blue gradient
- `green` - Green gradient
- `purple` - Purple gradient
- `orange` - Orange gradient
- `red` - Red gradient

## Helper Methods

### DashboardHelper::getRoleConfig($roleId)
Returns complete role configuration including:
- Title, icon, initial
- Description and welcome message
- Permissions and responsibilities
- Route names

### DashboardHelper::getDashboardView($roleId)
Returns the appropriate dashboard view name for the role.

### DashboardHelper::hasPermission($roleId, $action)
Checks if a role has permission for a specific action.

### DashboardHelper::getMenuItems($roleId)
Returns menu items organized by sections for the role.

## CSS Classes

### Main Layout
- `.dashboard-grid` - Grid container for cards
- `.card` - Individual dashboard card
- `.card-icon` - Card icon container
- `.card-title` - Card title
- `.card-description` - Card description

### Responsive Classes
- `.mobile-overlay` - Mobile sidebar overlay
- `.sidebar` - Main sidebar
- `.main-content` - Main content area
- `.header` - Top header

## JavaScript Features

### Sidebar Management
- `toggleSidebar()` - Toggle sidebar visibility
- `closeSidebar()` - Close sidebar and overlay
- Responsive behavior based on window width

### Notification System
- Real-time notification loading
- Mark as read functionality
- Toast notifications
- Auto-refresh every 30 seconds

### Mobile Responsiveness
- Touch-friendly interactions
- Swipe gestures
- Optimized for small screens

## Customization

### 1. Changing Colors
Edit CSS variables in `dashboard-template.blade.php`:
```css
:root {
    --primary-color: #your-color;
    --secondary-color: #your-color;
    --accent-color: #your-color;
}
```

### 2. Adding New Card Types
```css
.card-icon.your-color {
    background: linear-gradient(135deg, #color1, #color2);
    color: white;
}
```

### 3. Modifying Breakpoints
```css
@media (max-width: 1200px) {
    .dashboard-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}
```

## Security

### Middleware
- `auth` - User must be authenticated
- `role:superadmin` - Role-based access control

### Permission System
- Role-based menu visibility
- Route protection
- Data access control

## Performance

### Optimizations
- Lazy loading for assets
- Efficient CSS with minimal redundancy
- Modular JavaScript
- Responsive images

### Caching
- Template caching
- Asset compilation
- Database query optimization

## Browser Support

- Chrome 60+
- Firefox 55+
- Safari 12+
- Edge 79+
- Mobile browsers (iOS Safari, Chrome Mobile)

## Testing

### Manual Testing
1. Test each role dashboard
2. Verify menu items are correct
3. Check responsive behavior
4. Test notification system

### Automated Testing
```php
// Example test
public function test_superadmin_dashboard_loads()
{
    $user = User::factory()->create(['roles_id' => 1]);
    $this->actingAs($user);
    
    $response = $this->get('/superadmin/dashboard');
    $response->assertStatus(200);
    $response->assertViewIs('dashboard.superadmin-new');
}
```

## Migration Guide

### From Old Dashboard System

1. **Update Routes**:
```php
// Old
Route::get('/admin/dashboard', 'AdminController@dashboard');

// New
Route::get('/admin/dashboard', 'DashboardController@viewAdminDashboard');
```

2. **Update Controllers**:
```php
// Old
public function dashboard()
{
    return view('admin.dashboard', $data);
}

// New
public function viewAdminDashboard()
{
    $user = auth()->user();
    $roleConfig = DashboardHelper::getRoleConfig(2);
    
    return view('dashboard.admin-new', array_merge($roleConfig, [
        'user' => $user,
        'roleId' => 2
    ]));
}
```

3. **Update Views**:
- Move content to new template structure
- Update card layouts
- Adjust styling classes

## Troubleshooting

### Common Issues

1. **Helper Class Not Found**
   - Check if `DashboardServiceProvider` is registered
   - Clear config cache: `php artisan config:clear`

2. **Template Not Found**
   - Verify view files exist
   - Check view path in controller

3. **Menu Not Showing**
   - Check role ID in sidebar template
   - Verify route names exist

4. **Styling Issues**
   - Clear view cache: `php artisan view:clear`
   - Check CSS file paths

### Debug Mode
Enable debug mode in `.env`:
```
APP_DEBUG=true
LOG_LEVEL=debug
```

## Future Enhancements

- [ ] Dark/Light theme toggle
- [ ] Real-time data updates
- [ ] Advanced filtering and search
- [ ] Drag & drop card reordering
- [ ] Export functionality
- [ ] Analytics dashboard integration
- [ ] Multi-language support
- [ ] Accessibility improvements

## Support

For issues and questions:
1. Check this documentation
2. Review existing issues
3. Create new issue with detailed description
4. Contact development team

## License

This project uses MIT License. See LICENSE file for details.
