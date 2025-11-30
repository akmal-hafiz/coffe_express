<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdateMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The order instance.
     */
    public Order $order;

    /**
     * The previous status.
     */
    public string $previousStatus;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order, string $previousStatus)
    {
        $this->order = $order;
        $this->previousStatus = $previousStatus;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $statusText = $this->getStatusText($this->order->status);

        return new Envelope(
            subject: "Order #{$this->order->id} - {$statusText} - Coffee Express",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.order-status-update',
            with: [
                'order' => $this->order,
                'customerName' => $this->order->customer_name,
                'currentStatus' => $this->order->status,
                'previousStatus' => $this->previousStatus,
                'statusText' => $this->getStatusText($this->order->status),
                'statusMessage' => $this->getStatusMessage($this->order->status),
                'estimatedTime' => $this->order->estimated_time,
                'orderItems' => $this->order->items,
                'totalPrice' => $this->order->total_price,
                'pickupOption' => $this->order->pickup_option,
                'address' => $this->order->address,
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

    /**
     * Get human-readable status text.
     */
    private function getStatusText(string $status): string
    {
        return match ($status) {
            'pending' => 'Order Received',
            'preparing' => 'Now Preparing',
            'ready' => 'Ready for Pickup',
            'completed' => 'Order Completed',
            default => ucfirst($status),
        };
    }

    /**
     * Get status-specific message.
     */
    private function getStatusMessage(string $status): string
    {
        return match ($status) {
            'pending' => 'Your order has been received and is waiting to be processed.',
            'preparing' => 'Great news! Our baristas are now preparing your delicious coffee.',
            'ready' => 'Your order is ready! Please come to pick it up or wait for delivery.',
            'completed' => 'Thank you for your order! We hope you enjoyed your coffee. See you again soon!',
            default => 'Your order status has been updated.',
        };
    }
}
