<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewOrderNotification extends Notification
{
    use Queueable;

    protected $order;
    protected $item;

    /**
     * Create a new notification instance.
     */
    public function __construct($order, $item)
    {
        $this->order = $order;
        $this->item = $item;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ["database"];
    }

    public function toDatabase($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            "phone" => $this->order->phone,
            'shipping_address' => $this->order->shipping_address,
            'product_id' => $this->item->product->id,
            'product_name' => $this->item->product->name,
            'payment_method' => $this->order->payment_method,
            'quantity' => $this->item->quantity,

            'price' => $this->item->product->price,
            "discount" => $this->item->product->discount,
            'customer_phone' => $this->order->phone,
            'user_name' => $this->order->user->name ?? null,
            'user_email' => $this->order->user->email ?? null,
            // 'specifications' => $this->item->specifications ?? null,
        ];

            // "price" => 2271.28
            // "updated_at" => "2025-08-19 21:34:26"
            // "created_at" => "2025-08-19 21:34:26"
            // "id" => 13
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('You have a new order')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
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
