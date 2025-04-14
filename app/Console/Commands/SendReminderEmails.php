<?php

namespace App\Console\Commands;

use App\Enums\DealStatus;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\Deal;
use App\Mail\ReminderEmail;
use ErrorException;

class SendReminderEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-reminder-emails {--test}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '取引データのうちの予約済みかつ、リマインドメール未送信のものに対して、リマインドメールを送信する';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isTest = $this->option('test');
        // 1. 開始ログ出力
        $this->info('リマインドメール送信バッチ処理を開始します');
        Log::channel('remind_mail')->info('リマインドメール送信バッチ処理を開始します');

        try {
            $query = Deal::query();
            if ($isTest) {
                $this->info('テストモードで実行します。');
                $query->where('remarks', 'like', '%テスト用%');
            }

            // 2. 取引テーブルからデータを取得
            $deals = $query->where('remind_mail_sent_flg', false)
                ->where('status', DealStatus::NOT_LOADED->value)
                ->get();

            Log::channel('remind_mail')->info('送信対象取引件数: ' . $deals->count());

            // 3. 各取引に対してメール送信処理
            foreach ($deals as $deal) {
                try {
                    if(isBlank($deal->email)) {
                        throw new ErrorException('この取引にはメールアドレスが設定されていません。');
                    }
                    // メール送信
                    Mail::to($deal->email)->send(new ReminderEmail($deal));

                    // 4. メール送信フラグを更新
                    $deal->remind_mail_sent_flg = true;
                    $deal->save();

                    Log::channel('remind_mail')->info('メール送信成功 - 取引ID: ' . $deal->id);
                } catch (\Exception $e) {
                    Log::channel('remind_mail')->error('メール送信失敗 - 取引ID: ' . $deal->id . ' - エラー: ' . $e->getMessage());
                }
            }

            // 5. 終了ログ出力
            Log::channel('remind_mail')->info('リマインドメール送信バッチ処理を終了します');
            $this->info('リマインドメール送信バッチ処理を終了します');
        } catch (\Throwable $th) {
            Log::channel('remind_mail')->error('バッチ処理でエラーが発生しました: ' . $th->getMessage());
            $this->error('バッチ処理でエラーが発生しました: ' . $th->getMessage());
        }
    }
}
