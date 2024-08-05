<?php

// app/Services/FirebaseService.php

namespace App\Http\Services;

use Kreait\Firebase\Factory;
use App\Models\FirebaseCredential;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\ServiceAccount;
use Illuminate\Support\Facades\Crypt;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FirebaseService
{
    protected $messaging;

    public function __construct()
    {
        // Retrieve credentials from the database
        $credentials = FirebaseCredential::first(); // Adjust as needed (e.g., use specific ID)

        if (!$credentials) {
            throw new \Exception('No Firebase credentials found');
        }

        $privateKey = str_replace("\\n", "\n", $credentials->private_key);
        // Create the ServiceAccount object
        $serviceAccount = ServiceAccount::fromValue([
            'type' => $credentials->type,
            'project_id' => $credentials->project_id,
            'private_key_id' => $credentials->private_key_id,
            'private_key' => $privateKey,
            'client_email' => $credentials->client_email,
            'client_id' => $credentials->client_id,
            'auth_uri' => $credentials->auth_uri,
            'token_uri' => $credentials->token_uri,
            'auth_provider_x509_cert_url' => $credentials->auth_provider_x509_cert_url,
            'client_x509_cert_url' => $credentials->client_x509_cert_url,
            'universe_domain' => $credentials->universe_domain,
        ]);

        // Initialize Firebase with the ServiceAccount
        $factory = (new Factory())->withServiceAccount($serviceAccount);
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
