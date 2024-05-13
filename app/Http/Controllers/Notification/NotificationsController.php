<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\News\News;


class NotificationsController extends Controller
{
    public function index()
    {
        return view('notification.index');
    }
    public function notificationSend(Request $request)
    {
         $request->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => 'image' // If you want to allow image upload, you can remove 'required'
        ]);
        $title = $request->input('title');
        $description = $request->input('description');
        $image = $request->file('image');
        //start of Notification Code
        $apiKey = "AAAA9i8FWrM:APA91bG8ZUVpVOVZBAuZ0qHOYSko7Wzp-E4sl50qcenyY1bVV7stmxNQawShqGnXlDmQVCTePqlaZgF1TtU3FMkOkGanzkcOe3P2wktObcv4ByM-X_mTiGu_UD760ozf0qefPNPk3shr";
        //$apiKey = config('fcm.api_key');

        $data = [
            'title' => $title,
            'body' => $description,
            'image' => $image,
        ];
        // Fetch the latest news article from the database
        $latestNews = News::orderBy('created_at', 'desc')->first();


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
        //dd($response);
        //End of Notitification code
        // return redirect->route('route.name')->with('success', 'Task completed successfully');
        return redirect('/notification');
    }
    public function notification()
    {
        return view('notification.admin-notification');
    }
    public function notificationGive(News $news)
    {
        $title = $news->title;
        $description = $news->description;
        $image = $news->image; // Assuming image is stored as a file path

      $apiKey = "AAAA9i8FWrM:APA91bG8ZUVpVOVZBAuZ0qHOYSko7Wzp-E4sl50qcenyY1bVV7stmxNQawShqGnXlDmQVCTePqlaZgF1TtU3FMkOkGanzkcOe3P2wktObcv4ByM-X_mTiGu_UD760ozf0qefPNPk3shr";

        $data = [
            'title' => $title,
            'body' => $description,
            'image' => $image,
        ];
        $payload = [
            'to' => '/topics/all',
            // 'to' => "" . $fcmToken, // Use the FCM token from the request
            //'notification' => $data,
            'data' => [
                'news' => json_encode($news),
                "click_action" => "FLUTTER_NOTIFICATION_CLICK",
                "sound" => "default",
                'title' => $title,
                'image' => $image,
                'body' => $description,
                'type' => 'news'
      
            ]
        ];
        $response = Http::withHeaders([
            'Authorization' => 'key=' . $apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://fcm.googleapis.com/fcm/send', $payload);
        //dd($response);
        //End of Notitification code
        // return redirect->route('route.name')->with('success', 'Task completed successfully');
        return redirect('/news')->with('success', 'Notification sent successfully.');
    }
}