<?php
/**
 * Bootstrap - Carrega dependências e configurações
 */

// Carregar autoload do Composer
require_once __DIR__ . '/../vendor/autoload.php';

// Carregar variáveis de ambiente
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Definir constantes a partir do .env
define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
define('DB_NAME', $_ENV['DB_NAME'] ?? 'advportal_db');
define('DB_USER', $_ENV['DB_USER'] ?? 'root');
define('DB_PASS', $_ENV['DB_PASS'] ?? '');
define('DB_CHARSET', $_ENV['DB_CHARSET'] ?? 'utf8mb4');

define('JWT_SECRET_KEY', $_ENV['JWT_SECRET_KEY'] ?? 'sua_chave_secreta_aqui');
define('JWT_ALGORITHM', $_ENV['JWT_ALGORITHM'] ?? 'HS256');
define('JWT_EXPIRATION', $_ENV['JWT_EXPIRATION'] ?? 86400);

define('APP_ENV', $_ENV['APP_ENV'] ?? 'development');
define('APP_DEBUG', $_ENV['APP_DEBUG'] ?? 'true');
define('APP_URL', $_ENV['APP_URL'] ?? 'http://localhost:8000');
