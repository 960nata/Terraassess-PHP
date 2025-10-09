# CSRF Token Fix Guide - "Page Expired" Error

## 🔍 **PENYEBAB ERROR "PAGE EXPIRED"**

Error "Page Expired" (HTTP 419) biasanya disebabkan oleh:

1. **CSRF Token Mismatch** - Token tidak cocok atau expired
2. **Session Expired** - Session sudah kadaluarsa
3. **Cache Issues** - Browser cache yang bermasalah
4. **Configuration Issues** - Konfigurasi session/CSRF yang salah

## 🛠️ **SOLUSI LANGKAH DEMI LANGKAH**

### **1. Clear All Caches**
```bash
# Clear application cache
php artisan cache:clear

# Clear config cache
php artisan config:clear

# Clear view cache
php artisan view:clear

# Clear route cache
php artisan route:clear

# Clear session cache
php artisan session:table
```

### **2. Check .env Configuration**
Pastikan file `.env` memiliki konfigurasi yang benar:

```env
# Session Configuration
SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null
SESSION_SECURE_COOKIE=false
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax

# CSRF Configuration
APP_KEY=base64:YOUR_APP_KEY_HERE
```

### **3. Generate New Application Key**
```bash
# Generate new application key
php artisan key:generate

# Clear config cache after generating key
php artisan config:clear
```

### **4. Check Session Storage**
```bash
# Check if session storage is writable
ls -la storage/framework/sessions/

# If directory doesn't exist, create it
mkdir -p storage/framework/sessions
chmod 755 storage/framework/sessions
```

### **5. Update Login Form**
Pastikan form login memiliki CSRF token yang benar:

```html
<form method="POST" action="{{ route('authenticate') }}">
    @csrf
    <!-- Form fields -->
</form>
```

### **6. Add CSRF Meta Tag**
Tambahkan meta tag CSRF di head section:

```html
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
```

### **7. Update JavaScript (if using AJAX)**
```javascript
// Set CSRF token for AJAX requests
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
```

## 🔧 **QUICK FIX COMMANDS**

### **Run these commands in order:**

```bash
# 1. Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# 2. Generate new app key
php artisan key:generate

# 3. Clear config again
php artisan config:clear

# 4. Restart server
php artisan serve
```

## 🧪 **TESTING STEPS**

### **1. Test CSRF Token**
```php
// Add this to your login controller for debugging
public function authenticate(Request $request)
{
    // Debug CSRF token
    \Log::info('CSRF Token from request: ' . $request->input('_token'));
    \Log::info('CSRF Token from session: ' . csrf_token());
    \Log::info('Session ID: ' . session()->getId());
    
    // Rest of your login logic...
}
```

### **2. Check Browser Network Tab**
1. Open browser developer tools
2. Go to Network tab
3. Try to login
4. Check if CSRF token is being sent
5. Check response status

### **3. Test with Different Browsers**
- Chrome
- Firefox
- Safari
- Edge

## 🚨 **TROUBLESHOOTING**

### **If still getting 419 error:**

1. **Check Session Driver**
```php
// In config/session.php
'driver' => env('SESSION_DRIVER', 'file'),
```

2. **Check Session Lifetime**
```php
// In config/session.php
'lifetime' => env('SESSION_LIFETIME', 120), // 120 minutes
```

3. **Check CSRF Middleware**
```php
// In app/Http/Kernel.php
protected $middlewareGroups = [
    'web' => [
        \App\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\VerifyCsrfToken::class, // Make sure this is here
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
    ],
];
```

4. **Check Route Middleware**
```php
// In routes/web.php
Route::post('/authenticate', 'LoginRegistController@authenticate')
    ->middleware(['web']); // Make sure 'web' middleware is applied
```

## 🔄 **ALTERNATIVE SOLUTIONS**

### **1. Disable CSRF for Login (NOT RECOMMENDED)**
```php
// In app/Http/Middleware/VerifyCsrfToken.php
protected $except = [
    'authenticate', // Only if absolutely necessary
];
```

### **2. Use Database Session Driver**
```env
SESSION_DRIVER=database
```

```bash
# Create sessions table
php artisan session:table
php artisan migrate
```

### **3. Use Redis Session Driver**
```env
SESSION_DRIVER=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

## 📱 **MOBILE-SPECIFIC FIXES**

### **For mobile browsers:**

1. **Clear browser data**
2. **Disable private/incognito mode**
3. **Check if cookies are enabled**
4. **Try different mobile browser**

## ✅ **VERIFICATION**

After applying fixes, verify:

1. **Login works without 419 error**
2. **CSRF token is present in form**
3. **Session is created successfully**
4. **Redirect to dashboard works**
5. **Logout works properly**

## 🎯 **PREVENTION**

To prevent this issue in the future:

1. **Always include @csrf in forms**
2. **Use proper session configuration**
3. **Regular cache clearing**
4. **Monitor session lifetime**
5. **Test on different browsers**

## 📞 **SUPPORT**

If the issue persists:

1. Check Laravel logs: `storage/logs/laravel.log`
2. Check browser console for errors
3. Check network tab for failed requests
4. Verify server configuration
5. Contact development team
