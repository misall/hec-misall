<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AzureBlobService;

class AzureFileController extends Controller
{
    public function index(AzureBlobService $azure)
    {
        $files = $azure->listFilesWithSas();
        return view('azure.index', compact('files'));
    }

    public function upload(Request $request, AzureBlobService $azure)
    {
        $request->validate([
            'fichier' => 'required|file|max:10240'
        ]);
        $file = $request->file('fichier');
        $azure->uploadFile($file);

        return redirect('/')->with('success', 'Fichier envoyé avec succès ✅');
    }

    public function destroy($nom, AzureBlobService $azure)
    {
        $azure->deleteFile($nom);
        return redirect('/')->with('success', "Fichier supprimé avec succès : $nom");
    }
}
