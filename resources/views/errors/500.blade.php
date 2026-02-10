<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server Error - {{ config('app.name') }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .error-container {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 500px;
            width: 90%;
        }
        .error-code {
            font-size: 4rem;
            font-weight: bold;
            color: #e74c3c;
            margin: 0;
        }
        .error-title {
            font-size: 1.5rem;
            color: #2c3e50;
            margin: 1rem 0;
        }
        .error-message {
            color: #7f8c8d;
            margin: 1rem 0;
            line-height: 1.6;
        }
        .error-details {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1rem;
            margin: 1rem 0;
            text-align: left;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
            color: #495057;
            max-height: 200px;
            overflow-y: auto;
        }
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin: 0.5rem;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #2980b9;
        }
        .btn-secondary {
            background: #95a5a6;
        }
        .btn-secondary:hover {
            background: #7f8c8d;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">500</div>
        <h1 class="error-title">Server Error</h1>
        <p class="error-message">
            Maaf, terjadi kesalahan pada server. Tim kami telah diberitahu dan sedang mengatasi masalah ini.
        </p>
        
        @if(config('app.debug') && isset($exception))
        <div class="error-details">
            <strong>Error Details:</strong><br>
            <strong>Message:</strong> {{ $exception->getMessage() }}<br>
            <strong>File:</strong> {{ $exception->getFile() }}<br>
            <strong>Line:</strong> {{ $exception->getLine() }}<br>
            <strong>Trace:</strong><br>
            <pre>{{ $exception->getTraceAsString() }}</pre>
        </div>
        @endif
        
        <div>
            <a href="{{ url('/') }}" class="btn">Kembali ke Beranda</a>
            <a href="javascript:history.back()" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</body>
</html>
