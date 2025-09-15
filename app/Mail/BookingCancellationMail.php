<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingCancellationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $booking;
    public $refundAmount;

    /**
     * Create a new message instance.
     */
    public function __construct(Booking $booking, $refundAmount = 0)
    {
        $this->booking = $booking;
        $this->refundAmount = $refundAmount;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Booking Cancellation Confirmation - ' . $this->booking->hotel->name,
            to: [$this->booking->user->email],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.bookings.cancellation',
            with: [
                'booking' => $this->booking,
                'user' => $this->booking->user,
                'hotel' => $this->booking->hotel,
                'room' => $this->booking->room,
                'refundAmount' => $this->refundAmount,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
