<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function post_client_files(Request $req, $id)
    {
        $uploadedFiles = [];

        if ($req->hasFile('files')) {
            $files = $req->file('files');

            foreach ($files as $file) {
                $path = $file->store('files', 's3');
                $uploadedFiles[] = $path;

                Document::create([
                    'user_id' => $id,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path
                ]);
            }
        }

        return response()->json(['files' => $uploadedFiles]);
    }

    public function get_files_client($id)
    {
        $files = Document::where('user_id', $id)->get();

        return response()->json($files);
    }

    public function delete_file_client($fileId, $id) {
        $userId = $id;
        $file = Document::where('user_id', $userId)->find($fileId);
        
        if (!$file) {
            return response()->json(['message' => 'Le fichier n\'a pas été trouvé.'], 404);
        }

        $s3Path = $file->file_path;

        try {
            // Supprime le fichier du bucket S3 en utilisant la classe Storage
            Storage::disk('s3')->delete($s3Path);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Une erreur s\'est produite lors de la suppression du fichier.'], 500);
        }

        // Suppression du fichier de la base de données
        $file->delete();

        return response()->json(['message' => 'Le fichier a été supprimé avec succès.']);
    }

    public function download_file($path) {

        $filePath = 'files/' . $path;

        if (Storage::disk('s3')->exists($filePath)) {
            $fileContent = Storage::disk('s3')->get($filePath);
            $mimeType = Storage::disk('s3')->mimeType($filePath);
    
            $headers = [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'attachment; filename="' . $filePath . '"'
            ];
    
            return response()->stream(function () use ($fileContent) {
                echo $fileContent;
            }, 200, $headers);
        } else {
            abort(404, 'Le fichier demandé n\'existe pas.');
        }
    }

}
