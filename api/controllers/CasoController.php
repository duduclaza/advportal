<?php
/**
 * Controller de Casos
 */

require_once __DIR__ . '/../config/database.example.php';
require_once __DIR__ . '/../models/Caso.php';

class CasoController {
    private $db;
    private $caso;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->caso = new Caso($this->db);
    }

    // Criar caso
    public function criar($data, $usuario_logado) {
        // Validar dados obrigatórios
        if (!isset($data['titulo']) || !isset($data['cliente_id']) || !isset($data['data_abertura'])) {
            return $this->response(400, 'Título, cliente e data de abertura são obrigatórios');
        }

        // Cliente não pode criar casos
        if ($usuario_logado['perfil_nivel'] == 5) {
            return $this->response(403, 'Clientes não podem criar casos');
        }

        // Definir dados
        $this->caso->numero_processo = $data['numero_processo'] ?? null;
        $this->caso->titulo = $data['titulo'];
        $this->caso->descricao = $data['descricao'] ?? null;
        $this->caso->cliente_id = $data['cliente_id'];
        $this->caso->advogado_responsavel_id = $data['advogado_responsavel_id'] ?? null;
        $this->caso->tipo_acao = $data['tipo_acao'] ?? null;
        $this->caso->valor_causa = $data['valor_causa'] ?? null;
        $this->caso->data_abertura = $data['data_abertura'];
        $this->caso->status = $data['status'] ?? 'aberto';
        $this->caso->prioridade = $data['prioridade'] ?? 'media';
        $this->caso->tribunal = $data['tribunal'] ?? null;
        $this->caso->comarca = $data['comarca'] ?? null;
        $this->caso->vara = $data['vara'] ?? null;
        $this->caso->observacoes = $data['observacoes'] ?? null;
        $this->caso->created_by = $usuario_logado['usuario_id'];

        $caso_id = $this->caso->criar();

        if ($caso_id) {
            return $this->response(201, 'Caso criado com sucesso', ['id' => $caso_id]);
        }

        return $this->response(500, 'Erro ao criar caso');
    }

    // Listar casos
    public function listar($usuario_logado) {
        $casos = $this->caso->listar(
            $usuario_logado['usuario_id'],
            $usuario_logado['perfil_nivel']
        );

        return $this->response(200, 'Casos listados com sucesso', $casos);
    }

    // Buscar por ID
    public function buscarPorId($id, $usuario_logado) {
        $caso = $this->caso->buscarPorId($id);

        if (!$caso) {
            return $this->response(404, 'Caso não encontrado');
        }

        // Verificar permissão
        $perfil_nivel = $usuario_logado['perfil_nivel'];
        $usuario_id = $usuario_logado['usuario_id'];

        // Cliente só pode ver seus próprios casos
        if ($perfil_nivel == 5 && $caso['cliente_id'] != $usuario_id) {
            return $this->response(403, 'Você não tem permissão para visualizar este caso');
        }

        // Advogado só pode ver casos onde é responsável
        if ($perfil_nivel == 4 && $caso['advogado_responsavel_id'] != $usuario_id) {
            return $this->response(403, 'Você não tem permissão para visualizar este caso');
        }

        return $this->response(200, 'Caso encontrado', $caso);
    }

    // Atualizar caso
    public function atualizar($id, $data, $usuario_logado) {
        $caso_atual = $this->caso->buscarPorId($id);

        if (!$caso_atual) {
            return $this->response(404, 'Caso não encontrado');
        }

        // Verificar permissão
        $perfil_nivel = $usuario_logado['perfil_nivel'];
        $usuario_id = $usuario_logado['usuario_id'];

        // Cliente não pode atualizar casos
        if ($perfil_nivel == 5) {
            return $this->response(403, 'Clientes não podem atualizar casos');
        }

        // Advogado só pode atualizar casos onde é responsável
        if ($perfil_nivel == 4 && $caso_atual['advogado_responsavel_id'] != $usuario_id) {
            return $this->response(403, 'Você não tem permissão para atualizar este caso');
        }

        $this->caso->id = $id;
        $this->caso->titulo = $data['titulo'] ?? $caso_atual['titulo'];
        $this->caso->descricao = $data['descricao'] ?? $caso_atual['descricao'];
        $this->caso->advogado_responsavel_id = $data['advogado_responsavel_id'] ?? $caso_atual['advogado_responsavel_id'];
        $this->caso->tipo_acao = $data['tipo_acao'] ?? $caso_atual['tipo_acao'];
        $this->caso->valor_causa = $data['valor_causa'] ?? $caso_atual['valor_causa'];
        $this->caso->status = $data['status'] ?? $caso_atual['status'];
        $this->caso->prioridade = $data['prioridade'] ?? $caso_atual['prioridade'];
        $this->caso->tribunal = $data['tribunal'] ?? $caso_atual['tribunal'];
        $this->caso->comarca = $data['comarca'] ?? $caso_atual['comarca'];
        $this->caso->vara = $data['vara'] ?? $caso_atual['vara'];
        $this->caso->observacoes = $data['observacoes'] ?? $caso_atual['observacoes'];

        if ($this->caso->atualizar()) {
            return $this->response(200, 'Caso atualizado com sucesso');
        }

        return $this->response(500, 'Erro ao atualizar caso');
    }

    // Deletar caso
    public function deletar($id, $usuario_logado) {
        // Apenas Super Admin e Admin podem deletar
        if (!in_array($usuario_logado['perfil_nivel'], [1, 2])) {
            return $this->response(403, 'Você não tem permissão para deletar casos');
        }

        $caso = $this->caso->buscarPorId($id);
        if (!$caso) {
            return $this->response(404, 'Caso não encontrado');
        }

        $this->caso->id = $id;
        
        if ($this->caso->deletar()) {
            return $this->response(200, 'Caso deletado com sucesso');
        }

        return $this->response(500, 'Erro ao deletar caso');
    }

    // Estatísticas
    public function estatisticas($usuario_logado) {
        $stats = $this->caso->estatisticas(
            $usuario_logado['usuario_id'],
            $usuario_logado['perfil_nivel']
        );

        return $this->response(200, 'Estatísticas obtidas com sucesso', $stats);
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
