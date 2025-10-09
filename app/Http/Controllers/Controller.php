<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

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
}
