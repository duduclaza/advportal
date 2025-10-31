<?php
/**
 * Middleware de Autenticação
 */

require_once __DIR__ . '/../config/jwt.php';

class AuthMiddleware {
    
    public static function verificarToken() {
        $headers = getallheaders();
        
        if (!isset($headers['Authorization'])) {
            http_response_code(401);
            echo json_encode(['status' => 401, 'message' => 'Token não fornecido']);
            exit();
        }

        $authHeader = $headers['Authorization'];
        $token = str_replace('Bearer ', '', $authHeader);

        $payload = JWT::decode($token);

        if (!$payload) {
            http_response_code(401);
            echo json_encode(['status' => 401, 'message' => 'Token inválido ou expirado']);
            exit();
        }

        return $payload;
    }

    public static function verificarPermissao($usuario, $niveisPermitidos = []) {
        if (!in_array($usuario['perfil_nivel'], $niveisPermitidos)) {
            http_response_code(403);
            echo json_encode(['status' => 403, 'message' => 'Você não tem permissão para acessar este recurso']);
            exit();
        }
    }
}
