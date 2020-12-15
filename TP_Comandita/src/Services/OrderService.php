<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderState;
use App\Core\Exceptions\AppException;
use App\Core\Utils\HashHelper;
use App\Models\Menu;
use App\Models\OrderDetail;
use App\Models\Table;
use App\Models\TableState;
use Slim\Http\UploadedFile;

use function App\Core\Utils\kebabize;

class OrderService
{
    function list($filters = [], $page = 1, $length = 100, $field = "createdAt", $order = "ASC")
    {
        /** @var Order[] */
        $orders = Order::whereRemoved_at(null)
            ->skip(($page - 1) * $length)
            ->take($length)
            ->orderBy(kebabize($field), $order);

        foreach ($filters as $key => [$operator, $value]) {
            $orders->where(kebabize($key), $operator, $value);
        }

        return [
            "data" => $orders->fetch(),
            "total" => Order::count()
        ];
    }

    function read($code)
    {
        /** @var Order */
        $order = Order::findByCode($code);

        if ($order != null) {
            $order->detail = $order->details();
        }

        return $order;
    }

    function create($model, UploadedFile $image = null)
    {
        $order = new Order();

        $code = HashHelper::generate(5);

        /** @var Table */
        $table = Table::find($model->table);

        if ($table == null || $table->removed_at != null) throw new AppException("Table not found");
        if ($table->state != TableState::AVAILABLE) throw new AppException("Table is unavailable");

        $order->code = $code;
        $order->user = $model->user;
        $order->table = $model->table;
        $order->state = 0;

        if (!$order->create()) throw new AppException("Order could not be created, please try again later");

        foreach ($model->detail as $d) {
            /** @var Menu */
            $menu = Menu::find($d->menu);

            if ($menu == null) throw new AppException("Menu item not found");
            if ($menu->stock < $d->amount) throw new AppException("There is not enough " . $menu->name . " for this order");

            $detail = new OrderDetail();

            $detail->order = $code;
            $detail->menu = $d->menu;
            $detail->amount = $d->amount;
            $detail->state = OrderState::PENDING;

            if (!$detail->create()) throw new AppException("Could not add detail to order $code, please try again");

            $menu->stock = $menu->stock - $detail->amount;
            $menu->edit();
        }

        $table->state = TableState::WAITING;
        $table->edit();

        if ($image != null && ImageHelper::validate($image)) {
            ImageHelper::saveTo("Orders", $image, "$order->code.png");
        }

        return $code;
    }

    /** @return void */
    function update($code, $model, UploadedFile $image = null)
    {
        /** @var Order */
        $order = Order::find($code);

        if ($order == null || $order->removed_at != null) throw new AppException("Order not found");

        if ($model->table != $order->table) {
            /** @var Table */
            $table = Table::find($model->table);
            if ($table == null || $table->removed_at != null) throw new AppException("Table not found");
            if ($table->state != TableState::AVAILABLE) throw new AppException("Table is unavailable");
        }

        $order->user = $model->user;
        $order->table = $model->table;
        $order->state = $model->state;
        $order->updated_at = date('Y-m-d H:i:s');

        if (!$order->edit()) throw new AppException("Could not update the order, please try again");

        if ($image != null && ImageHelper::validate($image)) {
            ImageHelper::saveTo("Orders", $image, "$order->code.png", true);
        }
    }

    /** @return void */
    function delete($code)
    {
        /** @var Order */
        $order = Order::find($code);

        if ($order == null || $order->removed_at != null) throw new AppException("Order not found");

        if (!$order->delete()) throw new AppException("Could not deleted this order, please try again later");

        foreach ($order->details() as $detail) {
            $detail->delete();
        }
    }

    function states()
    {
        return OrderState::all()->orderBy("id", "ASC")->fetch();
    }

    function changeState($code)
    {
        /** @var Order */
        $order = Order::find($code);

        if ($order == null) throw new AppException("Order not found");

        if ($order->removed_at == null) {
            $order->removed_at = date('Y-m-d H:i:s');

            if (!$order->edit()) throw new AppException("Could not change state of order, please try again later");

            foreach ($order->details() as $detail) {
                $detail->removed_at = date('Y-m-d H:i:s');
                $detail->edit();
            }
        } else {
            $order->removed_at = null;

            if (!$order->edit()) throw new AppException("Could not change state of order, please try again later");

            foreach ($order->details() as $detail) {
                $detail->removed_at = null;
                $detail->edit();
            }
        }
    }

    function serve($code)
    {
        /** @var Order */
        $order = Order::find($code);

        if ($order == null || $order->removed_at != null) throw new AppException("Order not found");

        foreach ($order->details() as $detail) {
            if ($detail->state != OrderState::DONE) throw new AppException("Order detail must be in done state to serve this order");
        }

        $order->state = OrderState::SERVED;
        $order->updated_at = date('Y-m-d H:i:s');

        if (!$order->edit()) throw new AppException("Order could not be processed, please try again later");

        foreach ($order->details() as $detail) {
            $detail->state = OrderState::SERVED;
            $detail->edit();
        }
    }
}
