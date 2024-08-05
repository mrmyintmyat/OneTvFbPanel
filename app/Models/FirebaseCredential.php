<?php

namespace App\Models;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FirebaseCredential extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'project_id', 'private_key_id', 'private_key', 'client_email',
        'client_id', 'auth_uri', 'token_uri', 'auth_provider_x509_cert_url',
        'client_x509_cert_url', 'universe_domain'
    ];

    // Encrypt private_key attribute when setting it
    public function setPrivateKeyAttribute($value)
    {
        $this->attributes['private_key'] = Crypt::encryptString($value);
    }

    // Decrypt private_key attribute when accessing it
    public function getPrivateKeyAttribute($value)
    {
        return Crypt::decryptString($value);
    }
}
