<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('firebase_credentials', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('project_id');
            $table->longText('private_key');
            $table->string('private_key_id');
            $table->string('client_email');
            $table->string('client_id');
            $table->string('auth_uri');
            $table->string('token_uri');
            $table->string('auth_provider_x509_cert_url');
            $table->string('client_x509_cert_url');
            $table->string('universe_domain');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('firebase_credentials');
    }
};
