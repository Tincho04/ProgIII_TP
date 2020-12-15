<?php

namespace App\Services;

use App\Core\Data\QueryBuilder;
use App\Models\Log;
use PDO;

use function App\Core\Utils\kebabize;

class LogService
{
    function list($filters = [], $page = 1, $length = 100, $field = "createdAt", $order = "ASC")
    {
        /** @var QueryBuilder */
        $logs = Log::all()
            ->skip(($page - 1) * $length)
            ->take($length)
            ->orderBy(kebabize($field), $order);

        foreach ($filters as $key => [$operator, $value]) {
            $logs->where(kebabize($key), $operator, $value);
        }

        return [
            "data" => $logs->fetch(),
            "total" => Log::count(),
            "filtered" => $logs->count()->fetch()
        ];
    }
}
