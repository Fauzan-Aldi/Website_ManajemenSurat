<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Helpers\EncryptHelper;

class FileEncryptController extends Controller
{
    public function encryptPdf($filename)
    {
        $path = "public/attachments/$filename";

        if (!Storage::exists($path)) {
            return response("File tidak ditemukan.", 404);
        }

        $fileContent = Storage::get($path);
        $encrypted = EncryptHelper::encryptContent($fileContent);

        $output = "public/attachments/enc_$filename.enc";
        Storage::put($output, $encrypted);

        return response("File berhasil dienkripsi: enc_$filename.enc");
    }

    public function decryptPdf($encryptedFilename)
    {
        $path = "public/attachments/$encryptedFilename";

        if (!Storage::exists($path)) {
            return response("File terenkripsi tidak ditemukan.", 404);
        }

        $encryptedContent = Storage::get($path);
        $decrypted = EncryptHelper::decryptContent($encryptedContent);

        $output = "public/attachments/decrypted_" . str_replace(['enc_', '.enc'], '', $encryptedFilename) . ".pdf";
        Storage::put($output, $decrypted);

        return response("File berhasil didekripsi: " . basename($output));
    }
    
}
