<?php

namespace BT\Http\Livewire\Modals;

use BT\Modules\CustomFields\Models\CustomField;
use BT\Modules\Invoices\Models\Invoice;
use BT\Modules\MailQueue\Support\MailQueue;
use BT\Modules\PaymentMethods\Models\PaymentMethod;
use BT\Modules\Payments\Models\Payment;
use BT\Support\Contacts;
use BT\Support\Parser;
use Illuminate\Support\Str;
use Livewire\Component;

class CreatePaymentModal extends Component
{
    public $paymentdate, $paymentmethods = [], $amount, $payment_method_id, $payment_note, $readonly;
    public $email_payment_receipt, $customFields, $custom_data =[];
    public $resource_id, $resource_name, $client_invoices = [], $invoice_id;
    public $module, $modulefullname, $moduletype, $module_id, $currentUrl;

    protected $listeners = ['resource_idUpdated'   => 'setResourceId',
                            'descriptionUpdated' => 'setResourceName',];
    protected $rules = [
        'resource_id'         => 'required',
        'paymentdate'           => 'required',
        'invoice_id'        => 'required',
        'amount'            => 'required|numeric',
        'payment_method_id' => 'required',
    ];

    public function mount($modulefullname = null, $module_id = null, $readonly = null){
        $this->currentUrl = url()->previous();
        $this->paymentdate = date('Y-m-d');
        $this->paymentmethods = PaymentMethod::getList();
        $this->payment_method_id = config('bt.onlinePaymentMethod');
        $this->email_payment_receipt = (bool)config('bt.automaticEmailPaymentReceipts');
        $this->customFields = CustomField::forTable('payments')->get();

        if ($module_id) {
            $this->modulefullname = $modulefullname;
            // strip model name from full namespace
            $this->moduletype = Str::afterLast($modulefullname, '\\');
            $module_model = $modulefullname::find($module_id);
            $this->module = $module_model;
            $this->resource_id = $module_model->client_id;
            $this->invoice_id = $module_model->id;
            $this->amount = $module_model->amount->formatted_numeric_balance;
        }

        if ($readonly) {
            $this->readonly = true;
        }
    }

    public function setResourceId($object)
    {
        $this->resource_id = $object['value'];
        $this->client_invoices = Invoice::whereHas( 'client', function ($query) use ($object){
            $query->where('id', $object['value']);
        })->whereHas( 'amount', function ($query){
            $query->where('balance', '>', 0);
        })->sent()->get();

        $this->resetValidation();
    }

    public function setResourceName($object)
    {
        $this->resource_name = $object['description'];
    }

    public function updatedInvoiceId(){
        $this->amount = $this->client_invoices->find($this->invoice_id)->amount->formatted_numeric_balance;
    }

    public function validationAttributes()
    {
        return [
            'resource_id'        => trans('bt.client'),
            'paymentdate'           => trans('bt.payment_date'),
            'invoice_id'        => trans('bt.invoice'),
            'amount'            => trans('bt.amount'),
            'payment_method_id' => trans('bt.payment_method'),
        ];
    }

    public function updatedPaymentdate()
    {
        $this->validateOnly('paymentdate');
    }

    public function doCancel()
    {
        $this->emit('refreshSearch', ['searchTerm' => null, 'value' => null, 'description' => null, 'optionsValues' => null]);
        $this->emit('hideModal');
    }

    public function createPayment(){
        $createfields = [
            'client_id' => $this->resource_id,
                'invoice_id' => $this->invoice_id,
                'amount' => $this->amount,
                'payment_method_id' => $this->payment_method_id,
                'paid_at' => $this->paymentdate,
                'note' => $this->payment_note ?? '',
        ];

        $this->validate();

        $swaldata['message'] = __('bt.saving');
        $this->dispatchBrowserEvent('swal:saving', $swaldata);

        $module = Payment::create($createfields);

        $module->custom->update($this->custom_data,[]);

        // Close Modal After Logic
        $this->emit('hideModal');

        // email receipt
        if ($this->email_payment_receipt == true
            or !$this->email_payment_receipt and config('bt.automaticEmailPaymentReceipts') and $module->invoice->client->email) {
            $parser = new Parser($module);

            $contacts = new Contacts($module->invoice->client);

            $mailQueue = new MailQueue();

            $mail = $mailQueue->create($module, [
                'to'         => $contacts->getSelectedContactsTo(),
                'cc'         => $contacts->getSelectedContactsCc(),
                'bcc'        => $contacts->getSelectedContactsBcc(),
                'subject'    => $parser->parse('paymentReceiptEmailSubject'),
                'body'       => $parser->parse('paymentReceiptBody'),
                'attach_pdf' => config('bt.attachPdf'),
            ]);

            $mailQueue->send($mail->id);
        }

        if ($this->module){
            return redirect($this->currentUrl)
                ->with('alertSuccess', trans('bt.record_successfully_created'));
        }else {
            return redirect()->route('payments.index')
                ->with('alertSuccess', trans('bt.record_successfully_created'));
        }
    }

    public function render()
    {
        return view('livewire.modals.create-payment-modal');
    }
}
