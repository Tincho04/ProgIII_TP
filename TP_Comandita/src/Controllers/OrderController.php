<?php

namespace App\Controllers;

use \Firebase\JWT\JWT;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Usuario;
use App\Models\Order;
use stdClass;

class OrderController
{

    public function getAll(Request $request, Response $response, $args)
    {
        $rta = Usuario::get();
        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function getOne(Request $request, Response $response, $args)
    {
        $rta = Usuario::find($args['legajo']);;

        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    static function generate($length)
    {
        $hash = "";
        $alphabet = "abcdefghijklmnopqrstuvwxyz0123456789";
        $max = strlen($alphabet);
        for ($i = 0; $i < $length; $i++) {
            $hash .= $alphabet[rand(0, $max - 1)];
        }
        return $hash;
    }

    public function addOrder(Request $request, Response $response, $args)
    {
        $headers = getallHeaders();
        $token = $headers['token'];
        $decoded = JWT::decode($token, "Comandita", array('HS256'));

        $name = $decoded->name;
        $mesa = $request->getParsedBody()['table'];
        if ($mesa == 'me001' || $mesa == 'me002' || $mesa == 'me003' || $mesa == 'me004') {
            $order = new Order;
            
            $usuarioEncontrado = json_decode(Usuario::whereRaw('name = ?', array($name))->get());
            if ($usuarioEncontrado) {
                $id = $usuarioEncontrado[0]->id;
            };

            $code = $this->generate(5);

            $order->code = $code;
            $order->state = 0;
            $order->user = $id;
            $order->table = $mesa;

            $rta = $order->save();
            $response->getBody()->write(json_encode($rta));
            return $response;
        } else {
            $response->getBody()->write("No es una mesa valida");
            return $response->withStatus(401);
        }
    }
}
