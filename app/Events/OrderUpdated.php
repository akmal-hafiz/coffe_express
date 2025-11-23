<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * OrderUpdated Event
 * 
 * This event is fired whenever an order status or estimated time is updated by admin.
 * It broadcasts to a private channel specific to the order's owner (user).
 */
class OrderUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;
    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct(Order $order, string $message = null)
    {
        $this->order = $order;
        $this->message = $message ?? $this->generateMessage($order);
    }

    /**
     * Get the channels the event should broadcast on.
     * 
     * Broadcasting to private channel ensures only the order owner receives updates.
     * Channel naming: private-order.{orderId}
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('order.' . $this->order->id),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'order.updated';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'order_id' => $this->order->id,
            'status' => $this->order->status,
            'estimated_time' => $this->order->estimated_time,
            'message' => $this->message,
            'customer_name' => $this->order->customer_name,
            'total_price' => $this->order->total_price,
            'pickup_option' => $this->order->pickup_option,
        ];
    }

    /**
     * Generate appropriate message based on order status
     */
    private function generateMessage(Order $order): string
    {
        return match($order->status) {
            'pending' => '☕ Your order has been received and is being processed',
            'preparing' => '☕ Your coffee is being prepared with love',
            'ready' => $order->pickup_option === 'pickup' 
                ? '🎉 Your coffee is ready for pickup!' 
                : '🚗 Your coffee is on the way!',
            'completed' => '✅ Order completed. Thank you for your order!',
            default => 'Order status updated',
        };
    }
}
