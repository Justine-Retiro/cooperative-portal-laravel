<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $client;
    public $loan;
    public $payment;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($client, $loan, $payment)
    {
        $this->client = $client;
        $this->loan = $loan;
        $this->payment = $payment;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Invoice Mail',
        );
    }

    public function build()
    {
        $transactionHistories = $this->payment->transactionHistories();
        $specificTransactionType = $transactionHistories->first()?->transaction_type; // Example, adjust based on need

        return $this->from('cooperative.partners.2324@gmail.com', 'Credit Cooperative')
                    ->to($this->client->user->email, $this->client->user->name)
                    ->bcc('ocooperative.partners.2324@gmail.com')
                    ->subject('Invoice Mail')
                    ->markdown('emails.invoice', [
                        'name' => $this->client->user->name,
                        'paymentDate' => $this->payment->created_at->format('m-d-y h:i A'),
                        'transaction' => $specificTransactionType,
                        'referenceNumber' => $this->payment->reference_no,
                        'amountPaid' => $this->payment->amount_paid,
                        'remarks' => $this->payment->payment_pivot->first()->remarks,
                    ]);
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.invoice',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
