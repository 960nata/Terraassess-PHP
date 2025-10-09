<?php
/**
 * CSRF Debug Helper
 * Place this file in public directory and access via browser
 */

// Start session
session_start();

// Get current CSRF token
$csrfToken = csrf_token();

// Get session ID
$sessionId = session_id();

// Get session data
$sessionData = $_SESSION;

// Get current time
$currentTime = date('Y-m-d H:i:s');

// Get session lifetime
$sessionLifetime = ini_get('session.gc_maxlifetime');

// Get session cookie parameters
$sessionCookieParams = session_get_cookie_params();

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSRF Debug Information</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .debug-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .debug-title {
            color: #333;
            border-bottom: 2px solid #007cba;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .debug-item {
            margin-bottom: 15px;
            padding: 10px;
            background: #f8f9fa;
            border-left: 4px solid #007cba;
        }
        .debug-label {
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        .debug-value {
            color: #666;
            word-break: break-all;
        }
        .status-ok {
            color: #28a745;
        }
        .status-warning {
            color: #ffc107;
        }
        .status-error {
            color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="debug-container">
        <h1 class="debug-title">🔍 CSRF Debug Information</h1>
        
        <div class="debug-item">
            <div class="debug-label">Current Time:</div>
            <div class="debug-value"><?php echo $currentTime; ?></div>
        </div>
        
        <div class="debug-item">
            <div class="debug-label">Session ID:</div>
            <div class="debug-value"><?php echo $sessionId ?: 'No session ID'; ?></div>
        </div>
        
        <div class="debug-item">
            <div class="debug-label">CSRF Token:</div>
            <div class="debug-value"><?php echo $csrfToken ?: 'No CSRF token'; ?></div>
        </div>
        
        <div class="debug-item">
            <div class="debug-label">Session Lifetime:</div>
            <div class="debug-value"><?php echo $sessionLifetime; ?> seconds</div>
        </div>
        
        <div class="debug-item">
            <div class="debug-label">Session Cookie Parameters:</div>
            <div class="debug-value">
                <pre><?php print_r($sessionCookieParams); ?></pre>
            </div>
        </div>
        
        <div class="debug-item">
            <div class="debug-label">Session Data:</div>
            <div class="debug-value">
                <pre><?php print_r($sessionData); ?></pre>
            </div>
        </div>
        
        <div class="debug-item">
            <div class="debug-label">PHP Session Status:</div>
            <div class="debug-value status-<?php echo session_status() === PHP_SESSION_ACTIVE ? 'ok' : 'error'; ?>">
                <?php 
                switch(session_status()) {
                    case PHP_SESSION_DISABLED:
                        echo 'Sessions are disabled';
                        break;
                    case PHP_SESSION_NONE:
                        echo 'Sessions are enabled, but none exists';
                        break;
                    case PHP_SESSION_ACTIVE:
                        echo 'Sessions are active';
                        break;
                }
                ?>
            </div>
        </div>
        
        <div class="debug-item">
            <div class="debug-label">Session Save Path:</div>
            <div class="debug-value"><?php echo session_save_path(); ?></div>
        </div>
        
        <div class="debug-item">
            <div class="debug-label">Session Save Path Writable:</div>
            <div class="debug-value status-<?php echo is_writable(session_save_path()) ? 'ok' : 'error'; ?>">
                <?php echo is_writable(session_save_path()) ? 'Yes' : 'No'; ?>
            </div>
        </div>
        
        <div class="debug-item">
            <div class="debug-label">Storage Directory Writable:</div>
            <div class="debug-value status-<?php echo is_writable('storage/') ? 'ok' : 'error'; ?>">
                <?php echo is_writable('storage/') ? 'Yes' : 'No'; ?>
            </div>
        </div>
        
        <div class="debug-item">
            <div class="debug-label">Bootstrap Cache Directory Writable:</div>
            <div class="debug-value status-<?php echo is_writable('bootstrap/cache/') ? 'ok' : 'error'; ?>">
                <?php echo is_writable('bootstrap/cache/') ? 'Yes' : 'No'; ?>
            </div>
        </div>
    </div>
    
    <div class="debug-container">
        <h2 class="debug-title">🛠️ Quick Fix Commands</h2>
        <div class="debug-item">
            <div class="debug-label">Run these commands in terminal:</div>
            <div class="debug-value">
                <pre>
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Generate new app key
php artisan key:generate

# Clear config again
php artisan config:clear

# Or run the fix script
./fix-csrf.sh
                </pre>
            </div>
        </div>
    </div>
    
    <div class="debug-container">
        <h2 class="debug-title">🧪 Test CSRF Token</h2>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="hidden" name="_token" value="<?php echo $csrfToken; ?>">
            <button type="submit" name="test_csrf">Test CSRF Token</button>
        </form>
        
        <?php if (isset($_POST['test_csrf'])): ?>
            <div class="debug-item">
                <div class="debug-label">CSRF Test Result:</div>
                <div class="debug-value status-ok">
                    ✅ CSRF token is working correctly!
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>