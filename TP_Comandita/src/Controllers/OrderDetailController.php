<?php

namespace App\Controllers;

use \Firebase\JWT\JWT;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Usuario;
use App\Models\Mesa;
use App\Models\Menu;
use App\Models\Receipt;
use App\Models\Review;
use App\Models\Order;
use App\Models\OrderDetail;
use stdClass;

class OrderDetailController
{

    public function getAll(Request $request, Response $response, $args)
    {
        $headers = getallHeaders();
        $token = $headers['token'];
        $decoded = JWT::decode($token, "Comandita", array('HS256'));

        if ($decoded->role == 2) {
            $rta = Order::get();
            $response->getBody()->write(json_encode($rta));
            return $response;
        } else {
            $response->getBody()->write("No posee permisos");
            return $response->withStatus(401);
        }
    }

    public function getPedido(Request $request, Response $response, $args)
    {
        $pedido = $args['id'];
        $consulta = Order::select()->where('code', $pedido)->get();
        $response->getBody()->write(json_encode($consulta));

        return $response;
    }

    public function getOne(Request $request, Response $response, $args)
    {
        $rta = Usuario::find($args['legajo']);;

        $response->getBody()->write(json_encode($rta));
        return $response;
    }


    public function getDisponibles(Request $request, Response $response, $args)
    {
        $headers = getallHeaders();
        $token = $headers['token'];
        $decoded = JWT::decode($token, "Comandita", array('HS256'));

        if ($decoded->role == 4) {
            $rta = OrderDetail::select()->where('menu', '>', '4', 'AND', 'menu', '<', '8')->where('state', '=', '0')->get();
            $response->getBody()->write(json_encode($rta));
            return $response;
        }
        if ($decoded->role == 5) {
            $rta = OrderDetail::select()->where('menu', '>', '7', 'AND', 'menu', '<', '11')->where('state', '=', '0')->get();
            $response->getBody()->write(json_encode($rta));
            return $response;
        }
        if ($decoded->role == 6) {
            $rta = OrderDetail::select()->where('menu', '>=', '1', 'AND', 'menu', '<', '5')->where('state', '=', '0')->get();
            $response->getBody()->write(json_encode($rta));
            return $response;
        }
        if ($decoded->role == 7) {
            $rta = OrderDetail::select()->where('menu', '>', '10', 'AND', 'menu', '<', '13')->where('state', '=', '0')->get();
            $response->getBody()->write(json_encode($rta));
            return $response;
        }
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

    public function asignOrder(Request $request, Response $response, $args)
    {
        $headers = getallHeaders();
        $token = $headers['token'];
        $decoded = JWT::decode($token, "Comandita", array('HS256'));

        $pedidoChef  = $request->getParsedBody()['cocina'];
        $pedidoCerv  = $request->getParsedBody()['choper'];
        $pedidoBar   = $request->getParsedBody()['barra'];
        $pedidoCandy = $request->getParsedBody()['candy'];
        $pedidoChefCant  = $request->getParsedBody()['cocinacant'];
        $pedidoCervCant  = $request->getParsedBody()['chopercant'];
        $pedidoBarCant   = $request->getParsedBody()['barracant'];
        $pedidoCandyCant = $request->getParsedBody()['candycant'];

        $pedido = 0;
        $name = $decoded->name;
        $mesa = $request->getParsedBody()['table'];
        if ($mesa == 'me001' || $mesa == 'me002' || $mesa == 'me003' || $mesa == 'me004') {
            $table = json_decode(Mesa::whereRaw('code = ?', array($mesa))->get());
            if ($table[0]->state == 0) {
                $usuarioEncontrado = json_decode(Usuario::whereRaw('name = ?', array($name))->get());
                if ($usuarioEncontrado) {
                    $id = $usuarioEncontrado[0]->id;
                };

                $code = $this->generate(5);

                if (($pedidoChef >= 1 && $pedidoChef < 5) && ($pedidoChefCant > 0)) {
                    $orderD = new OrderDetail;
                    $orderD->user = null;
                    $orderD->order = $code;
                    $orderD->menu = $pedidoChef;
                    $orderD->amount = $pedidoChefCant;
                    $orderD->state = 0;
                    $rta = $orderD->save();
                    $pedido++;
                }

                if (($pedidoCerv >= 8 && $pedidoCerv < 11) && ($pedidoCervCant > 0)) {
                    $orderD = new OrderDetail;
                    $orderD->user = null;
                    $orderD->order = $code;
                    $orderD->menu = $pedidoCerv;
                    $orderD->amount = $pedidoCervCant;
                    $orderD->state = 0;
                    $rta = $orderD->save();
                    $pedido++;
                }

                if (($pedidoBar >= 5 && $pedidoBar < 8) && ($pedidoBarCant > 0)) {
                    $orderD = new OrderDetail;
                    $orderD->user = null;
                    $orderD->order = $code;
                    $orderD->menu = $pedidoBar;
                    $orderD->amount = $pedidoBarCant;
                    $orderD->state = 0;
                    $rta = $orderD->save();
                    $pedido++;
                }

                if (($pedidoCandy >= 11 && $pedidoCandy < 13) && ($pedidoCandyCant > 0)) {
                    $orderD = new OrderDetail;
                    $orderD->user = null;
                    $orderD->order = $code;
                    $orderD->menu = $pedidoChef;
                    $orderD->amount = $pedidoChefCant;
                    $orderD->state = 0;
                    $rta = $orderD->save();
                    $pedido++;
                }

                if ($pedido > 0) {
                    $order = new Order;
                    $order->code = $code;
                    $order->state = 0;
                    $order->user = $id;
                    $order->table = $mesa;

                    Mesa::where('code', '=', $mesa)->update(array('state' => 1));

                    $rta = $order->save();
                    echo ($code . "  ");
                    $response->getBody()->write(json_encode($rta));
                    return $response;
                } else {
                    $response->getBody()->write("No se ha efectuado un pedido valido");
                    return $response->withStatus(401);
                }
            } else {
                $response->getBody()->write("La mesa no se encuentra disponible");
                return $response->withStatus(401);
            }
        } else {
            $response->getBody()->write("No es una mesa valida");
            return $response->withStatus(401);
        }
    }

    public function takeOrder(Request $request, Response $response, $args)
    {
        $headers = getallHeaders();
        $token = $headers['token'];
        $decoded = JWT::decode($token, "Comandita", array('HS256'));
        $name = $decoded->name;
        $usuarioEncontrado = json_decode(Usuario::whereRaw('name = ?', array($name))->get());
        if ($usuarioEncontrado) {
            $id = $usuarioEncontrado[0]->id;
        };
        $pedido  = $request->getParsedBody()['pedido'];
        $mesaEncontrada = json_decode(Order::whereRaw('code = ?', array($pedido))->get());
        if ($mesaEncontrada) {
            $table = $mesaEncontrada[0]->table;
        };
        $estimated_at = date('Y-m-d H:i:s', time() + (60));

        if ($decoded->role == 4 || $decoded->role == 5 || $decoded->role == 6 || $decoded->role == 7) {
            $rta = OrderDetail::select()->where('order', '=', $pedido, 'AND', 'state', '=', '0')->update(array('user' => $id, 'state' => '1', 'estimated_at' => $estimated_at));
            $rtaO = Order::select()->where('code', '=', $pedido, 'AND', 'state', '=', '0')->update(array('state' => '1', 'estimated_at' => $estimated_at));
            $rtaM = Mesa::select()->where('code', '=', $table, 'AND', 'state', '=', '0')->update(array('state' => '1'));
            $response->getBody()->write(json_encode($rta));

            return $response;
        }
    }

    public function finishOrder(Request $request, Response $response, $args)
    {
        $headers = getallHeaders();
        $token = $headers['token'];
        $decoded = JWT::decode($token, "Comandita", array('HS256'));
        $name = $decoded->name;
        $usuarioEncontrado = json_decode(Usuario::whereRaw('name = ?', array($name))->get());
        if ($usuarioEncontrado) {
            $id = $usuarioEncontrado[0]->id;
        };
        $pedido  = $request->getParsedBody()['pedido'];
        $estado  = $request->getParsedBody()['estado'];
        $time = date('Y-m-d H:i:s', time());

        $mesaEncontrada = json_decode(Order::whereRaw('code = ?', array($pedido))->get());
        if ($mesaEncontrada) {
            $table = $mesaEncontrada[0]->table;
        };

        if ($decoded->role == 4 || $decoded->role == 5 || $decoded->role == 6 || $decoded->role == 7) {
            $validate = OrderDetail::select()->where('order', '=', $pedido, 'AND', 'state', '=', '1')->get();
            if ($validate[0]->estimated_at < $time) {
                $rta = OrderDetail::select()->where('user', '=', $id, 'AND', 'order', '=', $pedido, 'AND', 'state', '=', '1')->update(array('state' => '2', 'estimated_at' => NULL));
                $rtaO = Order::select()->where('code', '=', $pedido, 'AND', 'state', '=', '1')->update(array('state' => '2', 'estimated_at' => NULL));
                $response->getBody()->write(json_encode($rta));
            } else {
                $rta = OrderDetail::select()->where('user', '=', $id, 'AND', 'order', '=', $pedido, 'AND', 'state', '=', '1')->update(array('state' => '4', 'estimated_at' => NULL));
                $rtaO = Order::select()->where('code', '=', $pedido, 'AND', 'state', '=', '1')->update(array('state' => '4', 'estimated_at' => NULL));
                $response->getBody()->write(json_encode($rta));
            }
            return $response;
        }
        if ($decoded->role == 2) {
            if ($estado != 'cancelado') {
                $rta = OrderDetail::select()->where('order', '=', $pedido)->update(array('state' => '3', 'estimated_at' => NULL));
                $rtaO = Order::select()->where('code', '=', $pedido)->update(array('state' => '3', 'estimated_at' => NULL));
                $response->getBody()->write(json_encode($rta));
            } else {
                $rta = OrderDetail::select()->where('order', '=', $pedido)->update(array('state' => '5', 'estimated_at' => NULL));
                $rtaO = Order::select()->where('code', '=', $pedido)->update(array('state' => '5', 'estimated_at' => NULL));
                $response->getBody()->write(json_encode($rta));
            }
            return $response;
        }
    }

    public function serve(Request $request, Response $response, $args)
    {
        $headers = getallHeaders();
        $token = $headers['token'];
        $decoded = JWT::decode($token, "Comandita", array('HS256'));

        $mesa  = $request->getParsedBody()['mesa'];
        $orden  = $request->getParsedBody()['orden'];

        if ($decoded->role == 3) {
                $rta = OrderDetail::select()->where('order', '=', $orden, 'AND', 'state', '=', '2')->update(array('state' => '3'));
                $rtaO = Order::select()->where('code', '=', $orden, 'AND', 'state', '=', '2')->update(array('state' => '3'));
                $rtaM = Mesa::select()->where('code', '=', $mesa, 'AND', 'state', '=', '1')->update(array('state' => '2'));
                $response->getBody()->write(json_encode($rta));

            return $response;
        }
    }


    public function checkout(Request $request, Response $response, $args)
    {
        $headers = getallHeaders();
        $token = $headers['token'];
        $decoded = JWT::decode($token, "Comandita", array('HS256'));
        $name = $decoded->name;
        $usuarioEncontrado = json_decode(Usuario::whereRaw('name = ?', array($name))->get());
        if ($usuarioEncontrado) {
            $id = $usuarioEncontrado[0]->id;
        };
        $mesa  = $request->getParsedBody()['mesa'];
        $orden  = $request->getParsedBody()['orden'];

        if ($decoded->role == 2) {
            $amount = OrderDetail::select('amount')->where('order', '=', $orden)->get();
            $menu   = OrderDetail::select('menu')->where('order', '=', $orden)->get();
            $monto = 0;

            $precio1 = Menu::select('price')->where('id', '=', $menu[0]->menu)->get();
            $precio2 = Menu::select('price')->where('id', '=', $menu[1]->menu)->get();
            $precio3 = Menu::select('price')->where('id', '=', $menu[2]->menu)->get();
            $precio4 = Menu::select('price')->where('id', '=', $menu[3]->menu)->get();

            if (($amount[0]->amount) > 0 && ($precio1[0]->price) > 0) {
                $monto = $monto + $amount[0]->amount * $precio1[0]->price;
            }

            if (($amount[1]->amount) > 0  && ($precio2[0]->price) > 0) {
                $monto = $monto + $amount[1]->amount * $precio2[0]->price;
            }

            if (($amount[2]->amount) > 0  && ($precio3[0]->price) > 0) {
                $monto = $monto + $amount[2]->amount * $precio3[0]->price;
            }

            if (($amount[3]->amount) > 0  && ($precio4[0]->price) > 0) {
                $monto = $monto + $amount[3]->amount * $precio4[0]->price;
            }

            $rtaM = Mesa::select()->where('code', '=', $mesa)->update(array('state' => '3'));

            // echo ($monto);

            $recibo = new Receipt;
            $recibo->user = $id;
            $recibo->table = $mesa;
            $recibo->order = $orden;
            $recibo->total = $monto;
            $rta = $recibo->save();

            $response->getBody()->write(json_encode($rta));

            return $response;
        }
    }


    public function review(Request $request, Response $response, $args)
    {
        $orden  = $request->getParsedBody()['Orden'];
        $nombreCliente  = $request->getParsedBody()['Nombre'];
        $mailCliente   = $request->getParsedBody()['Mail'];
        $descripcion = $request->getParsedBody()['Descripcion'];
        $mesa  = $request->getParsedBody()['Mesa'];
        $restaurante  = $request->getParsedBody()['Restaurante'];
        $atencion   = $request->getParsedBody()['Atencion'];
        $cocina = $request->getParsedBody()['Cocina'];

        if (($mesa < 11 && $mesa > 0) && ($restaurante < 11 && $restaurante > 0) && ($atencion < 11 && $atencion > 0) && ($cocina < 11 && $cocina > 0)) {
            if (strlen($descripcion) <= 66) {
                $review = new Review;
                $review->order = $orden;
                $review->name = $nombreCliente;
                $review->email = $mailCliente;
                $review->description = $descripcion;
                $review->table_score = $mesa;
                $review->menu_score = $restaurante;
                $review->service_score = $atencion;
                $review->environment_score = $cocina;
                $rta = $review->save();
                $response->getBody()->write(json_encode($rta));
                return $response;
            } else {
                $response->getBody()->write("La reseña no debe superar los 66 caracteres");
                return $response->withStatus(401);
            }
        } else {
            $response->getBody()->write("Puntaje no valido");
            return $response->withStatus(401);
        }
    }


    public function login(Request $request, Response $response, $args)
    {

        $body = $request->getParsedBody();
        $email = $body['email'];
        $clave = $body['clave'];

        $usuarioEncontrado = json_decode(Usuario::whereRaw('email = ? AND clave = ?', array($email, $clave))->get());
        $key = 'Comandita';
        if ($usuarioEncontrado) {
            $payload = array(
                "email" => $usuarioEncontrado[0]->email,
                "clave" => $usuarioEncontrado[0]->clave,
                "tipo" => $usuarioEncontrado[0]->tipo,
            );

            $play = JWT::encode($payload, $key);
            $rta = new stdClass;
            $rta->data = $play;
            $response->getBody()->write(json_encode($rta));
        } else {
            $response->getBody()->write("Usuario no registrado");
            return $response->withStatus(402);
        }

        return $response->withHeader('Content-type', 'application/json');;
    }
}
