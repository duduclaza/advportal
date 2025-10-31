<?php
/**
 * API Router Principal (Versão com .env)
 */

// Carregar bootstrap
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/config/cors.php';
require_once __DIR__ . '/middleware/AuthMiddleware.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/UsuarioController.php';
require_once __DIR__ . '/controllers/CasoController.php';

// Obter método e URI
$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = str_replace('/api', '', $uri);

// Obter dados da requisição
$data = json_decode(file_get_contents("php://input"), true) ?? [];

// Instanciar controladores
$authController = new AuthController();
$usuarioController = new UsuarioController();
$casoController = new CasoController();

// Rotas públicas (sem autenticação)
if ($uri === '/auth/login' && $method === 'POST') {
    echo $authController->login($data);
    exit();
}

if ($uri === '/auth/solicitar-codigo' && $method === 'POST') {
    echo $authController->solicitarCodigo($data);
    exit();
}

if ($uri === '/auth/confirmar-codigo' && $method === 'POST') {
    echo $authController->confirmarCodigo($data);
    exit();
}

// Verificar autenticação para rotas protegidas
$usuario = AuthMiddleware::verificarToken();

// Rotas de usuários
if (preg_match('/^\/usuarios$/', $uri) && $method === 'GET') {
    $perfil_id = $_GET['perfil_id'] ?? null;
    echo $usuarioController->listar($usuario, $perfil_id);
    exit();
}

if (preg_match('/^\/usuarios$/', $uri) && $method === 'POST') {
    echo $usuarioController->criar($data, $usuario);
    exit();
}

if (preg_match('/^\/usuarios\/(\d+)$/', $uri, $matches) && $method === 'GET') {
    echo $usuarioController->buscarPorId($matches[1], $usuario);
    exit();
}

if (preg_match('/^\/usuarios\/(\d+)$/', $uri, $matches) && $method === 'PUT') {
    echo $usuarioController->atualizar($matches[1], $data, $usuario);
    exit();
}

if (preg_match('/^\/usuarios\/(\d+)$/', $uri, $matches) && $method === 'DELETE') {
    echo $usuarioController->deletar($matches[1], $usuario);
    exit();
}

// Rotas de casos
if (preg_match('/^\/casos$/', $uri) && $method === 'GET') {
    echo $casoController->listar($usuario);
    exit();
}

if (preg_match('/^\/casos$/', $uri) && $method === 'POST') {
    echo $casoController->criar($data, $usuario);
    exit();
}

if (preg_match('/^\/casos\/(\d+)$/', $uri, $matches) && $method === 'GET') {
    echo $casoController->buscarPorId($matches[1], $usuario);
    exit();
}

if (preg_match('/^\/casos\/(\d+)$/', $uri, $matches) && $method === 'PUT') {
    echo $casoController->atualizar($matches[1], $data, $usuario);
    exit();
}

if (preg_match('/^\/casos\/(\d+)$/', $uri, $matches) && $method === 'DELETE') {
    echo $casoController->deletar($matches[1], $usuario);
    exit();
}

if (preg_match('/^\/casos\/estatisticas$/', $uri) && $method === 'GET') {
    echo $casoController->estatisticas($usuario);
    exit();
}

// Rota de perfis
if (preg_match('/^\/perfis$/', $uri) && $method === 'GET') {
    require_once __DIR__ . '/models/Perfil.php';
    require_once __DIR__ . '/config/database_env.php';
    
    $database = new Database();
    $db = $database->getConnection();
    $perfil = new Perfil($db);
    
    $perfis = $perfil->listar();
    
    http_response_code(200);
    echo json_encode([
        'status' => 200,
        'message' => 'Perfis listados com sucesso',
        'data' => $perfis
    ]);
    exit();
}

// Rota não encontrada
http_response_code(404);
echo json_encode([
    'status' => 404,
    'message' => 'Rota não encontrada'
]);
