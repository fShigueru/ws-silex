<?php
require_once __DIR__.'/../vendor/autoload.php';
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Entity\Usuario;
use Entity\Publicacao;

$app = new Silex\Application();
$app['debug'] = true;

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver' => 'pdo_mysql',
        'dbhost' => 'localhost',
        'dbname' => 'ws',
        'user' => 'root',
        'password' => '',
    ),
));



$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {
    $twig->addFunction(new \Twig_SimpleFunction('asset', function ($asset) {
        // implement whatever logic you need to determine the asset path

        return sprintf("http://{$_SERVER['HTTP_HOST']}/%s", ltrim($asset, '/'));
    }));

    return $twig;
}));

$encoders = array(new XmlEncoder(), new JsonEncoder());
$normalizers = array(new ObjectNormalizer());
$serializer = new Serializer($normalizers, $encoders);
$response = new Response();


$app->get('/publicacoes/listar', function () use ($app,$serializer) {
    return $app['twig']->render('/publicacao/index.twig');
});


/**
 * Recupera todos os usuários
 * Method GET
 */
$app->get('/ws/usuarios', function () use ($app,$serializer,$response) {

    $sql = "SELECT * FROM usuario";
    $usuarios = $app['db']->fetchAll($sql);
    $json = $serializer->serialize($usuarios, 'json');

    $response->setStatusCode(200);
    $response->setContent($json);
    $response->headers->set('Content-type', 'application/json');
    return $response;
});

/**
 * Autentica os usuários
 * Method POST
 */
$app->post('/ws/usuario/auth', function (Request $request) use ($app,$serializer,$response) {
    $usuario = new Usuario($request->get('login'),$request->get('senha'));

    $sql = "SELECT * FROM usuario WHERE login = ? AND senha = ?";
    $auth = $app['db']->fetchAssoc($sql, array($usuario->getLogin(), $usuario->getSenha()));

    if($auth == null){
        return new Response('Usuário não existe', 201);
    }

    $json = $serializer->serialize($auth, 'json');
    $response->setStatusCode(200);
    $response->setContent($json);
    $response->headers->set('Content-type', 'application/json');
    return $response;
});

/**
 * Recupera todos as publicações
 * Method GET
 */
$app->get('/ws/publicacoes', function () use ($app,$serializer,$response) {

    $sql = "SELECT publicacao.*, usuario.nome FROM publicacao INNER JOIN usuario ON usuario.id = publicacao.usuario_id";
    $publicacoes = $app['db']->fetchAll($sql);
    $json = $serializer->serialize($publicacoes, 'json');

    $response->setStatusCode(200);
    $response->setContent($json);
    $response->headers->set('Content-type', 'application/json');
    return $response;
});

/**
 * Persiste publicação
 * Method POST
 */
$app->post('/ws/publicacao/save', function (Request $request) use ($app,$serializer,$response) {

    $publicacao = new Publicacao();
    $publicacao->setUsuarioId($request->get('usuarioId'));
    $publicacao->setDescricao($request->get('descricao'));
    $publicacao->setFotoLocal($request->get('fotoLocal'));
    $publicacao->setFotoWS($request->get('fotoWS'));

    $retorno = $app['db']->insert('publicacao',
        [
            'usuario_id' => $publicacao->getUsuarioId(),
            'descricao' => $publicacao->getDescricao(),
            'fotoWS' => $publicacao->getFotoWS(),
            'fotoLocal' => $publicacao->getFotoLocal()
        ]
    );
    $json = null;
    if($retorno == 1){
        $json = $serializer->serialize(['retorno' => 1], 'json');
    }else{
        $json = $serializer->serialize(['retorno' => 0], 'json');
    }
    $response->setStatusCode(200);
    $response->setContent($json);
    $response->headers->set('Content-type', 'application/json');
    return $response;
});

$app->run();