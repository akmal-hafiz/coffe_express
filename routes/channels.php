<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Order;

/**
 * User presence channel
 */
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

/**
 * Private Order Channel
 * 
 * Only the user who owns the order can subscribe to this channel.
 * This ensures order updates are sent only to the correct user.
 * 
 * Channel: private-order.{orderId}
 */
Broadcast::channel('order.{orderId}', function ($user, $orderId) {
    $order = Order::find($orderId);
    
    // Allow access only if the authenticated user owns this order
    return $order && (int) $order->user_id === (int) $user->id;
});
