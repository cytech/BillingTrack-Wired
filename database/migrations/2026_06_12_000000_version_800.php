<?php

use BT\Modules\Attachments\Models\Attachment;
use BT\Modules\Clients\Models\Client;
use BT\Modules\CompanyProfiles\Models\CompanyProfile;
use BT\Modules\Documents\Models\Document;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // fix null on custom_fields field_meta
        Schema::table('custom_fields', function (Blueprint $table) {
            $table->text('field_meta')->nullable()->change();
        });

        // fix default on tax_rate_id
        Schema::table('document_items', function (Blueprint $table) {
            $table->unsignedInteger('tax_rate_id')->default('0')->change();
        });

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

        // move email templates - laravel 13 storage default changed to storage_path('app/private')
        File::moveDirectory(storage_path('app/email_templates'), Storage::disk('local')->path('email_templates'));

        // move attachments directory to app/private
        File::moveDirectory(storage_path('attachments'), Storage::disk('local')->path('attachments'));

        // move company_profile logos to app/private
        $companyprofiles = CompanyProfile::whereNotNull('logo')->get();
        Storage::makeDirectory('companyprofile_logos');
        foreach ($companyprofiles as $companyprofile) {
            File::move(storage_path($companyprofile->logo), Storage::disk('local')->path('companyprofile_logos/'.$companyprofile->logo));
        }

        // create dompdf temp storage
        Storage::makeDirectory('dompdf');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {}
};
