// Verificar autenticação
verificarAuth();

// Carregar informações do usuário
const usuario = getUsuario();

if (usuario) {
    document.getElementById('userName').textContent = usuario.nome;
    document.getElementById('userRole').textContent = usuario.perfil;
    document.getElementById('userAvatar').textContent = getIniciais(usuario.nome);

    // Mostrar menu de admin
    if (usuario.perfil_nivel <= 2) {
        document.getElementById('adminMenu').style.display = 'block';
    }

    // Ocultar botão de novo caso para clientes
    if (usuario.perfil_nivel === 5) {
        const btnNovo = document.getElementById('btnNovoCaso');
        if (btnNovo) btnNovo.style.display = 'none';
    }
}

let todosOsCasos = [];

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

// Carregar casos
async function carregarCasos() {
    const result = await api.casos.listar();
    
    const tbody = document.getElementById('casosList');
    
    if (result && result.status === 200) {
        todosOsCasos = result.data.data;
        renderizarCasos(todosOsCasos);
    } else {
        tbody.innerHTML = `
            <tr>
                <td colspan="8" style="text-align: center; padding: 40px; color: var(--danger);">
                    Erro ao carregar casos
                </td>
            </tr>
        `;
    }
}

// Renderizar casos
function renderizarCasos(casos) {
    const tbody = document.getElementById('casosList');
    
    if (casos.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="8" style="text-align: center; padding: 40px;">
                    Nenhum caso encontrado
                </td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = casos.map(caso => `
        <tr onclick="visualizarCaso(${caso.id})" style="cursor: pointer;">
            <td>${caso.numero_processo || '-'}</td>
            <td><strong>${caso.titulo}</strong></td>
            <td>${caso.cliente_nome || '-'}</td>
            <td>${caso.advogado_nome || '-'}</td>
            <td>${getStatusBadge(caso.status)}</td>
            <td>${getPrioridadeBadge(caso.prioridade)}</td>
            <td>${formatarData(caso.data_abertura)}</td>
            <td>${formatarMoeda(caso.valor_causa)}</td>
        </tr>
    `).join('');
}

// Visualizar caso
function visualizarCaso(id) {
    showNotification('Página de detalhes em desenvolvimento', 'info');
    // window.location.href = `caso-detalhes.html?id=${id}`;
}

// Filtrar casos
function filtrarCasos() {
    const filtroStatus = document.getElementById('filtroStatus').value.toLowerCase();
    const filtroPrioridade = document.getElementById('filtroPrioridade').value.toLowerCase();
    const filtroBusca = document.getElementById('filtroBusca').value.toLowerCase();

    let casosFiltrados = todosOsCasos;

    if (filtroStatus) {
        casosFiltrados = casosFiltrados.filter(caso => caso.status === filtroStatus);
    }

    if (filtroPrioridade) {
        casosFiltrados = casosFiltrados.filter(caso => caso.prioridade === filtroPrioridade);
    }

    if (filtroBusca) {
        casosFiltrados = casosFiltrados.filter(caso => {
            const titulo = (caso.titulo || '').toLowerCase();
            const numero = (caso.numero_processo || '').toLowerCase();
            const cliente = (caso.cliente_nome || '').toLowerCase();
            
            return titulo.includes(filtroBusca) || 
                   numero.includes(filtroBusca) || 
                   cliente.includes(filtroBusca);
        });
    }

    renderizarCasos(casosFiltrados);
}

// Event listeners para filtros
document.getElementById('filtroStatus').addEventListener('change', filtrarCasos);
document.getElementById('filtroPrioridade').addEventListener('change', filtrarCasos);
document.getElementById('filtroBusca').addEventListener('input', filtrarCasos);

// Carregar dados ao iniciar
carregarCasos();
