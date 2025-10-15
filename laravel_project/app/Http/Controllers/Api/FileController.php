<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Exception;

class FileController extends Controller
{
    /**
     * Upload a single file and return its URI
     */
    public function upload(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'file' => 'required|file|max:10240', // 10MB max
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            if ($request->hasFile('file')) {
                $uploadedFile = $request->file('file');
                
                // Generate unique filename
                $fileName = time() . '_' . $uploadedFile->getClientOriginalName();
                
                // Store file
                $filePath = $uploadedFile->storeAs('files', $fileName, 'public');
                
                // Generate file URL
                $fileUrl = asset('storage/' . $filePath);

                return response()->json([
                    'success' => true,
                    'message' => 'File uploaded successfully',
                    'data' => [
                        'uri' => $fileUrl,
                        'filename' => $fileName,
                        'original_name' => $uploadedFile->getClientOriginalName(),
                        'size' => $uploadedFile->getSize(),
                        'mime_type' => $uploadedFile->getMimeType()
                    ]
                ], 201);
            }

            return response()->json([
                'success' => false,
                'message' => 'No file provided'
            ], 400);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error uploading file',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload multiple files and return their URIs
     */
    public function uploadMultiple(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'files' => 'required|array|max:10',
                'files.*' => 'required|file|max:10240', // 10MB max per file
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $uploadedFiles = [];
            
            foreach ($request->file('files') as $uploadedFile) {
                $fileName = time() . '_' . uniqid() . '_' . $uploadedFile->getClientOriginalName();
                $filePath = $uploadedFile->storeAs('files', $fileName, 'public');
                
                $uploadedFiles[] = [
                    'uri' => asset('storage/' . $filePath),
                    'filename' => $fileName,
                    'original_name' => $uploadedFile->getClientOriginalName(),
                    'size' => $uploadedFile->getSize(),
                    'mime_type' => $uploadedFile->getMimeType()
                ];
            }

            return response()->json([
                'success' => true,
                'message' => 'Files uploaded successfully',
                'data' => $uploadedFiles
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error uploading files',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a file by filename
     */
    public function delete(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'filename' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $filename = $request->filename;
            $filePath = 'files/' . $filename;
            
            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
                
                return response()->json([
                    'success' => true,
                    'message' => 'File deleted successfully'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'File not found'
            ], 404);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting file',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}