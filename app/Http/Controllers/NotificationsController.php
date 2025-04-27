<?php

namespace App\Http\Controllers;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NotificationsController extends Controller
{
    public function send_like_notif(Request $request){
        $contents = $request->query('contents');
    $subscriptionIds = [$request->query('subscriptionIds')];
    $url = $request->query('url');
    $userId = $request->query('userId');
    $initiatorId = $request->query('initiatorId');

    try{
        $res = Http::withHeaders([
            'Authorization' => 'Key os_v2_app_oryl7ynk6rbglixjttwromxvpuxds5pw3tfui6ewkcmbfeu2swiuevpk74yexmoujfthbizbwadcbjviwnuhbx25ddkdrecx5s6zs5q',
            'accept' => 'application/json',
            'content-type' => 'application/json',
        ])->post("https://api.onesignal.com/notifications?c=push",[
            'app_id' => '7470bfe1-aaf4-4265-a2e9-9ced1732f57d',
            "include_subscription_ids" => $subscriptionIds,
            'contents' => ['en' => $contents],
            'url' => $url
        ]);

        Notification::create([
            'user_id' => $userId,
            'from_user_id' => $initiatorId,
            'content' => $contents,
            'type' => false,
        ]);

        return $res->body();

    }catch( \Exception $e){
        report($e);
        return response()->json(['Eror' => $e->getMessage()], 500);
    }
    }

    public function send_comment_notif(Request $request){
        $contents = $request->query('contents');
    $subscriptionIds = [$request->query('subscriptionIds')];
    $url = $request->query('url');
    $userId = $request->query('userId');
    $initiatorId = $request->query('initiatorId');

    try{
        $res = Http::withHeaders([
            'Authorization' => 'Key os_v2_app_oryl7ynk6rbglixjttwromxvpuxds5pw3tfui6ewkcmbfeu2swiuevpk74yexmoujfthbizbwadcbjviwnuhbx25ddkdrecx5s6zs5q',
            'accept' => 'application/json',
            'content-type' => 'application/json',
        ])->post("https://api.onesignal.com/notifications?c=push",[
            'app_id' => '7470bfe1-aaf4-4265-a2e9-9ced1732f57d',
            "include_subscription_ids" => $subscriptionIds,
            'contents' => ['en' => $contents],
            'url' => $url
        ]);
        
        Notification::create([
            'user_id' => $userId,
            'from_user_id' => $initiatorId,
            'content' => $contents,
            'type' => false,
        ]);

        return $res->body();

    }catch( \Exception $e){
        report($e);
        return response()->json(['Eror' => $e->getMessage()], 500);
    }
    }

    public function send_post_notif(Request $request){
        $contents = $request->query('contents');
    $subscriptionIds = [$request->query('subscriptionIds')];
    $url = $request->query('url');
    $userId = $request->query('userId');
    $initiatorId = $request->query('initiatorId');

    try{
        $res = Http::withHeaders([
            'Authorization' => 'Key os_v2_app_oryl7ynk6rbglixjttwromxvpuxds5pw3tfui6ewkcmbfeu2swiuevpk74yexmoujfthbizbwadcbjviwnuhbx25ddkdrecx5s6zs5q',
            'accept' => 'application/json',
            'content-type' => 'application/json',
        ])->post("https://api.onesignal.com/notifications?c=push",[
            'app_id' => '7470bfe1-aaf4-4265-a2e9-9ced1732f57d',
            "include_subscription_ids" => $subscriptionIds,
            'contents' => ['en' => $contents],
            'url' => $url
        ]);

        Notification::create([
            'user_id' => $userId,
            'from_user_id' => $initiatorId,
            'content' => $contents,
            'type' => false,
        ]);

        return $res->body();

    }catch( \Exception $e){
        report($e);
        return response()->json(['Eror' => $e->getMessage()], 500);
    }
    }

    public function send_follow_notif(Request $request){
        $contents = $request->query('contents');
    $subscriptionIds = [$request->query('subscriptionIds')];
    $url = $request->query('url');
    $userId = $request->query('userId');
    $initiatorId = $request->query('initiatorId');

    try{
        $res = Http::withHeaders([
            'Authorization' => 'Key os_v2_app_oryl7ynk6rbglixjttwromxvpuxds5pw3tfui6ewkcmbfeu2swiuevpk74yexmoujfthbizbwadcbjviwnuhbx25ddkdrecx5s6zs5q',
            'accept' => 'application/json',
            'content-type' => 'application/json',
        ])->post("https://api.onesignal.com/notifications?c=push",[
            'app_id' => '7470bfe1-aaf4-4265-a2e9-9ced1732f57d',
            "include_subscription_ids" => $subscriptionIds,
            'contents' => ['en' => $contents],
            'url' => $url
        ]);

        Notification::create([
            'user_id' => $userId,
            'from_user_id' => $initiatorId,
            'content' => $contents,
            'type' => false,
        ]);

    
        return $res->body();

    }catch( \Exception $e){
        report($e);
        return response()->json(['Eror' => $e->getMessage()], 500);
    }
    }

    public function navbarNotifications()
{
    // Fetch only the notifications where 'type' is false
    $notifications = Notification::where('user_id', auth()->id())
        ->where('type', false)  // Filter by type being false
        ->orderBy('created_at', 'desc')
        ->limit(10)
        ->get();

    // Update the type to true for all the notifications fetched
    Notification::where('user_id', auth()->id())
        ->where('type', false)
        ->update(['type' => true]);

    // Map through the notifications and add a human-readable time
    $notifications = $notifications->map(function ($notification) {
        // Add a new formatted time key to the notification
        $notification->formatted_created_at = $notification->created_at->diffForHumans();
        return $notification;
    });

    // Return the notifications as a JSON response
    return response()->json($notifications);
}
    
}
