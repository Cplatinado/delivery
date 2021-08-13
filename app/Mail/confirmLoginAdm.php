<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class confirmLoginAdm extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    private $data;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(\stdClass $data)
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

        $this->subject('Você fez login na Xmartt - '.' '. $this->data->name . ' ?');
        $this->to($this->data->email, $this->data->name);

        return $this->markdown('mail.confirmLoginAdms',[
            'user' => $this->data
        ]);
    }
}
