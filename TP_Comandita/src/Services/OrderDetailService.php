<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderState;
use App\Core\Exceptions\AppException;
use App\Models\Menu;
use App\Models\OrderDetail;
use App\Models\Role;

use function App\Core\Utils\kebabize;

class OrderDetailService
{
    function list($role, $page = 1, $length = 100, $field = "createdAt", $order = "ASC")
    {
        /** @var OrderDetail[] */
        $details = OrderDetail::whereRemoved_at(null)
            ->skip(($page - 1) * $length)
            ->take($length)
            ->orderBy(kebabize($field), $order)
            ->fetch();

        if ($role === Role::ADMIN || $role === Role::MANAGER || $role === Role::FLOOR) {
            return [
                "data" => $details,
                "total" => OrderDetail::count()
            ];
        } else {
            return [
                "data" => array_values(array_filter($details, function (OrderDetail $detail) use ($role) {
                    return Menu::find($detail->menu)->role === $role;
                }, ARRAY_FILTER_USE_BOTH)),
                "total" => OrderDetail::count()
            ];
        }
    }

    function read($code, $id)
    {
        /** @var OrderDetail */
        $detail = OrderDetail::find([
            "id" => $id,
            "order" => $code
        ]);

        if ($detail == null || $detail->removed_at != null) {
            return null;
        }

        return $detail;
    }

    function create($code, $model)
    {
        /** @var Order */
        $order = Order::find($code);
        if ($order == null) throw new AppException("Order not found");

        /** @var Menu */
        $menu = Menu::find($model->menu);
        if ($menu == null) throw new AppException("Menu not found");
        if ($menu->stock < $model->amount) throw new AppException("Insufficient stock for this item in the menu");

        /** @var OrderDetail */
        if (OrderDetail::find([
            "order" => $order->code,
            "menu" => $menu->id
        ]) != null) throw new AppException("There already exists an order for this menu item, please edit the current one or add a different menu item");

        $menu->stock = $menu->stock - $model->amount;
        $menu->edit();

        $detail = new OrderDetail();

        $detail->order = $order->code;
        $detail->menu = $menu->id;
        $detail->amount = $model->amount;
        $detail->state = OrderState::PENDING;

        if (!$detail->create()) throw new AppException("Order detail could not be added, please try again later");

        return OrderDetail::find([
            "order" => $order->code,
            "menu" => $menu->id
        ])->id;
    }

    /** @return void */
    function update($code, $id, $model)
    {
        /** @var OrderDetail */
        $detail = OrderDetail::find([
            "order" => $code,
            "id" => $id
        ]);

        if ($detail == null || $detail->removed_at != null) throw new AppException("Order detail not found");
        if ($detail->state == OrderState::DONE || $detail->state == OrderState::SERVED) throw new AppException("Order is already done, please place a new one");

        /** @var Menu */
        $menu = Menu::find($detail->menu);
        if ($menu == null) throw new AppException("Menu not found");
        if ($menu->stock < $model->amount) throw new AppException("Insufficient stock for this item in the menu");

        $menu->stock = $menu->stock - $model->amount;
        $menu->edit();

        $detail->amount = $model->amount;
        $detail->updated_at = date('Y-m-d H:i:s');

        if (!$detail->edit()) throw new AppException("Could not update the order, please try again");
    }

    /** @return void */
    function delete($code, $id)
    {
        /** @var Order */
        $detail = OrderDetail::find([
            "order" => $code,
            "id" => $id
        ]);

        if ($detail == null || $detail->removed_at != null) throw new AppException("Detail not found");

        if (!$detail->delete()) throw new AppException("Could not deleted this detail, please try again later");
    }

    function states()
    {
        return OrderState::all()->orderBy("id", "ASC")->fetch();
    }

    function take($code, $id, $user, $model)
    {
        /** @var OrderDetail */
        $detail = OrderDetail::find([
            "order" => $code,
            "id" => $id
        ]);

        if ($detail == null || $detail->removed_at != null) throw new AppException("Order detail not found");
        if ($detail->state != OrderState::PENDING) throw new AppException("Cannot take an order that's not in pending");

        $detail->user = $user;
        $detail->estimated_at = date('Y-m-d H:i:s', time() + ($model->estimated * 60));
        $detail->state = OrderState::PREPARING;
        $detail->updated_at = date('Y-m-d H:i:s');

        if (!$detail->edit()) throw new AppException("Could not update the order, please try again");
    }

    function complete($code, $id, $user)
    {
        /** @var OrderDetail */
        $detail = OrderDetail::find([
            "order" => $code,
            "id" => $id
        ]);

        if ($detail == null || $detail->removed_at != null) throw new AppException("Order detail not found");
        if ($detail->state != OrderState::PREPARING) throw new AppException("Cannot mark as done an order that has not been prepared");
        if ($detail->user != $user) throw new AppException("You cannot complete another users' order");

        $detail->state = OrderState::DONE;
        $detail->updated_at = date('Y-m-d H:i:s');

        if (!$detail->edit()) throw new AppException("Could not update the order, please try again");
    }
}
