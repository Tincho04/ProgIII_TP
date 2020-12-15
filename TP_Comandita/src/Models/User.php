<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use JsonSerializable;

class User extends Model implements JsonSerializable
{
    public $id;
    public $name;
    public $password;
    public $email;
    public $first_name;
    public $last_name;
    public $created_at;
    public $updated_at;
    public $removed_at;
    public $last_login_at;
    public $role;

    function stats($from = "", $to = "")
    {
        $stats = [];

        if ($this->role == Role::MOZO) {
            $orders = Order::where("user", "=", $this->id)->fetch();

            $codes = array_map(function (Order $order) {
                return $order->code;
            }, $orders);

            $q = Review::avg("service_score")->where("order", true, $codes);

            if (strlen($from) != 0) $q->where("createdAt", "=", $from);
            if (strlen($to) != 0) $q->where("createdAt", "=", $to);

            $stats["averageScore"] = count($codes) > 0 ? $q->fetch()[0] : 0;
            $stats["totalOrders"] = count($orders);
        } else if ($this->role == Role::CHEF || $this->role == Role::BARTENDER || $this->role == Role::CERVECERO) {
            $details = OrderDetail::where("user", "=", $this->id)->fetch();

            $codes = array_map(function (OrderDetail $detail) {
                return $detail->order;
            }, $details);

            $q = Review::avg("menu_score")->where("order", true, $codes);

            if (strlen($from) != 0) $q->where("createdAt", "=", $from);
            if (strlen($to) != 0) $q->where("createdAt", "=", $to);

            $stats["averageScore"] = count($codes) > 0 ? $q->fetch()[0] : 0;
            $stats["totalOrders"] = count($details);
        }

        return $stats;
    }

    function jsonSerialize()
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "firstName" => $this->first_name,
            "lastName" => $this->last_name,
            "email" => $this->email,
            "createdAt" => $this->created_at,
            "updatedAt" => $this->updated_at,
            "removedAt" => $this->removed_at,
            "lastLoginAt" => $this->last_login_at,
            "role" => $this->role
        ];
    }
}
