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

    // Cliente não pode criar casos
    if (usuario.perfil_nivel === 5) {
        showNotification('Clientes não podem criar casos', 'error');
        setTimeout(() => {
            window.location.href = 'dashboard.html';
        }, 2000);
    }
}

// Definir data atual
document.getElementById('data_abertura').valueAsDate = new Date();

// Carregar clientes
async function carregarClientes() {
    const result = await api.usuarios.listar(5); // Perfil Cliente = 5
    
    const select = document.getElementById('cliente_id');
    
    if (result && result.status === 200) {
        const clientes = result.data.data;
        
        clientes.forEach(cliente => {
            const option = document.createElement('option');
            option.value = cliente.id;
            option.textContent = `${cliente.nome} - ${cliente.email}`;
            select.appendChild(option);
        });
    }
}

// Carregar advogados
async function carregarAdvogados() {
    const result = await api.usuarios.listar(4); // Perfil Advogado = 4
    
    const select = document.getElementById('advogado_responsavel_id');
    
    if (result && result.status === 200) {
        const advogados = result.data.data;
        
        advogados.forEach(advogado => {
            const option = document.createElement('option');
            option.value = advogado.id;
            option.textContent = advogado.nome;
            select.appendChild(option);
        });
    }
}

// Formatar textarea
document.querySelectorAll('textarea').forEach(textarea => {
    textarea.style.fontFamily = 'inherit';
    textarea.style.fontSize = '14px';
    textarea.style.padding = '10px 14px';
    textarea.style.border = '1px solid var(--gray-300)';
    textarea.style.borderRadius = '6px';
    textarea.style.resize = 'vertical';
});

// Formatar select
document.querySelectorAll('select').forEach(select => {
    select.style.width = '100%';
    select.style.padding = '10px 14px';
    select.style.fontSize = '14px';
    select.style.border = '1px solid var(--gray-300)';
    select.style.borderRadius = '6px';
    select.style.backgroundColor = 'white';
});

// Criar caso
document.getElementById('novoCasoForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const data = {};
    
    formData.forEach((value, key) => {
        if (value) {
            data[key] = value;
        }
    });

    // Validações
    if (!data.titulo) {
        showNotification('O título é obrigatório', 'error');
        return;
    }

    if (!data.cliente_id) {
        showNotification('Selecione um cliente', 'error');
        return;
    }

    if (!data.data_abertura) {
        showNotification('A data de abertura é obrigatória', 'error');
        return;
    }

    // Criar caso
    const result = await api.casos.criar(data);
    
    if (result && result.status === 201) {
        showNotification('Caso criado com sucesso!', 'success');
        
        setTimeout(() => {
            window.location.href = 'casos.html';
        }, 1500);
    } else {
        const message = result?.data?.message || 'Erro ao criar caso';
        showNotification(message, 'error');
    }
});

// Carregar dados iniciais
carregarClientes();
carregarAdvogados();
