<?php

require '../src/app/BD.php';

require '../vendor/autoload.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selective\BasePath\BasePathMiddleware;
use Slim\Factory\AppFactory;
use \PDO as PDO;
use \App\BD as BD;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS');
header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Accept, Origin, Authorization');

$app = AppFactory::create();

$app->addRoutingMiddleware();
$app->add(new BasePathMiddleware($app));
$app->addErrorMiddleware(true, true, true);

$app->get('/provincia/{nombre_prov}/municipio/{nombre_mun}', function (Request $request, Response $response, $args) {
    try {
        $bd = BD::getConexion();
        
        $nombre_prov = $args["nombre_prov"];
        $nombre_mun = $args["nombre_mun"];
        
        $sql = "SELECT nombre, precio FROM municipios WHERE id_prov = (SELECT id FROM provincias WHERE nombre = :nombre_prov) AND nombre = :nombre_mun;";
        $sth = $bd->prepare($sql);
        $sth->execute([":nombre_prov" => $nombre_prov, ":nombre_mun" => $nombre_mun]);
        $sth->setFetchMode(PDO::FETCH_OBJ);
        $precio_row = ($sth->fetch()) ?: null;
        
        if (is_null($precio_row)) {
            $datos = [
                "nombre" => $nombre_mun,
                "precio" => "1610"
            ];
        }
        else {
            $datos = [
                "nombre" => $precio_row->nombre,
                "precio" => $precio_row->precio
            ];
        }

        $response->getBody()->write(json_encode($datos));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    } catch (PDOException $error) {
        $response->getBody()->write(json_encode($error));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(500);
    }
});

$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write("El punto final del API es: /provincia/{nombre_prov}/municipio/{nombre_mun}");
    return $response;
})->setName('root');

$app->run();
