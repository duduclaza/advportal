<?php
/**
 * Controller de Autenticação
 */

require_once __DIR__ . '/../config/database.example.php';
require_once __DIR__ . '/../config/jwt.php';
require_once __DIR__ . '/../models/Usuario.php';

class AuthController {
    private $db;
    private $usuario;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->usuario = new Usuario($this->db);
    }

    // Login
    public function login($data) {
        if (!isset($data['email']) || !isset($data['senha'])) {
            return $this->response(400, 'Email e senha são obrigatórios');
        }

        $this->usuario->email = $data['email'];
        $usuario_data = $this->usuario->buscarPorEmail();

        if (!$usuario_data) {
            return $this->response(401, 'Credenciais inválidas');
        }

        if ($usuario_data['status'] !== 'ativo') {
            return $this->response(403, 'Usuário inativo. Entre em contato com o administrador.');
        }

        if (!password_verify($data['senha'], $usuario_data['senha'])) {
            return $this->response(401, 'Credenciais inválidas');
        }

        // Gerar token JWT
        $payload = [
            'usuario_id' => $usuario_data['id'],
            'email' => $usuario_data['email'],
            'nome' => $usuario_data['nome'],
            'perfil_id' => $usuario_data['perfil_id'],
            'perfil_nivel' => $usuario_data['perfil_nivel'],
            'perfil_nome' => $usuario_data['perfil_nome'],
            'exp' => time() + JWT_EXPIRATION
        ];

        $token = JWT::encode($payload);

        // Registrar log de acesso
        $this->registrarLog($usuario_data['id'], 'login');

        return $this->response(200, 'Login realizado com sucesso', [
            'token' => $token,
            'usuario' => [
                'id' => $usuario_data['id'],
                'nome' => $usuario_data['nome'],
                'email' => $usuario_data['email'],
                'perfil' => $usuario_data['perfil_nome'],
                'perfil_nivel' => $usuario_data['perfil_nivel'],
                'primeiro_acesso' => (bool)$usuario_data['primeiro_acesso']
            ]
        ]);
    }

    // Primeiro acesso - solicitar código
    public function solicitarCodigo($data) {
        if (!isset($data['email'])) {
            return $this->response(400, 'Email é obrigatório');
        }

        $this->usuario->email = $data['email'];
        $usuario_data = $this->usuario->buscarPorEmail();

        if (!$usuario_data) {
            return $this->response(404, 'Email não encontrado');
        }

        $codigo = $this->usuario->gerarCodigoConfirmacao();

        if ($codigo) {
            // TODO: Enviar email com o código
            // Por enquanto, retornar o código na resposta (apenas para desenvolvimento)
            return $this->response(200, 'Código enviado para o email', [
                'codigo' => $codigo, // Remover em produção
                'mensagem' => 'Código de confirmação enviado para o email'
            ]);
        }

        return $this->response(500, 'Erro ao gerar código');
    }

    // Validar código e cadastrar senha
    public function confirmarCodigo($data) {
        if (!isset($data['email']) || !isset($data['codigo']) || !isset($data['nova_senha'])) {
            return $this->response(400, 'Email, código e nova senha são obrigatórios');
        }

        if (strlen($data['nova_senha']) < 6) {
            return $this->response(400, 'A senha deve ter no mínimo 6 caracteres');
        }

        $this->usuario->email = $data['email'];
        $usuario_data = $this->usuario->validarCodigo($data['codigo']);

        if (!$usuario_data) {
            return $this->response(400, 'Código inválido ou expirado');
        }

        $this->usuario->id = $usuario_data['id'];
        
        if ($this->usuario->atualizarSenha($data['nova_senha'])) {
            return $this->response(200, 'Senha cadastrada com sucesso. Faça login com suas credenciais.');
        }

        return $this->response(500, 'Erro ao cadastrar senha');
    }

    // Validar token
    public function validarToken($token) {
        $payload = JWT::decode($token);
        
        if (!$payload) {
            return null;
        }

        return $payload;
    }

    // Registrar log de acesso
    private function registrarLog($usuario_id, $acao) {
        $query = "INSERT INTO logs_acesso (usuario_id, acao, ip_address, user_agent) 
                  VALUES (:usuario_id, :acao, :ip, :user_agent)";
        
        $stmt = $this->db->prepare($query);
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
        
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->bindParam(':acao', $acao);
        $stmt->bindParam(':ip', $ip);
        $stmt->bindParam(':user_agent', $user_agent);
        
        $stmt->execute();
    }

    // Resposta padronizada
    private function response($status, $message, $data = null) {
        http_response_code($status);
        
        $response = [
            'status' => $status,
            'message' => $message
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        return json_encode($response);
    }
}
