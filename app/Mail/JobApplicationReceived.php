<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class JobApplicationReceived extends Mailable
{
    use Queueable, SerializesModels;

    public $application;
    private $file = null;
    private $mail = null;
    private $status = null;
    private $senderName = "fsfsfsfsfsfsfsfsf";

    public function __construct($application)
    {
        $this->application = $application;
        $this->mail        = $application['email'];
    }

    // public function envelope()
    // {
    //     return new Envelope(
    //         from: new Address("ld99.lh@gmail.com", 'Intership Asea@@@@'),
    //         replyTo: [
    //             new Address("longdyheak9999@gmail.com", 'LongdyHeak@@@'),
    //         ],
    //         subject: 'invoice pdf @@@@@@@@@'
    //     );
    // }

    public function build()
    {
        $subject = 'Job Application Received: ' . $this->application['id'];
        return $this->view('website::pages.mail')
                    ->with([
                        'email' => $this->application['email']
                    ])
                    ->subject($this->subject)
                    ->from('ld99.lh@gmail.com', $subject);
    }
    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
   

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    // public function content()
    // {
    //     return new Content(
    //         view: 'view.name',
    //     );
    // }

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
