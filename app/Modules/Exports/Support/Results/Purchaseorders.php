<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\Exports\Support\Results;

use BT\Modules\Documents\Models\Purchaseorder;

class Purchaseorders implements SourceInterface
{
    public function getResults($params = [])
    {
        $purchaseorder = Purchaseorder::select('documents.number', 'documents.created_at', 'documents.updated_at', 'documents.document_date',
            'documents.action_date', 'documents.terms', 'documents.footer', 'documents.url_key', 'documents.currency_code',
            'documents.exchange_rate', 'documents.template', 'documents.summary', 'groups.name AS group', 'clients.name AS client_name',
            'clients.email AS client_email', 'clients.address AS client_address', 'clients.city AS client_city',
            'clients.state AS client_state', 'clients.zip AS client_zip', 'clients.country AS client_country',
            'users.name AS user_name', 'users.email AS user_email',
            'company_profiles.company AS company', 'company_profiles.address AS company_address',
            'company_profiles.city AS company_city', 'company_profiles.state AS company_state',
            'company_profiles.zip AS company_zip', 'company_profiles.country AS company_country',
            'document_amounts.subtotal', 'document_amounts.tax', 'document_amounts.total',
            'document_amounts.paid', 'document_amounts.balance')
            ->join('document_amounts', 'document_amounts.document_id', '=', 'documents.id')
            ->join('clients', 'clients.id', '=', 'documents.client_id')
            ->join('groups', 'groups.id', '=', 'documents.group_id')
            ->join('users', 'users.id', '=', 'documents.user_id')
            ->join('company_profiles', 'company_profiles.id', '=', 'documents.company_profile_id')
            ->orderBy('number');

        return $purchaseorder->get()->makeHidden(['formatted_document_date', 'formatted_action_date', 'formatted_summary'])->toArray();
    }
}
