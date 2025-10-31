<?php
/**
 * Model de Usuário
 */

class Usuario {
    private $conn;
    private $table = 'usuarios';

    public $id;
    public $nome;
    public $email;
    public $senha;
    public $perfil_id;
    public $telefone;
    public $cpf;
    public $status;
    public $primeiro_acesso;
    public $codigo_confirmacao;
    public $codigo_expiracao;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Criar usuário
    public function criar() {
        $query = "INSERT INTO " . $this->table . " 
                  (nome, email, senha, perfil_id, telefone, cpf, status, primeiro_acesso) 
                  VALUES 
                  (:nome, :email, :senha, :perfil_id, :telefone, :cpf, :status, :primeiro_acesso)";

        $stmt = $this->conn->prepare($query);

        $this->senha = password_hash($this->senha, PASSWORD_BCRYPT);

        $stmt->bindParam(':nome', $this->nome);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':senha', $this->senha);
        $stmt->bindParam(':perfil_id', $this->perfil_id);
        $stmt->bindParam(':telefone', $this->telefone);
        $stmt->bindParam(':cpf', $this->cpf);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':primeiro_acesso', $this->primeiro_acesso);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Buscar por email
    public function buscarPorEmail() {
        $query = "SELECT u.*, p.nome as perfil_nome, p.nivel as perfil_nivel 
                  FROM " . $this->table . " u
                  LEFT JOIN perfis p ON u.perfil_id = p.id
                  WHERE u.email = :email 
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Buscar por ID
    public function buscarPorId($id) {
        $query = "SELECT u.*, p.nome as perfil_nome, p.nivel as perfil_nivel 
                  FROM " . $this->table . " u
                  LEFT JOIN perfis p ON u.perfil_id = p.id
                  WHERE u.id = :id 
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Listar todos
    public function listar($perfil_id = null) {
        $query = "SELECT u.*, p.nome as perfil_nome, p.nivel as perfil_nivel 
                  FROM " . $this->table . " u
                  LEFT JOIN perfis p ON u.perfil_id = p.id";
        
        if ($perfil_id) {
            $query .= " WHERE u.perfil_id = :perfil_id";
        }
        
        $query .= " ORDER BY u.created_at DESC";

        $stmt = $this->conn->prepare($query);
        
        if ($perfil_id) {
            $stmt->bindParam(':perfil_id', $perfil_id);
        }
        
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Atualizar usuário
    public function atualizar() {
        $query = "UPDATE " . $this->table . " 
                  SET nome = :nome, 
                      telefone = :telefone, 
                      perfil_id = :perfil_id,
                      status = :status
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':nome', $this->nome);
        $stmt->bindParam(':telefone', $this->telefone);
        $stmt->bindParam(':perfil_id', $this->perfil_id);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }

    // Atualizar senha
    public function atualizarSenha($nova_senha) {
        $query = "UPDATE " . $this->table . " 
                  SET senha = :senha, 
                      primeiro_acesso = FALSE,
                      ultima_senha_alteracao = NOW()
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $senha_hash = password_hash($nova_senha, PASSWORD_BCRYPT);
        
        $stmt->bindParam(':senha', $senha_hash);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }

    // Gerar código de confirmação
    public function gerarCodigoConfirmacao() {
        $codigo = sprintf("%06d", mt_rand(1, 999999));
        $expiracao = date('Y-m-d H:i:s', strtotime('+15 minutes'));

        $query = "UPDATE " . $this->table . " 
                  SET codigo_confirmacao = :codigo,
                      codigo_expiracao = :expiracao
                  WHERE email = :email";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':codigo', $codigo);
        $stmt->bindParam(':expiracao', $expiracao);
        $stmt->bindParam(':email', $this->email);

        if ($stmt->execute()) {
            return $codigo;
        }

        return false;
    }

    // Validar código de confirmação
    public function validarCodigo($codigo) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE email = :email 
                  AND codigo_confirmacao = :codigo 
                  AND codigo_expiracao > NOW()
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':codigo', $codigo);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Deletar usuário
    public function deletar() {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        
        return $stmt->execute();
    }

    // Verificar se email existe
    public function emailExiste() {
        $query = "SELECT id FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }

    // Verificar se CPF existe
    public function cpfExiste() {
        $query = "SELECT id FROM " . $this->table . " WHERE cpf = :cpf LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':cpf', $this->cpf);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }
}
