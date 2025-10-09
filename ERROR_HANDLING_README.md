# Error Handling Documentation

## Overview
This document describes the error handling mechanisms implemented in the Terra Assessment application to handle decryption errors and other common issues.

## Decryption Error Handling

### Problem
The application was experiencing `DecryptException: The payload is invalid` errors when trying to decrypt tokens in various controllers. This typically happens when:
- Tokens are malformed or corrupted
- Tokens have expired
- Tokens are not properly encrypted
- Invalid characters are present in the token

### Solution
Implemented a comprehensive error handling system:

#### 1. Base Controller Helper Methods
Added to `app/Http/Controllers/Controller.php`:

```php
/**
 * Helper method untuk decrypt dengan error handling
 *
 * @param string $token
 * @return int|null
 */
protected function safeDecrypt($token)
{
    try {
        return decrypt($token);
    } catch (Exception $e) {
        return null;
    }
}

/**
 * Helper method untuk decrypt dengan redirect error
 *
 * @param string $token
 * @param string $errorMessage
 * @return int|false
 */
protected function safeDecryptOrRedirect($token, $errorMessage = 'Invalid token. Please try again.')
{
    $decrypted = $this->safeDecrypt($token);
    
    if ($decrypted === null) {
        return false;
    }
    
    return $decrypted;
}
```

#### 2. Updated Controllers
Modified controllers to use safe decryption:

**TugasController.php**
- `viewTugas()` - Fixed decrypt error handling
- `siswaUpdateNilai()` - Fixed decrypt error handling  
- `viewCreateTugas()` - Fixed decrypt error handling

**Pattern used:**
```php
public function someMethod(Request $request)
{
    $id = $this->safeDecrypt($request->token);
    
    if ($id === null) {
        return view('errors.token-invalid');
    }
    
    // Continue with method logic...
}
```

#### 3. Error View
Created `resources/views/errors/token-invalid.blade.php`:
- User-friendly error page
- Consistent with application design
- Provides navigation options (Go Back, Home)
- Space theme styling

## Error View Features

### Design
- **Theme**: Space theme consistent with main application
- **Fonts**: Poppins (titles) + Inter (body text)
- **Colors**: White text with space background
- **Layout**: Centered error message with action buttons

### User Experience
- Clear error message explaining the issue
- Action buttons for navigation
- Responsive design for all screen sizes
- Professional appearance

## Implementation Guidelines

### For New Controllers
When creating new controllers that use encrypted tokens:

1. **Use safe decryption:**
```php
$id = $this->safeDecrypt($request->token);
if ($id === null) {
    return view('errors.token-invalid');
}
```

2. **Handle multiple tokens:**
```php
$id = $this->safeDecrypt($request->token);
$idx = $this->safeDecrypt($request->kelasMapelId);

if ($id === null || $idx === null) {
    return view('errors.token-invalid');
}
```

3. **Provide fallback actions:**
```php
if ($id === null) {
    return redirect()->back()->with('error', 'Invalid token. Please try again.');
}
```

### For Existing Controllers
Update existing controllers by replacing direct `decrypt()` calls:

**Before:**
```php
$id = decrypt($request->token);
```

**After:**
```php
$id = $this->safeDecrypt($request->token);
if ($id === null) {
    return view('errors.token-invalid');
}
```

## Error Types Handled

### 1. Decryption Errors
- **Cause**: Invalid, expired, or malformed tokens
- **Solution**: Safe decryption with null return
- **User Experience**: Custom error page

### 2. Missing Parameters
- **Cause**: Required parameters not provided
- **Solution**: Validation before processing
- **User Experience**: Form validation messages

### 3. Database Errors
- **Cause**: Database connection or query issues
- **Solution**: Try-catch blocks around database operations
- **User Experience**: Generic error messages

## Testing

### Manual Testing
1. Access URLs with invalid tokens
2. Verify error page displays correctly
3. Test navigation buttons work
4. Check responsive design

### Automated Testing
```php
public function testInvalidToken()
{
    $response = $this->get('/tugas?token=invalid_token');
    $response->assertStatus(200);
    $response->assertViewIs('errors.token-invalid');
}
```

## Future Improvements

### 1. Logging
Add logging for decryption errors:
```php
protected function safeDecrypt($token)
{
    try {
        return decrypt($token);
    } catch (Exception $e) {
        Log::warning('Decryption failed', [
            'token' => $token,
            'error' => $e->getMessage()
        ]);
        return null;
    }
}
```

### 2. Token Validation
Add token format validation:
```php
protected function isValidToken($token)
{
    return is_string($token) && strlen($token) > 10;
}
```

### 3. Rate Limiting
Implement rate limiting for error pages to prevent abuse.

## Conclusion

The error handling system provides:
- ✅ **Robust Error Handling**: Prevents application crashes
- ✅ **User-Friendly Experience**: Clear error messages and navigation
- ✅ **Consistent Design**: Matches application theme
- ✅ **Maintainable Code**: Reusable helper methods
- ✅ **Future-Proof**: Easy to extend and modify

This implementation ensures the Terra Assessment application remains stable and provides a good user experience even when encountering errors.
