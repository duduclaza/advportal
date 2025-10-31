<?php
/**
 * Controller de Usuários
 */

require_once __DIR__ . '/../config/database_env.php';
require_once __DIR__ . '/../models/Usuario.php';

class UsuarioController {
    private $db;
    private $usuario;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->usuario = new Usuario($this->db);
    }

    // Criar usuário
    public function criar($data, $usuario_logado) {
        // Verificar permissão (apenas Super Admin e Admin)
        if (!in_array($usuario_logado['perfil_nivel'], [1, 2])) {
            return $this->response(403, 'Você não tem permissão para criar usuários');
        }

        // Validar dados obrigatórios
        if (!isset($data['nome']) || !isset($data['email']) || !isset($data['perfil_id'])) {
            return $this->response(400, 'Nome, email e perfil são obrigatórios');
        }

        // Validar email
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return $this->response(400, 'Email inválido');
        }

        // Verificar se email já existe
        $this->usuario->email = $data['email'];
        if ($this->usuario->emailExiste()) {
            return $this->response(400, 'Email já cadastrado');
        }

        // Verificar se CPF já existe (se fornecido)
        if (isset($data['cpf']) && !empty($data['cpf'])) {
            $this->usuario->cpf = $data['cpf'];
            if ($this->usuario->cpfExiste()) {
                return $this->response(400, 'CPF já cadastrado');
            }
        }

        // Definir dados
        $this->usuario->nome = $data['nome'];
        $this->usuario->email = $data['email'];
        $this->usuario->senha = bin2hex(random_bytes(8)); // Senha temporária
        $this->usuario->perfil_id = $data['perfil_id'];
        $this->usuario->telefone = $data['telefone'] ?? null;
        $this->usuario->cpf = $data['cpf'] ?? null;
        $this->usuario->status = 'pendente';
        $this->usuario->primeiro_acesso = true;

        if ($this->usuario->criar()) {
            // Gerar código de confirmação
            $codigo = $this->usuario->gerarCodigoConfirmacao();
            
            // TODO: Enviar email com código
            
            return $this->response(201, 'Usuário criado com sucesso. Email de confirmação enviado.', [
                'codigo' => $codigo // Remover em produção
            ]);
        }

        return $this->response(500, 'Erro ao criar usuário');
    }

    // Listar usuários
    public function listar($usuario_logado, $perfil_id = null) {
        // Verificar permissão
        if (!in_array($usuario_logado['perfil_nivel'], [1, 2, 3])) {
            return $this->response(403, 'Você não tem permissão para listar usuários');
        }

        $usuarios = $this->usuario->listar($perfil_id);

        // Remover senhas da resposta
        foreach ($usuarios as &$user) {
            unset($user['senha']);
            unset($user['codigo_confirmacao']);
        }

        return $this->response(200, 'Usuários listados com sucesso', $usuarios);
    }

    // Buscar por ID
    public function buscarPorId($id, $usuario_logado) {
        // Usuário pode ver apenas seus próprios dados, exceto admin
        if ($id != $usuario_logado['usuario_id'] && !in_array($usuario_logado['perfil_nivel'], [1, 2, 3])) {
            return $this->response(403, 'Você não tem permissão para visualizar este usuário');
        }

        $usuario = $this->usuario->buscarPorId($id);

        if (!$usuario) {
            return $this->response(404, 'Usuário não encontrado');
        }

        unset($usuario['senha']);
        unset($usuario['codigo_confirmacao']);

        return $this->response(200, 'Usuário encontrado', $usuario);
    }

    // Atualizar usuário
    public function atualizar($id, $data, $usuario_logado) {
        // Verificar permissão
        if ($id != $usuario_logado['usuario_id'] && !in_array($usuario_logado['perfil_nivel'], [1, 2])) {
            return $this->response(403, 'Você não tem permissão para atualizar este usuário');
        }

        $usuario_atual = $this->usuario->buscarPorId($id);
        if (!$usuario_atual) {
            return $this->response(404, 'Usuário não encontrado');
        }

        $this->usuario->id = $id;
        $this->usuario->nome = $data['nome'] ?? $usuario_atual['nome'];
        $this->usuario->telefone = $data['telefone'] ?? $usuario_atual['telefone'];
        $this->usuario->perfil_id = $data['perfil_id'] ?? $usuario_atual['perfil_id'];
        $this->usuario->status = $data['status'] ?? $usuario_atual['status'];

        // Apenas admin pode alterar perfil e status
        if ($id == $usuario_logado['usuario_id']) {
            $this->usuario->perfil_id = $usuario_atual['perfil_id'];
            $this->usuario->status = $usuario_atual['status'];
        }

        if ($this->usuario->atualizar()) {
            return $this->response(200, 'Usuário atualizado com sucesso');
        }

        return $this->response(500, 'Erro ao atualizar usuário');
    }

    // Deletar usuário
    public function deletar($id, $usuario_logado) {
        // Apenas Super Admin pode deletar
        if ($usuario_logado['perfil_nivel'] != 1) {
            return $this->response(403, 'Apenas Super Admin pode deletar usuários');
        }

        // Não pode deletar a si mesmo
        if ($id == $usuario_logado['usuario_id']) {
            return $this->response(400, 'Você não pode deletar seu próprio usuário');
        }

        $this->usuario->id = $id;
        
        if ($this->usuario->deletar()) {
            return $this->response(200, 'Usuário deletado com sucesso');
        }

        return $this->response(500, 'Erro ao deletar usuário');
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
