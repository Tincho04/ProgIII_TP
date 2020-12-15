<?php

namespace App\Controllers;

use \Firebase\JWT\JWT;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Usuario;
use App\Models\Logs;
use stdClass;

class EmpleadoController
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


    public function addOne(Request $request, Response $response, $args)
    {
        $user = new Usuario;

        $user->name = $request->getParsedBody()['usuario'];
        $user->password = $request->getParsedBody()['clave'];
        $user->first_name = $request->getParsedBody()['firstName'];
        $user->last_name = $request->getParsedBody()['lastName'];
        $user->email = $request->getParsedBody()['email'];
        $user->role = $request->getParsedBody()['rol'];

        if ($user->role >= 1 && $user->role <= 8) {
            if (strlen($user->password) >= 4) {
                $rta = $user->save();
                $response->getBody()->write(json_encode($rta));
            }
        } else {
            $response->getBody()->write("El tipo de usuario seleccionado no es válido");
            return $response->withStatus(401);
        }

        return $response;
    }

    public function updateOneUsuario(Request $request, Response $response, $args)
    {
        $id = $args['id'];

        $user = Usuario::find($id);

        $user->tipo = $request->getParsedBody()['tipo'];

        $rta = $user->save();

        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function deleteOne(Request $request, Response $response, $args)
    {
        $id = $args['id'];

        if (is_numeric($id)) {
            $user = Usuario::find($id);

            $rta = $user->delete();

            $response->getBody()->write(json_encode($rta));
            return $response;
        } else {
            $response->getBody()->write("Usuario no registrado");
            return $response->withStatus(402);
        }
    }


    public function login(Request $request, Response $response, $args)
    {

        $body = $request->getParsedBody();
        $user = $body['usuario'];
        $clave = $body['clave'];

        $usuarioEncontrado = json_decode(Usuario::whereRaw('name = ? AND password = ?', array($user, $clave))->get());
        $key = 'Comandita';
        if ($usuarioEncontrado) {
            $payload = array(
                "name" => $usuarioEncontrado[0]->name,
                "password" => $usuarioEncontrado[0]->password,
                "role" => $usuarioEncontrado[0]->role,
            );

            $id = $usuarioEncontrado[0]->id;
            $role = $usuarioEncontrado[0]->role;
            $this->loginUpdate($id);

            $play = JWT::encode($payload, $key);
            $rta = new stdClass;
            $rta->data = $play;
            $response->getBody()->write(json_encode($rta));

            $log = new Logs;

            $log->user = $id;
            $log->role = $role;
            $log->action = 'Login';

            $rta = $log->save();
        } else {
            $response->getBody()->write("Usuario no registrado");
            return $response->withStatus(402);
        }

        return $response->withHeader('Content-type', 'application/json');;
    }

    public function loginUpdate($id)
    {
        $usuario = Usuario::find($id);
        $usuario->last_login_at = date('d-m-y h:i:s');
        $usuario->save();
    }
}
