<?php

namespace App\Http\Controllers\API\Package;

use App\Http\Controllers\Controller;
use App\Models\Package\Package;
use Illuminate\Http\Request;
use App\Services\Package\CreatePackageService;
use Illuminate\Support\Facades\Validator;
use Exception;

class PackageController extends Controller
{
    protected $createPackageService;

    public function __construct(CreatePackageService $createPackageService)
    {
        $this->createPackageService = $createPackageService;
    }

    public function storePackage(Request $request)
    {
        $user = auth()->user();

        if (!$user || $user->role_id != 1) {
            return response()->json([
                'message' => 'You do not have permission to access this resource',
            ], 403);
        }

        $validatedData = $request->validate([
            'package_category_id' => 'required|exists:package_categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'status' => 'required|boolean',
            'items' => 'required|array|min:1',
            'items.*.ticket_type_id' => 'nullable|exists:ticket_types,id',
            'items.*.item_id' => 'nullable|exists:items,id',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        try {
            $package = $this->createPackageService->execute($validatedData);

            return response()->json([
                'message' => 'Package created successfully',
                'data' => $package,
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to create package',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    public function index(){
        try {
            $user = auth()->user();

            if (!$user || $user->role_id != 1) {
                return response()->json([
                    'message' => 'You do not have permission to access this resource',
                ], 403);
            }

            $packages = Package::with(['packageCategory', 'packageDetails.item', 'packageDetails.ticketType'])
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'message' => 'Packages retrieved successfully',
                'data' => $packages,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve packages',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(string $id){
        try {
            $user = auth()->user();
            if (!$user || $user->role_id != 1) {
                return response()->json([
                    'message' => 'You do not have permission to access this resource',
                ], 403);
            }
            $package = Package::with(['packageCategory', 'packageDetails.item', 'packageDetails.ticketType'])
                ->where('id', $id)
                ->first();
            return response()->json([
                'message' => 'Package retrieved successfully',
                'data' => $package,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve package',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
