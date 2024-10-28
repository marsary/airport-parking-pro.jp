<?php
namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UploadService
{

    public static function saveFile(UploadedFile $uploadedFile, $uploadPath = '/')
    {
        // アップロードされたファイル名を取得
        $fileName = $uploadedFile->getClientOriginalName();

        $path = Storage::disk('uploads')->putFileAs($uploadPath, $uploadedFile, $fileName);

        if($path == false) {
            abort('500', 'ファイルのアップロードに失敗しました。');
        }

        return $path;
    }
}
