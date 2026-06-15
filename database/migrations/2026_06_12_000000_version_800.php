<?php

use BT\Modules\Attachments\Models\Attachment;
use BT\Modules\Clients\Models\Client;
use BT\Modules\Documents\Models\Document;
use BT\Modules\Settings\Models\Setting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // fix missing url_key on VERY old clients, documents and attachments
        $clients = Client::where('url_key', null)->orWhere('url_key', '')->get();
        foreach ($clients as $client) {
            $client->url_key = str_random(32);
            $client->save();
        }

        $attachments = Attachment::where('url_key', null)->orWhere('url_key', '')->get();
        foreach ($attachments as $attachment) {
            $attachment->url_key = str_random(64);
            $attachment->save();
        }

        $documents = Document::where('url_key', null)->orWhere('url_key', '')->get();
        foreach ($documents as $document) {
            $document->url_key = str_random(32);
            $document->save();
        }

        // re-write email templates - laravel 13 storage default changed to storage_path('app/private'),
        Setting::writeEmailTemplates();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {}
};
