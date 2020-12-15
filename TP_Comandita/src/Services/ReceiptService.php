<?php

namespace App\Services;

use App\Models\Menu;
use App\Core\Exceptions\AppException;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Receipt;

class ReceiptService
{
    function list()
    {
        /** @var Receipt[] */
        $receipts = Receipt::all()
            ->orderBy("created_at")
            ->take(10)
            ->fetch();

        return $receipts;
    }

    function create($model)
    {
        /** @var Order */
        $order = Order::find($model->order);

        if ($order == null || $order->removed_at != null) throw new AppException("Order doesn't exist");
        if (Receipt::find(["order" => $model->order]) != null) throw new AppException("There already exists a receipt for this order");

        $receipt = new Receipt();

        $receipt->user = $model->user;
        $receipt->table = $model->table;
        $receipt->order = $model->order;
        $receipt->total = array_reduce($order->details(), function ($carry, OrderDetail $current) {
            /** @var Menu */
            $menu = Menu::find($current->menu);
            $carry += ($menu->price * $current->amount);
            return $carry;
        }, 0);

        if (!$receipt->create()) throw new AppException("Receipt could not be created, please try again later");

        return $receipt->find(["order" => $receipt->order]);
    }
}
