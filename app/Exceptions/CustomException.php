<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CustomException extends Exception
{
    protected $statusCode;
    protected $errorCode;
    protected $details;

    public function __construct(
        string $message = 'Terjadi kesalahan',
        int $statusCode = 500,
        string $errorCode = 'INTERNAL_ERROR',
        array $details = []
    ) {
        parent::__construct($message);
        $this->statusCode = $statusCode;
        $this->errorCode = $errorCode;
        $this->details = $details;
    }

    public function render(Request $request): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $this->getMessage(),
            'error_code' => $this->errorCode,
            'timestamp' => now()->toISOString(),
        ];

        if (!empty($this->details)) {
            $response['details'] = $this->details;
        }

        if (config('app.debug')) {
            $response['debug'] = [
                'file' => $this->getFile(),
                'line' => $this->getLine(),
                'trace' => $this->getTraceAsString(),
            ];
        }

        return response()->json($response, $this->statusCode);
    }

    public function report(): void
    {
        Log::error('Custom Exception', [
            'message' => $this->getMessage(),
            'error_code' => $this->errorCode,
            'status_code' => $this->statusCode,
            'details' => $this->details,
            'file' => $this->getFile(),
            'line' => $this->getLine(),
            'trace' => $this->getTraceAsString(),
        ]);
    }

    public static function notFound(string $message = 'Resource tidak ditemukan'): self
    {
        return new self($message, 404, 'NOT_FOUND');
    }

    public static function unauthorized(string $message = 'Anda tidak memiliki akses'): self
    {
        return new self($message, 401, 'UNAUTHORIZED');
    }

    public static function forbidden(string $message = 'Akses ditolak'): self
    {
        return new self($message, 403, 'FORBIDDEN');
    }

    public static function validation(string $message = 'Data tidak valid', array $details = []): self
    {
        return new self($message, 422, 'VALIDATION_ERROR', $details);
    }

    public static function serverError(string $message = 'Terjadi kesalahan pada server'): self
    {
        return new self($message, 500, 'SERVER_ERROR');
    }
}
