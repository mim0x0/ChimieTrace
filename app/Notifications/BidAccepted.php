<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BidAccepted extends Notification
{
    use Queueable;
    public $bid;

    /**
     * Create a new notification instance.
     */
    public function __construct($bid)
    {
        $this->bid = $bid;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Your Bid Has Been Accepted')
                    ->greeting('Hello ' . $notifiable->name . ',')
                    ->line('Your bid for the chemical "' . $this->bid->market->chemical->chemical_name . '" has been accepted.')
                    ->line('Quantity offered: ' . $this->bid->quantity)
                    ->line('Offered price: MYR ' . number_format($this->bid->price, 2))
                    ->line('Please prepare the items for delivery as per your proposal.')
                    ->action('View Offer', url('/m/'. $this->bid->market->id))
                    ->line('Thank you for supplying to ABrC.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
