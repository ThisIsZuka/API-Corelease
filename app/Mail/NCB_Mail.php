<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NCB_Mail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($PassZip)
    {
        //
        $this->PassZip = $PassZip;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $PassZip = $this->PassZip;
        // return $this->view('view.name');
        return $this
            ->from('noreply@ufundportal.com')
            ->subject('แจ้งรหัสผ่าน')
            ->view('NCB_Mail', compact(['PassZip']));
    }
}
