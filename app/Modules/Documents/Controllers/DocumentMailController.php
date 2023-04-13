<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\Documents\Controllers;

use BT\Events\DocumentEmailed;
use BT\Events\DocumentEmailing;
use BT\Http\Controllers\Controller;
use BT\Modules\MailQueue\Support\MailQueue;
use BT\Modules\Documents\Models\Document;
use BT\Requests\SendEmailRequest;
use BT\Support\Contacts;
use BT\Support\Parser;

class DocumentMailController extends Controller
{
    private $mailQueue;

    public function __construct(MailQueue $mailQueue)
    {
        $this->mailQueue = $mailQueue;
    }

    public function create()
    {
        $document = Document::find(request('document_id'));

        $contacts = new Contacts($document->client);

        $parser = new Parser($document);

        return view('documents._modal_mail')
            ->with('documentId', $document->id)
            ->with('redirectTo', urlencode(request('redirectTo')))
            ->with('subject', $parser->parse(strtolower($document->module_type) . 'EmailSubject'))
            ->with('body', $parser->parse(strtolower($document->module_type) . 'EmailBody'))
            ->with('contactDropdownTo', $contacts->contactDropdownTo())
            ->with('contactDropdownCc', $contacts->contactDropdownCc())
            ->with('contactDropdownBcc', $contacts->contactDropdownBcc());
    }

    public function store(SendEmailRequest $request)
    {
        $document = Document::find($request->input('document_id'));

        event(new DocumentEmailing($document));

        $mail = $this->mailQueue->create($document, $request->except('document_id'));

        if ($this->mailQueue->send($mail->id))
        {
            event(new DocumentEmailed($document));
        }
        else
        {
            return response()->json(['errors' => [[$this->mailQueue->getError()]]], 400);
        }
    }
}
