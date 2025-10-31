// Verificar autenticação
verificarAuth();

// Carregar informações do usuário
const usuario = getUsuario();

if (usuario) {
    document.getElementById('userName').textContent = usuario.nome;
    document.getElementById('userRole').textContent = usuario.perfil;
    document.getElementById('userAvatar').textContent = getIniciais(usuario.nome);

    // Mostrar menu de admin para Super Admin e Admin
    if (usuario.perfil_nivel <= 2) {
        document.getElementById('adminMenu').style.display = 'block';
    }
}

// Mapear status
function getStatusBadge(status) {
    const statusMap = {
        'aberto': { class: 'badge-info', text: 'Aberto' },
        'em_andamento': { class: 'badge-warning', text: 'Em Andamento' },
        'suspenso': { class: 'badge-danger', text: 'Suspenso' },
        'encerrado': { class: 'badge-success', text: 'Encerrado' },
        'arquivado': { class: 'badge-secondary', text: 'Arquivado' }
    };
    const s = statusMap[status] || { class: 'badge-info', text: status };
    return `<span class="badge ${s.class}">${s.text}</span>`;
}

// Mapear prioridade
function getPrioridadeBadge(prioridade) {
    const prioridadeMap = {
        'baixa': { class: 'badge-success', text: 'Baixa' },
        'media': { class: 'badge-info', text: 'Média' },
        'alta': { class: 'badge-warning', text: 'Alta' },
        'urgente': { class: 'badge-danger', text: 'Urgente' }
    };
    const p = prioridadeMap[prioridade] || { class: 'badge-info', text: prioridade };
    return `<span class="badge ${p.class}">${p.text}</span>`;
}

// Carregar estatísticas
async function carregarEstatisticas() {
    const result = await api.casos.estatisticas();
    
    if (result && result.status === 200) {
        const stats = result.data.data;
        document.getElementById('totalCasos').textContent = stats.total || 0;
        document.getElementById('casosAbertos').textContent = stats.abertos || 0;
        document.getElementById('casosAndamento').textContent = stats.em_andamento || 0;
        document.getElementById('casosUrgentes').textContent = stats.urgentes || 0;
    }
}

// Carregar casos recentes
async function carregarCasosRecentes() {
    const result = await api.casos.listar();
    
    const tbody = document.getElementById('casosRecentes');
    
    if (result && result.status === 200) {
        const casos = result.data.data;
        
        if (casos.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" style="text-align: center; padding: 40px;">
                        Nenhum caso encontrado
                    </td>
                </tr>
            `;
            return;
        }

        // Mostrar apenas os 5 mais recentes
        const casosRecentes = casos.slice(0, 5);
        
        tbody.innerHTML = casosRecentes.map(caso => `
            <tr onclick="window.location.href='caso-detalhes.html?id=${caso.id}'" style="cursor: pointer;">
                <td>${caso.numero_processo || '-'}</td>
                <td>${caso.titulo}</td>
                <td>${caso.cliente_nome || '-'}</td>
                <td>${getStatusBadge(caso.status)}</td>
                <td>${getPrioridadeBadge(caso.prioridade)}</td>
                <td>${formatarData(caso.data_abertura)}</td>
            </tr>
        `).join('');
    } else {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" style="text-align: center; padding: 40px; color: var(--danger);">
                    Erro ao carregar casos
                </td>
            </tr>
        `;
    }
}

// Carregar dados ao iniciar
carregarEstatisticas();
carregarCasosRecentes();
