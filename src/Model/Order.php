<?php

namespace Neyromanser\LaravelShop\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class Order extends Model
{
    protected $table = 'orders';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * Create an Order
     *
     * @param array $items
     * @param $user_id
     *
     * @return Order
     */
    public function order($user_id, $data = null)
    {
        $order = new self();

        $order->user_id = $user_id;
        $order->items_number = 0;
        $order->items_total = 0;

        if ($data) {
          $order->fill($data);
        }

        $order->state = config("shop.status_init");
        $order->save();

        return $order;
    }

    /**
     * Get an Order
     *
     * @param $order_id
     *
     * @return mixed
     */
    public function getOrder($order_id)
    {
        return self::findOrFail($order_id);
    }

    /**
     * Get User Order
     *
     * @param $user_id
     *
     * @return mixed
     */
    public function getUserOrders($user_id)
    {
        return self::where('user_id', $user_id)->get();
    }

    /**
     * Refresh an Order amount
     *
     * @param $item_id
     * @param $qty
     *
     * @return bool
     */

    public function updateQty($item_id, $qty)
    {
        $item = OrderItem::findOrFail($item_id);

        if (!$item) {
            return;
        }

        if ($qty <= 0) {
            $this->removeItem($item->order_id, $item_id);
        }

        $item->quantity = $qty;

        $item->total_price = $item->price * $item->quantity;
        $item->total_price += $item->total_price * $item->$vat;

        $item->save();

        $this->updateOrder($item->order_id);

        return $item;
    }

    public function addItem($order, Model $object, $price, $qty, $data = null, $vat = 0)
    {
        $orderItem = new OrderItem();

        if ($data) {
          $orderItem->fill($data);
        }

        $orderItem->line_item_id = $object->id;
        $orderItem->line_item_type = get_class($object);
        $orderItem->price = $price;
        $orderItem->quantity = $qty;
        $orderItem->vat = $vat;

        $orderItem->total_price = $qty * $price;
        $orderItem->total_price += $orderItem->total_price * $vat;

        $orderItem->order_id = $order->id;

        $orderItem->save();
        $order = $this->updateOrder($order);

        return $order;
    }

    public function batchAddItems($order, $orderItems)
    {
        foreach ($orderItems as $item) {
          $orderItem = new OrderItem();

          if ($item) {
            $orderItem->fill($item);
          }

          $orderItem->total_price = $orderItem->quantity * $orderItem->price;
          $orderItem->total_price += $orderItem->total_price * $orderItem->vat;

          $orderItem->order_id = $order->id;
          $orderItem->save();
          $order = $this->updateOrder($order);
        }

        return $order;
    }

    /**
     * Delete an Order
     *
     * @param $item_id
     *
     * @return bool
     */
    public function removeItem($order_id, $item_id)
    {
        $order = self::findOrFail($order_id);

        $item = OrderItem::findOrFail($item_id);

        if (!($order->id == $item->order_id)) {
            return false;
        }

        if (!$item) {
            return false;
        }

        $item->delete();

        $this->updateOrder($order_id);

        return true;
    }

    /**
     * Update an Order Status
     *
     * @param $order_id
     * @param $status
     *
     * @return bool
     */
    public function updateStatus($order_id, $status)
    {
        // if (!($status == $this->INIT || $status == $this->COMPLETE
        //     || $status == $this->OBLIGATION || $status == $this->PROCESSING)) {
        //     return false;
        // }

        $order = $this->getOrder($order_id);

        if (!$order) {
            return false;
        }

        if ($status == config("shop.status_complete")) {
          //Mark order as completed
          $order->completed_at = date("Y-m-d H:i:s");
        }

        $order->state = $status;
        $order->save();

        return true;
    }

    /**
     * Delete an Order
     *
     * @param $order_id
     *
     * @return bool
     */
    public function deleteOrder($order_id)
    {
        $order = $this->getOrder($order_id);

        if (!$order) {
            return false;
        }

        OrderItem::where('order_id', $order->id)->delete();

        $order->delete();

        return true;
    }

    /**
     * Refresh an Order's values
     *
     * @param $order_id
     *
     * @return bool
     */

    public function updateOrder($order)
    {
        // $order = self::findOrFail($order_id);
        if (!$order) {
            return false;
        }
        $order->items_total = $this->total($order);
        $order->items_number = $this->count($order);

        $order->save();

        return $order;
    }

    /**
     * Calculate an Order total amount
     *
     * @param $order_id
     *
     * @return int
     */

    public function total($order)
    {
        if ($order->id > 0) {
            $items = OrderItem::where('order_id', $order->id)->get();
        }
        else{
            $items = $order->orderItems;
        }

        $total = 0;

        if ($items) {
          foreach ($items as $item) {
              $total += $item->total_price;
          }
        }

        return $total;
    }

    /**
     * Calculate an Order total item count
     *
     * @param $order_id
     *
     * @return int
     */
    public function count($order)
    {
        if ($order->id > 0) {
            $items = OrderItem::where('order_id', $order->id)->get();
        }
        else{
            $items = $order->orderItems;
        }
        // $items = OrderItem::where('order_id', $order_id)->get();

        $count = 0;

        if ($items) {
          foreach ($items as $item) {
              $count += $item->quantity;
          }
        }

        return $count;
    }

    public function orderItems()
    {
        return $this->hasMany('Neyromanser\LaravelShop\Model\OrderItem');
    }
	
}
