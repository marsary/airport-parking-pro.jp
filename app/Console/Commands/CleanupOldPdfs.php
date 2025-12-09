<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CleanupOldPdfs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cleanup-old-pdfs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'storage/app/print から30分以上経過した古い PDF ファイルを削除します';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('古いPDFファイル削除処理を実行します。');

        $storagePath = storage_path('app/print');
        if (!File::isDirectory($storagePath)) {
            $this->info("ディレクトリが存在しません: " . $storagePath);
            return;
        }
        $files = File::files($storagePath);
        $cutoff = now()->subMinutes(30); // 30分前の時刻を設定

        foreach ($files as $file) {
            // ファイルの最終更新日時がカットオフ時刻よりも古いかチェック
            if (File::lastModified($file->getPathname()) < $cutoff->timestamp) {
                File::delete($file->getPathname());
                $this->info($file->getFilename() . " のPDFファイルを削除しました" );
            }
        }

        $this->info('PDFファイル削除処理が完了しました。');
    }
}
