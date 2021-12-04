<?php

namespace Webkul\Marketplace\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Contact Seller Mail class
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class SellerQueryNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The seller instance.
     *
     * @var Seller
     */
    public $seller;

    /**
     * Contains form data
     *
     * @var array
     */
    public $data;

    /**
     * Create a new message instance.
     *
     * @param Seller $seller
     * @param array  $data
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to(core()->getConfigData('emails.configure.email_settings.admin_email'))
                ->subject(trans('marketplace::app.shop.sellers.mails.contact-seller.subject', ['subject' => $this->data['subject']]))
                ->view('marketplace::shop.emails.seller.sellerQuery', ['sellerName' => $this->data['name'],'email' => $this->data['email'] ,'query' => $this->data['query']]);
    }
}
