<?php

// app/Services/FirebaseService.php

namespace App\Http\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FirebaseService
{
    protected $messaging;

    public function __construct()
    {
        $factory = (new Factory)->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')));
        $this->messaging = $factory->createMessaging();
    }

    public function sendNotification($title, $body, $imageUrl, $url)
    {
        $message = CloudMessage::fromArray([
            'topic' => 'onetv',
            'notification' => Notification::create($title, $body, $imageUrl),
            'data' => [
                'url' => $url,
            ],
        ]);

        return $this->messaging->send($message);
    }
}
