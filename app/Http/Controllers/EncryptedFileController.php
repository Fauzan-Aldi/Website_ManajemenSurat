<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\EncryptedFile;
use App\Helpers\EncryptHelper;

class EncryptedFileController extends Controller
{
    // Menampilkan daftar file terenkripsi (dengan pencarian opsional)
    public function index(Request $request)
{
    $files = EncryptedFile::all();

    // Pencarian berdasarkan nama (setelah dekripsi)
    if ($request->has('search')) {
        $search = strtolower($request->search);
        $files = $files->filter(function ($file) use ($search) {
            $decryptedName = EncryptHelper::decryptContent($file->original_name);
            return stripos($decryptedName, $search) !== false;
        });
    }

    return view('files.index', compact('files'));
}


    // Upload dan enkripsi file PDF
    public function upload(Request $request)
{
    $request->validate([
        'file_pdf' => 'required|file|mimes:pdf',
    ]);

    $originalFile = $request->file('file_pdf');
    $originalName = $originalFile->getClientOriginalName();
    $fileContent = file_get_contents($originalFile);

    // 🔐 Enkripsi isi file
    $encryptedContent = EncryptHelper::encryptContent($fileContent);

    // 🔐 Enkripsi nama asli file
    $encryptedOriginalName = EncryptHelper::encryptContent($originalName);

    // Nama file enkripsi yang disimpan di storage
    $encryptedName = 'enc_' . time() . '.pdf.enc';
    $path = 'public/attachments/' . $encryptedName;

    // Simpan ke storage
    Storage::put($path, $encryptedContent);

    // Simpan ke database dengan nama asli yang sudah terenkripsi
    EncryptedFile::create([
        'original_name' => $encryptedOriginalName, // <- sudah terenkripsi
        'encrypted_name' => $encryptedName,
        'path' => $path,
    ]);

    return back()->with('success', 'File berhasil dienkripsi dan disimpan.');
}

    // Dekripsi file berdasarkan ID
    public function decrypt($id)
    {
        $file = EncryptedFile::findOrFail($id);
        $encrypted = Storage::get($file->path);

        $decrypted = EncryptHelper::decryptContent($encrypted);

        $decryptedName = 'decrypted_' . $file->original_name;
        $decryptedPath = 'public/attachments/' . $decryptedName;
        Storage::put($decryptedPath, $decrypted);

        return back()->with('success', 'File berhasil didekripsi: ' . $decryptedName);
    }

    // Download file yang sudah didekripsi
    public function download($id)
    {
        $file = EncryptedFile::findOrFail($id);
        $decryptedName = 'decrypted_' . $file->original_name;
        $decryptedPath = 'public/attachments/' . $decryptedName;

        if (!Storage::exists($decryptedPath)) {
            return back()->with('error', 'File belum didekripsi.');
        }

        return response()->download(storage_path('app/' . $decryptedPath));
    }
}
