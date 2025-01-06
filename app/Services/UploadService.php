<?php
namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UploadService
{

    /**
     * アップロードファイルを public/uploads フォルダの指定のパスに保存する
     *
     * @param UploadedFile $uploadedFile
     * @param string $uploadPath
     * @return string $path
     * @throws HttpException
     */
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
