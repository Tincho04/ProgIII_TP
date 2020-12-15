<?php

namespace App\Controllers;

use \Firebase\JWT\JWT;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Logs;
use stdClass;

class LogsController
{

    public function getAll(Request $request, Response $response, $args)
    {
        $headers = getallHeaders();
        $token = $headers['token'];
        $decoded = JWT::decode($token, "Comandita", array('HS256'));

        if ($decoded->role == 1) {
            $rta = Logs::get();
            $response->getBody()->write(json_encode($rta));
            return $response;
        } else {
            $response->getBody()->write("No posee permisos");
            return $response->withStatus(401);
        }
    }
}
