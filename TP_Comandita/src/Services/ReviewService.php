<?php

namespace App\Services;

use App\Core\Data\QueryBuilder;
use App\Models\Review;
use App\Core\Exceptions\AppException;
use App\Models\Order;
use App\Models\OrderState;

use function App\Core\Utils\kebabize;

class ReviewService
{
    function list($page = 1, $length = 100, $field = "createdAt", $order = "ASC")
    {
        /** @var Review[] */
        $reviews = Review::whereRemoved_at(null)
            ->orderBy(kebabize($field), $order)
            ->skip(($page - 1) * $length)
            ->take($length)
            ->fetch();

        return [
            "data" => $reviews,
            "count" => Review::count()
        ];
    }

    function averages()
    {
        return [
            "menu" => Review::avg("menu_score")->fetch()[0],
            "table" => Review::avg("table_score")->fetch()[0],
            "service" => Review::avg("service_score")->fetch()[0],
            "environment" => Review::avg("environment_score")->fetch()[0],
        ];
    }

    function read($id)
    {
        /** @var Review */
        $review = Review::find($id);

        if ($review == null || $review->removed_at != null) {
            return null;
        }

        return $review;
    }

    function create($code, $model)
    {
        /** @var Order */
        $order = Order::find($code);

        if ($order == null || $order->removed_at != null) throw new AppException("Order not found");
        if ($order->state != OrderState::SERVED) throw new AppException("Cannot review an order that has not been served");
        if (Review::find(["order" => $code])) throw new AppException("This order has already been reviewed");


        $review = new Review();

        $review->order = $order->code;
        $review->name = $model->name;
        $review->description = $model->description;
        $review->email = $model->email;
        $review->menu_score = $model->menuScore;
        $review->table_score = $model->tableScore;
        $review->service_score = $model->serviceScore;
        $review->environment_score = $model->environmentScore;

        if (!$review->create()) throw new AppException("Review could not be processed");

        return Review::find(["order" => $order->code])->id;
    }

    function update($id, $model)
    {
        /** @var Review */
        $review = Review::find($id);

        if ($review == null || $review->removed_at != null) throw new AppException("Review not found");

        $review->name = $model->name;
        $review->description = $model->description;
        $review->email = $model->email;
        $review->menu_score = $model->menuScore;
        $review->table_score = $model->tableScore;
        $review->service_score = $model->serviceScore;
        $review->environment_score = $model->environmentScore;
        $review->updated_at = date('Y-m-d H:i:s');

        return $review->edit();
    }

    function delete($id)
    {
        /** @var Review */
        $review = Review::find($id);

        if ($review == null || $review->removed_at != null) throw new AppException("Review not found");

        return $review->delete();
    }

    function changeState($id)
    {
        /** @var Review */
        $review = Review::find($id);

        if ($review == null) throw new AppException("Review not found");

        if ($review->removed_at == null) {
            $review->removed_at = date('Y-m-d H:i:s');
        } else {
            $review->removed_at = null;
        }

        return $review->edit();
    }
}
