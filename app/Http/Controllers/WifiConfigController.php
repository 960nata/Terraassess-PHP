<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WifiConfigController extends Controller
{
    /**
     * Show WiFi configuration page
     */
    public function index()
    {
        return view('iot.wifi-config');
    }

    /**
     * Detect available serial ports for ESP8266 connection
     */
    public function detectPorts(Request $request)
    {
        try {
            Log::info('Port detection requested');
            
            // Simulate port detection for development
            // In production, this would use system commands to detect actual serial ports
            $ports = $this->simulatePortDetection();
            
            return response()->json([
                'success' => true,
                'ports' => $ports,
                'message' => 'Port detection completed successfully'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Port detection failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Port detection failed: ' . $e->getMessage(),
                'ports' => []
            ], 500);
        }
    }
    
    /**
     * Simulate port detection for development
     * In production, this would use actual system commands
     */
    private function simulatePortDetection()
    {
        // Simulate different scenarios
        $scenarios = [
            // No ports found
            [],
            
            // One ESP8266 device found
            [
                [
                    'port' => '/dev/cu.usbserial-0001',
                    'device_name' => 'USB Serial Port',
                    'device_type' => 'ESP8266',
                    'vendor_id' => '10C4',
                    'product_id' => 'EA60',
                    'is_compatible' => true,
                    'description' => 'CP2102 USB to UART Bridge Controller'
                ]
            ],
            
            // Multiple devices found
            [
                [
                    'port' => '/dev/cu.usbserial-0001',
                    'device_name' => 'ESP8266 Device',
                    'device_type' => 'ESP8266',
                    'vendor_id' => '10C4',
                    'product_id' => 'EA60',
                    'is_compatible' => true,
                    'description' => 'CP2102 USB to UART Bridge Controller'
                ],
                [
                    'port' => '/dev/cu.usbserial-0002',
                    'device_name' => 'Arduino Uno',
                    'device_type' => 'Arduino',
                    'vendor_id' => '2341',
                    'product_id' => '0043',
                    'is_compatible' => false,
                    'description' => 'Arduino Uno Rev3'
                ]
            ]
        ];
        
        // Randomly select a scenario for demo purposes
        $selectedScenario = $scenarios[array_rand($scenarios)];
        
        // Add some delay to simulate real detection
        usleep(500000); // 0.5 seconds
        
        return $selectedScenario;
    }
    
    /**
     * Get port information for a specific port
     */
    public function getPortInfo(Request $request, $port)
    {
        try {
            // Simulate port info retrieval
            $portInfo = [
                'port' => $port,
                'status' => 'available',
                'baud_rates' => [9600, 115200, 57600, 38400],
                'default_baud_rate' => 115200,
                'description' => 'Serial port information'
            ];
            
            return response()->json([
                'success' => true,
                'port_info' => $portInfo
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to get port info: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Test connection to a specific port
     */
    public function testConnection(Request $request)
    {
        try {
            $port = $request->input('port');
            $baudRate = $request->input('baud_rate', 115200);
            
            // Simulate connection test
            $success = rand(0, 1); // Random success/failure for demo
            
            if ($success) {
                return response()->json([
                    'success' => true,
                    'message' => "Connection to {$port} at {$baudRate} baud successful",
                    'connection_info' => [
                        'port' => $port,
                        'baud_rate' => $baudRate,
                        'status' => 'connected',
                        'device_responded' => true
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => "Failed to connect to {$port} at {$baudRate} baud",
                    'connection_info' => [
                        'port' => $port,
                        'baud_rate' => $baudRate,
                        'status' => 'failed',
                        'device_responded' => false
                    ]
                ], 400);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Connection test failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
