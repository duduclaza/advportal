<?php
/**
 * Model de Caso/Processo
 */

class Caso {
    private $conn;
    private $table = 'casos';

    public $id;
    public $numero_processo;
    public $titulo;
    public $descricao;
    public $cliente_id;
    public $advogado_responsavel_id;
    public $tipo_acao;
    public $valor_causa;
    public $data_abertura;
    public $data_encerramento;
    public $status;
    public $prioridade;
    public $tribunal;
    public $comarca;
    public $vara;
    public $observacoes;
    public $created_by;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Criar caso
    public function criar() {
        $query = "INSERT INTO " . $this->table . " 
                  (numero_processo, titulo, descricao, cliente_id, advogado_responsavel_id, 
                   tipo_acao, valor_causa, data_abertura, status, prioridade, 
                   tribunal, comarca, vara, observacoes, created_by) 
                  VALUES 
                  (:numero_processo, :titulo, :descricao, :cliente_id, :advogado_responsavel_id,
                   :tipo_acao, :valor_causa, :data_abertura, :status, :prioridade,
                   :tribunal, :comarca, :vara, :observacoes, :created_by)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':numero_processo', $this->numero_processo);
        $stmt->bindParam(':titulo', $this->titulo);
        $stmt->bindParam(':descricao', $this->descricao);
        $stmt->bindParam(':cliente_id', $this->cliente_id);
        $stmt->bindParam(':advogado_responsavel_id', $this->advogado_responsavel_id);
        $stmt->bindParam(':tipo_acao', $this->tipo_acao);
        $stmt->bindParam(':valor_causa', $this->valor_causa);
        $stmt->bindParam(':data_abertura', $this->data_abertura);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':prioridade', $this->prioridade);
        $stmt->bindParam(':tribunal', $this->tribunal);
        $stmt->bindParam(':comarca', $this->comarca);
        $stmt->bindParam(':vara', $this->vara);
        $stmt->bindParam(':observacoes', $this->observacoes);
        $stmt->bindParam(':created_by', $this->created_by);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }

        return false;
    }

    // Listar casos
    public function listar($usuario_id = null, $perfil_nivel = null) {
        $query = "SELECT c.*, 
                         cli.nome as cliente_nome, cli.email as cliente_email,
                         adv.nome as advogado_nome, adv.email as advogado_email,
                         u.nome as criado_por_nome
                  FROM " . $this->table . " c
                  LEFT JOIN usuarios cli ON c.cliente_id = cli.id
                  LEFT JOIN usuarios adv ON c.advogado_responsavel_id = adv.id
                  LEFT JOIN usuarios u ON c.created_by = u.id";

        // Se for cliente, mostrar apenas seus casos
        if ($perfil_nivel == 5) {
            $query .= " WHERE c.cliente_id = :usuario_id";
        }
        // Se for advogado, mostrar casos onde é responsável
        elseif ($perfil_nivel == 4) {
            $query .= " WHERE c.advogado_responsavel_id = :usuario_id";
        }

        $query .= " ORDER BY c.created_at DESC";

        $stmt = $this->conn->prepare($query);

        if (in_array($perfil_nivel, [4, 5])) {
            $stmt->bindParam(':usuario_id', $usuario_id);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar por ID
    public function buscarPorId($id) {
        $query = "SELECT c.*, 
                         cli.nome as cliente_nome, cli.email as cliente_email, cli.telefone as cliente_telefone,
                         adv.nome as advogado_nome, adv.email as advogado_email,
                         u.nome as criado_por_nome
                  FROM " . $this->table . " c
                  LEFT JOIN usuarios cli ON c.cliente_id = cli.id
                  LEFT JOIN usuarios adv ON c.advogado_responsavel_id = adv.id
                  LEFT JOIN usuarios u ON c.created_by = u.id
                  WHERE c.id = :id
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Atualizar caso
    public function atualizar() {
        $query = "UPDATE " . $this->table . " 
                  SET titulo = :titulo,
                      descricao = :descricao,
                      advogado_responsavel_id = :advogado_responsavel_id,
                      tipo_acao = :tipo_acao,
                      valor_causa = :valor_causa,
                      status = :status,
                      prioridade = :prioridade,
                      tribunal = :tribunal,
                      comarca = :comarca,
                      vara = :vara,
                      observacoes = :observacoes
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':titulo', $this->titulo);
        $stmt->bindParam(':descricao', $this->descricao);
        $stmt->bindParam(':advogado_responsavel_id', $this->advogado_responsavel_id);
        $stmt->bindParam(':tipo_acao', $this->tipo_acao);
        $stmt->bindParam(':valor_causa', $this->valor_causa);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':prioridade', $this->prioridade);
        $stmt->bindParam(':tribunal', $this->tribunal);
        $stmt->bindParam(':comarca', $this->comarca);
        $stmt->bindParam(':vara', $this->vara);
        $stmt->bindParam(':observacoes', $this->observacoes);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }

    // Deletar caso
    public function deletar() {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        
        return $stmt->execute();
    }

    // Buscar estatísticas
    public function estatisticas($usuario_id = null, $perfil_nivel = null) {
        $where = "";
        
        if ($perfil_nivel == 5) {
            $where = " WHERE cliente_id = :usuario_id";
        } elseif ($perfil_nivel == 4) {
            $where = " WHERE advogado_responsavel_id = :usuario_id";
        }

        $query = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'aberto' THEN 1 ELSE 0 END) as abertos,
                    SUM(CASE WHEN status = 'em_andamento' THEN 1 ELSE 0 END) as em_andamento,
                    SUM(CASE WHEN status = 'encerrado' THEN 1 ELSE 0 END) as encerrados,
                    SUM(CASE WHEN prioridade = 'urgente' THEN 1 ELSE 0 END) as urgentes
                  FROM " . $this->table . $where;

        $stmt = $this->conn->prepare($query);

        if (in_array($perfil_nivel, [4, 5])) {
            $stmt->bindParam(':usuario_id', $usuario_id);
        }

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
