// Verificar autenticação
verificarAuth();

// Carregar informações do usuário
const usuario = getUsuario();

if (usuario) {
    document.getElementById('userName').textContent = usuario.nome;
    document.getElementById('userRole').textContent = usuario.perfil;
    document.getElementById('userAvatar').textContent = getIniciais(usuario.nome);

    // Apenas Super Admin e Admin podem acessar
    if (usuario.perfil_nivel > 2) {
        showNotification('Você não tem permissão para acessar esta página', 'error');
        setTimeout(() => {
            window.location.href = 'dashboard.html';
        }, 2000);
    }
}

let todosOsUsuarios = [];
let perfis = [];

// Mapear status
function getStatusBadge(status) {
    const statusMap = {
        'ativo': { class: 'badge-success', text: 'Ativo' },
        'inativo': { class: 'badge-danger', text: 'Inativo' },
        'pendente': { class: 'badge-warning', text: 'Pendente' }
    };
    const s = statusMap[status] || { class: 'badge-info', text: status };
    return `<span class="badge ${s.class}">${s.text}</span>`;
}

// Carregar perfis
async function carregarPerfis() {
    const result = await api.perfis.listar();
    
    if (result && result.status === 200) {
        perfis = result.data.data;
        
        // Preencher select do filtro
        const selectFiltro = document.getElementById('filtroPerfil');
        perfis.forEach(perfil => {
            const option = document.createElement('option');
            option.value = perfil.id;
            option.textContent = perfil.nome;
            selectFiltro.appendChild(option);
        });

        // Preencher select do modal
        const selectModal = document.getElementById('perfil_id');
        perfis.forEach(perfil => {
            const option = document.createElement('option');
            option.value = perfil.id;
            option.textContent = perfil.nome;
            selectModal.appendChild(option);
        });
    }
}

// Carregar usuários
async function carregarUsuarios(perfil_id = null) {
    const result = await api.usuarios.listar(perfil_id);
    
    const tbody = document.getElementById('usuariosList');
    
    if (result && result.status === 200) {
        todosOsUsuarios = result.data.data;
        renderizarUsuarios(todosOsUsuarios);
    } else {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" style="text-align: center; padding: 40px; color: var(--danger);">
                    Erro ao carregar usuários
                </td>
            </tr>
        `;
    }
}

// Renderizar usuários
function renderizarUsuarios(usuarios) {
    const tbody = document.getElementById('usuariosList');
    
    if (usuarios.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" style="text-align: center; padding: 40px;">
                    Nenhum usuário encontrado
                </td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = usuarios.map(user => `
        <tr>
            <td><strong>${user.nome}</strong></td>
            <td>${user.email}</td>
            <td>${user.perfil_nome}</td>
            <td>${user.telefone || '-'}</td>
            <td>${getStatusBadge(user.status)}</td>
            <td>
                <button onclick="editarUsuario(${user.id})" class="btn btn-secondary" style="width: auto; padding: 6px 12px; font-size: 12px; margin-right: 5px;">Editar</button>
                ${usuario.perfil_nivel === 1 ? `<button onclick="deletarUsuario(${user.id}, '${user.nome}')" class="btn btn-danger" style="width: auto; padding: 6px 12px; font-size: 12px;">Excluir</button>` : ''}
            </td>
        </tr>
    `).join('');
}

// Filtrar usuários
function filtrarUsuarios() {
    const filtroPerfil = document.getElementById('filtroPerfil').value;
    const filtroBusca = document.getElementById('filtroBusca').value.toLowerCase();

    let usuariosFiltrados = todosOsUsuarios;

    if (filtroPerfil) {
        usuariosFiltrados = usuariosFiltrados.filter(user => user.perfil_id == filtroPerfil);
    }

    if (filtroBusca) {
        usuariosFiltrados = usuariosFiltrados.filter(user => {
            const nome = (user.nome || '').toLowerCase();
            const email = (user.email || '').toLowerCase();
            
            return nome.includes(filtroBusca) || email.includes(filtroBusca);
        });
    }

    renderizarUsuarios(usuariosFiltrados);
}

// Abrir modal
function abrirModalNovoUsuario() {
    document.getElementById('modalNovoUsuario').classList.add('show');
}

// Fechar modal
function fecharModal() {
    document.getElementById('modalNovoUsuario').classList.remove('show');
    document.getElementById('formNovoUsuario').reset();
}

// Criar usuário
document.getElementById('formNovoUsuario').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const data = {};
    
    formData.forEach((value, key) => {
        if (value) {
            data[key] = value;
        }
    });

    const result = await api.usuarios.criar(data);
    
    if (result && result.status === 201) {
        showNotification('Usuário criado com sucesso!', 'success');
        fecharModal();
        carregarUsuarios();
        
        if (result.data.data && result.data.data.codigo) {
            showNotification(`Código de acesso: ${result.data.data.codigo}`, 'info');
        }
    } else {
        const message = result?.data?.message || 'Erro ao criar usuário';
        showNotification(message, 'error');
    }
});

// Editar usuário
function editarUsuario(id) {
    showNotification('Funcionalidade de edição em desenvolvimento', 'info');
}

// Deletar usuário
async function deletarUsuario(id, nome) {
    if (!confirm(`Deseja realmente excluir o usuário "${nome}"?`)) {
        return;
    }

    const result = await api.usuarios.deletar(id);
    
    if (result && result.status === 200) {
        showNotification('Usuário excluído com sucesso!', 'success');
        carregarUsuarios();
    } else {
        const message = result?.data?.message || 'Erro ao excluir usuário';
        showNotification(message, 'error');
    }
}

// Event listeners
document.getElementById('filtroPerfil').addEventListener('change', filtrarUsuarios);
document.getElementById('filtroBusca').addEventListener('input', filtrarUsuarios);

// Carregar dados iniciais
carregarPerfis();
carregarUsuarios();
