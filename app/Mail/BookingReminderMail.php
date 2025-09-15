<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingReminderMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $booking;
    public $daysUntilCheckIn;

    /**
     * Create a new message instance.
     */
    public function __construct(Booking $booking, $daysUntilCheckIn)
    {
        $this->booking = $booking;
        $this->daysUntilCheckIn = $daysUntilCheckIn;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->daysUntilCheckIn === 1
            ? 'Tomorrow is Check-in Day!'
            : "Your stay is in {$this->daysUntilCheckIn} days";
            
        return new Envelope(
            subject: $subject . ' - ' . $this->booking->hotel->name,
            to: [$this->booking->user->email],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.bookings.reminder',
            with: [
                'booking' => $this->booking,
                'user' => $this->booking->user,
                'hotel' => $this->booking->hotel,
                'room' => $this->booking->room,
                'daysUntilCheckIn' => $this->daysUntilCheckIn,
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
