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

    // 🔐 Enkripsi nama asli file untuk disimpan di database
    $encryptedOriginalName = EncryptHelper::encryptContent($originalName);

    // 🔐 Enkripsi nama file (tanpa karakter khusus)
    $encryptedNameRaw = EncryptHelper::encryptContent(pathinfo($originalName, PATHINFO_FILENAME));
    $encryptedNameSafe = preg_replace('/[^A-Za-z0-9]/', '', base64_encode($encryptedNameRaw));
    $encryptedName = $encryptedNameSafe . '.enc';

    // Simpan file terenkripsi ke storage
    $path = 'public/attachments/' . $encryptedName;
    Storage::put($path, $encryptedContent);

    // Simpan metadata ke database
    EncryptedFile::create([
        'original_name' => $originalName,
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

    // ✅ Hapus file terenkripsi & hasil dekripsi (jika ada)
    public function destroy($id)
    {
        $file = EncryptedFile::findOrFail($id);

        // Hapus file terenkripsi
        if (Storage::exists($file->path)) {
            Storage::delete($file->path);
        }

        // Hapus file hasil dekripsi juga jika ada
        $decryptedPath = 'public/attachments/decrypted_' . $file->original_name;
        if (Storage::exists($decryptedPath)) {
            Storage::delete($decryptedPath);
        }

        // Hapus data dari database
        $file->delete();

        return back()->with('success', 'File berhasil dihapus.');
    }
}
