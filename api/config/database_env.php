<?php
/**
 * Configuração do Banco de Dados usando .env
 * Este arquivo carrega as credenciais do arquivo .env
 */

// Carregar bootstrap se ainda não foi carregado
if (!defined('DB_HOST')) {
    require_once __DIR__ . '/../bootstrap.php';
}

class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $charset;
    private $conn = null;

    public function __construct() {
        $this->host = DB_HOST;
        $this->db_name = DB_NAME;
        $this->username = DB_USER;
        $this->password = DB_PASS;
        $this->charset = DB_CHARSET;
    }

    public function getConnection() {
        if ($this->conn !== null) {
            return $this->conn;
        }

        try {
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=" . $this->charset;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_PERSISTENT => false,
            ];
            
            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
        } catch(PDOException $e) {
            $errorMsg = APP_DEBUG === 'true' 
                ? "Erro de conexão: " . $e->getMessage()
                : "Erro ao conectar ao banco de dados.";
            
            http_response_code(500);
            echo json_encode([
                'status' => 500,
                'message' => $errorMsg
            ]);
            die();
        }

        return $this->conn;
    }
}
