<?php

namespace App\Notifications;

use App\Models\Market;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewMarket extends Notification
{
    use Queueable;
    public $market;

    /**
     * Create a new notification instance.
     */
    public function __construct(Market $market)
    {
        $this->market = $market;
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
                    ->subject('New Chemical Demand Available')
                    ->greeting('Hello ' . $notifiable->name . ',')
                    ->line('A new chemical demand has been added by ChimieTrace:')
                    ->line('Chemical: ' . $this->market->chemical->chemical_name . ' (' . $this->market->inventory->description . ')')
                    // ->line('Price: MYR ' . number_format($this->market->price, 2))
                    ->line('Quantity Needed: ' . $this->market->quantity_needed . ' ' . $this->market->unit)
                    ->action('View Product', url('/m/' . $this->market->id))
                    ->line('Log in to place your own offer or view more details.');
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
