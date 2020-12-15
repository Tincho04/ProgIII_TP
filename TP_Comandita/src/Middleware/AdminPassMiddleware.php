<?php

namespace App\Middleware;
use \Firebase\JWT\JWT;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
// use Psr\Http\Message\ResponseInterface as Response;

class AdminPassMiddleware
{
    /**
     * Example middleware invokable class
     *
     * @param  ServerRequest  $request PSR-7 request
     * @param  RequestHandler $handler PSR-15 request handler
     *
     * @return Response
     */
    
    // private $tipo;
    // public function __construct($tipo)
    // {
    //     $this->tipo=$tipo;
    // }

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $headers = getallHeaders();
        $token=$headers['token'];
        $decoded = JWT::decode($token,"Comandita", array('HS256'));
 
        if ($decoded->role != 1) {
            $response = new Response();
            $response->getBody()->write("Usuario no autorizado");
            
            return $response->withStatus(403);
        } else {
            $response = $handler->handle($request);
            $existingContent = (string) $response->getBody();
            $resp = new Response();
            $resp->getBody()->write($existingContent);
            return $resp;
        }
    }
}
