<?php

namespace App\Controllers;

use \Firebase\JWT\JWT;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Mesa;
use App\Models\Usuario;
use stdClass;

class MesaController
{

    public function getDisponibles(Request $request, Response $response, $args)
    {
        $headers = getallHeaders();
        $token = $headers['token'];
        $decoded = JWT::decode($token, "Comandita", array('HS256'));

        if ($decoded->role == 2 || $decoded->role == 3 || $decoded->role == 1) {
            $rta = Mesa::select()->where('state', 0)->get();
            $response->getBody()->write(json_encode($rta));
            return $response;
        }
        return $response;
    }

    public function getAll(Request $request, Response $response, $args)
    {
        $rta = Mesa::get();
        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    
    public function deleteOne(Request $request, Response $response, $args)
    {
        $id = $args['id'];

            $user = Mesa::find($id);

            $rta = $user->delete();

            $response->getBody()->write(json_encode($rta));
            return $response;
    }

    public function updateOne(Request $request, Response $response, $args)
    {
        $id  = $request->getParsedBody()['mesa'];

        $rta = Mesa::select()->where('code', '=', $id)->update(array('state' => '0'));
        $response->getBody()->write(json_encode($rta));

        return $response;
    }
}
