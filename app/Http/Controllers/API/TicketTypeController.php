<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class TicketTypeController extends Controller
{
    // Menampilkan semua data ticket type dengan user role super admin / role_id = 1
    public function index()
    {
        try {
            $user = auth()->user();
            if ($user->role_id != 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'You dont have permission to access this resource',
                ], 403);
            }

            $ticketType = \App\Models\TicketType::all();
            return response()->json([
                'success' => true,
                'message' => 'List All Ticket Type',
                'data' => $ticketType
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve ticket type data',
                'error' => $e->getMessage(),
            ]);
        }
    }

    // Alur untuk menambahkan jenis tiket baru untuk role super admin / role_id = 1
    public function store(Request $request)
    {
        try {
            $user = auth()->user();
            if ($user->role_id != 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'You dont have permission to access this resource',
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'clubhouse_id' => 'nullable|exists:clubhouses,id',
                'type_ticket' => 'required',
                'name' => 'required|string|max:255',
                'price' => 'required|integer',
                'duration' => 'required|integer',
                'status' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $ticketType = new \App\Models\TicketType();
            $ticketType->clubhouse_id = $request->clubhouse_id;
            $ticketType->type_ticket = $request->type_ticket;
            $ticketType->name = $request->name;
            $ticketType->price = $request->price;
            $ticketType->duration = $request->duration;
            $ticketType->status = $request->status;
            $ticketType->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Ticket Type created successfully',
                'data' => $ticketType
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create ticket type',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Alur untuk menampilkan jenis tiket sesuai dengan id ticket type hanya diakses oleh role_id = 1
    public function show(string $id)
    {
        try {
            $user = auth()->user();
            if ($user->role_id != 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'You dont have permission to access this resource',
                
                ], 403);
            }

            $ticketType = \App\Models\TicketType::find($id);
            return response()->json([
                'success' => true,
                'message' => 'Ticket Type retrieved successfully',
                'data' => $ticketType
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve ticket type',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Alur untuk melakukan update data ticket type sesuai dengan id ticket type hanya diakses oleh role_id = 1
    public function update(Request $request, string $id)
    {
        try {
            $user = auth()->user();
            if ($user->role_id != 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'You dont have permission to access this resource',
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'clubhouse_id' => 'nullable|exists:clubhouses,id',
                'type_ticket' => 'required',
                'name' => 'required|string|max:255',
                'price' => 'required|integer',
                'duration' => 'required|integer',
                'status' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $ticketType = \App\Models\TicketType::find($id);
            $ticketType->clubhouse_id = $request->clubhouse_id;
            $ticketType->type_ticket = $request->type_ticket;
            $ticketType->name = $request->name;
            $ticketType->price = $request->price;
            $ticketType->duration = $request->duration;
            $ticketType->status = $request->status;
            $ticketType->save();

            return response()->json([
                'success' => true,
                'message' => 'Ticket Type updated successfully',
                'data' => $ticketType
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update ticket type',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Alur untuk menghapus data tiket type menurut ID nya hanya dengan role_id = 1
    public function destroy(string $id)
    {
        try {
            $user = auth()->user();
            if ($user->role_id != 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'You dont have permission to access this resource',
                ], 403);
            }

            $ticketType = \App\Models\TicketType::find($id);
            $ticketType->delete();

            return response()->json([
                'success' => true,
                'message' => 'Ticket Type deleted successfully',   
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete ticket type',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
