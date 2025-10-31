// Configuração da API
const API_URL = window.location.hostname === 'localhost' 
    ? 'http://localhost:8000/api' 
    : 'https://adv.sgqoti.com.br/api';

// Obter token
function getToken() {
    return localStorage.getItem('token');
}

// Obter usuário logado
function getUsuario() {
    const usuario = localStorage.getItem('usuario');
    return usuario ? JSON.parse(usuario) : null;
}

// Verificar autenticação
function verificarAuth() {
    const token = getToken();
    if (!token) {
        window.location.href = '../index.html';
        return false;
    }
    return true;
}

// Fazer logout
function logout() {
    localStorage.removeItem('token');
    localStorage.removeItem('usuario');
    window.location.href = '../index.html';
}

// API Request genérica
async function apiRequest(endpoint, method = 'GET', data = null) {
    const token = getToken();
    
    const options = {
        method,
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`
        }
    };

    if (data && method !== 'GET') {
        options.body = JSON.stringify(data);
    }

    try {
        const response = await fetch(`${API_URL}${endpoint}`, options);
        const result = await response.json();

        if (response.status === 401) {
            logout();
            return null;
        }

        return { status: response.status, data: result };
    } catch (error) {
        console.error('Erro na requisição:', error);
        return { status: 500, data: { message: 'Erro ao conectar com o servidor' } };
    }
}

// APIs específicas
const api = {
    // Usuários
    usuarios: {
        listar: (perfil_id = null) => {
            const query = perfil_id ? `?perfil_id=${perfil_id}` : '';
            return apiRequest(`/usuarios${query}`);
        },
        buscar: (id) => apiRequest(`/usuarios/${id}`),
        criar: (data) => apiRequest('/usuarios', 'POST', data),
        atualizar: (id, data) => apiRequest(`/usuarios/${id}`, 'PUT', data),
        deletar: (id) => apiRequest(`/usuarios/${id}`, 'DELETE')
    },

    // Casos
    casos: {
        listar: () => apiRequest('/casos'),
        buscar: (id) => apiRequest(`/casos/${id}`),
        criar: (data) => apiRequest('/casos', 'POST', data),
        atualizar: (id, data) => apiRequest(`/casos/${id}`, 'PUT', data),
        deletar: (id) => apiRequest(`/casos/${id}`, 'DELETE'),
        estatisticas: () => apiRequest('/casos/estatisticas')
    },

    // Perfis
    perfis: {
        listar: () => apiRequest('/perfis')
    }
};

// Mostrar notificação
function showNotification(message, type = 'success') {
    const notification = document.getElementById('notification');
    if (notification) {
        notification.textContent = message;
        notification.className = `notification ${type} show`;
        
        setTimeout(() => {
            notification.classList.remove('show');
        }, 5000);
    }
}

// Formatar data
function formatarData(data) {
    if (!data) return '-';
    const d = new Date(data);
    return d.toLocaleDateString('pt-BR');
}

// Formatar moeda
function formatarMoeda(valor) {
    if (!valor) return '-';
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    }).format(valor);
}

// Obter iniciais do nome
function getIniciais(nome) {
    if (!nome) return '?';
    const partes = nome.trim().split(' ');
    if (partes.length === 1) return partes[0].charAt(0).toUpperCase();
    return (partes[0].charAt(0) + partes[partes.length - 1].charAt(0)).toUpperCase();
}
