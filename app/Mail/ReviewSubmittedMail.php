<?php

namespace App\Mail;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReviewSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Review $review;

    /**
     * Create a new message instance.
     */
    public function __construct(Review $review)
    {
        $this->review = $review;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Nova review submetida na biblioteca')
            ->markdown('emails.reviews.submitted');
    }
}
