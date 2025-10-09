<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\IotDevice;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IotManagementController extends Controller
{
    /**
     * Display IoT management page
     */
    public function index()
    {
        // Get devices with their classes
        $devices = IotDevice::with('kelas')
            ->orderBy('name')
            ->get();

        // Get classes for dropdown
        $classes = Kelas::orderBy('name')->get();

        // Calculate statistics
        $connectedDevices = $devices->where('status', 'connected')->count();
        $disconnectedDevices = $devices->where('status', 'disconnected')->count();
        $totalDataPoints = $devices->sum('data_points') ?? 0;
        $adrenoDevices = $devices->where('platform', 'adreno')->count();

        return view('superadmin.iot-management-new', compact(
            'devices',
            'classes',
            'connectedDevices',
            'disconnectedDevices',
            'totalDataPoints',
            'adrenoDevices'
        ));
    }

    /**
     * Store new IoT device
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'connection_type' => 'required|string|in:usb,ethernet,serial',
            'device_id' => 'nullable|string|max:100|unique:iot_devices,device_id',
            'description' => 'nullable|string|max:1000',
            'location' => 'nullable|string|max:255',
            'class_id' => 'nullable|exists:kelas,id',
        ]);

        try {
            DB::beginTransaction();

            $device = IotDevice::create([
                'name' => $request->name,
                'connection_type' => $request->connection_type,
                'device_id' => $request->device_id,
                'description' => $request->description,
                'location' => $request->location,
                'class_id' => $request->class_id,
                'platform' => 'adreno', // All devices use Adreno platform
                'status' => 'disconnected', // Default status
                'data_points' => 0,
            ]);

            DB::commit();

            return redirect()->route('superadmin.iot-management.new')
                ->with('success', 'Perangkat IoT berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show edit form for device
     */
    public function edit($id)
    {
        $device = IotDevice::with('kelas')->findOrFail($id);
        
        return response()->json([
            'id' => $device->id,
            'name' => $device->name,
            'connection_type' => $device->connection_type,
            'device_id' => $device->device_id,
            'description' => $device->description,
            'location' => $device->location,
            'class_id' => $device->class_id,
        ]);
    }

    /**
     * Update device
     */
    public function update(Request $request, $id)
    {
        $device = IotDevice::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'connection_type' => 'required|string|in:usb,ethernet,serial',
            'device_id' => 'nullable|string|max:100|unique:iot_devices,device_id,' . $id,
            'description' => 'nullable|string|max:1000',
            'location' => 'nullable|string|max:255',
            'class_id' => 'nullable|exists:kelas,id',
        ]);

        try {
            DB::beginTransaction();

            $device->update([
                'name' => $request->name,
                'connection_type' => $request->connection_type,
                'device_id' => $request->device_id,
                'description' => $request->description,
                'location' => $request->location,
                'class_id' => $request->class_id,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Perangkat IoT berhasil diperbarui!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete device
     */
    public function destroy($id)
    {
        $device = IotDevice::findOrFail($id);

        try {
            DB::beginTransaction();

            $device->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Perangkat IoT berhasil dihapus!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Connect device
     */
    public function connect($id)
    {
        $device = IotDevice::findOrFail($id);

        try {
            $device->update([
                'status' => 'connected',
                'last_connected' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Perangkat berhasil terhubung!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Disconnect device
     */
    public function disconnect($id)
    {
        $device = IotDevice::findOrFail($id);

        try {
            $device->update([
                'status' => 'disconnected',
                'last_disconnected' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Perangkat berhasil terputus!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get device data
     */
    public function getDeviceData($id)
    {
        $device = IotDevice::with('kelas')->findOrFail($id);
        
        return response()->json([
            'device' => $device,
            'data_points' => $device->data_points ?? 0,
            'last_activity' => $device->last_connected ?? 'Tidak pernah',
        ]);
    }
}
