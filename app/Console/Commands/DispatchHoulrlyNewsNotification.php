<?php

namespace App\Console\Commands;

use App\Models\News\News;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DispatchHoulrlyNewsNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:dispatch-houlrly-news-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $latestNews = News::inRandomOrder()->first();
        Log::info($latestNews);
        $title = $latestNews->cetegory_id;
        $description = $latestNews->description;
        $image = $latestNews->image;
        //start of Notification Code
        $apiKey = "AAAA9i8FWrM:APA91bG8ZUVpVOVZBAuZ0qHOYSko7Wzp-E4sl50qcenyY1bVV7stmxNQawShqGnXlDmQVCTePqlaZgF1TtU3FMkOkGanzkcOe3P2wktObcv4ByM-X_mTiGu_UD760ozf0qefPNPk3shr";
        //$apiKey = config('fcm.api_key');

        $data = [
            'title' => $title,
            'body' => $description,
            'image' => $image,
        ];


        $payload = [
            'to' => '/topics/all',
            // 'to' => "" . $fcmToken, // Use the FCM token from the request
            'notification' => $data,
            'data' => [
                'news' => json_encode($latestNews),
                "click_action" => "FLUTTER_NOTIFICATION_CLICK",
                "sound" => "default",
                'title' => $title,
                'body' => $description,
                'type' => 'news'
      
            ]
        ];
        $response = Http::withHeaders([
            'Authorization' => 'key=' . $apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://fcm.googleapis.com/fcm/send', $payload);
        Log::info($response);
    }
}
