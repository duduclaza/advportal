// Configuração da API
const API_URL = window.location.hostname === 'localhost' 
    ? 'http://localhost:8000/api' 
    : 'https://adv.sgqoti.com.br/api';

// Elementos
const loginForm = document.getElementById('loginForm');
const primeiroAcessoForm = document.getElementById('primeiroAcessoForm');
const primeiroAcessoLink = document.getElementById('primeiroAcessoLink');
const voltarLoginLink = document.getElementById('voltarLoginLink');
const solicitarCodigoBtn = document.getElementById('solicitarCodigoBtn');
const step1 = document.getElementById('step1');
const step2 = document.getElementById('step2');

// Mostrar notificação
function showNotification(message, type = 'success') {
    const notification = document.getElementById('notification');
    notification.textContent = message;
    
    // Cores baseadas no tipo
    const colors = {
        'success': 'bg-green-500',
        'error': 'bg-red-500',
        'warning': 'bg-yellow-500',
        'info': 'bg-blue-500'
    };
    
    notification.className = `fixed top-4 right-4 px-6 py-4 rounded-lg shadow-lg font-medium text-white transition-all duration-300 z-50 ${colors[type] || colors.success}`;
    
    // Mostrar
    setTimeout(() => {
        notification.classList.remove('opacity-0', '-translate-y-4');
        notification.classList.add('opacity-100', 'translate-y-0');
    }, 10);
    
    // Ocultar após 5 segundos
    setTimeout(() => {
        notification.classList.remove('opacity-100', 'translate-y-0');
        notification.classList.add('opacity-0', '-translate-y-4');
    }, 5000);
}

// Login
loginForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const email = document.getElementById('email').value;
    const senha = document.getElementById('senha').value;

    try {
        const response = await fetch(`${API_URL}/auth/login`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ email, senha })
        });

        const result = await response.json();

        if (response.ok) {
            // Salvar token
            localStorage.setItem('token', result.data.token);
            localStorage.setItem('usuario', JSON.stringify(result.data.usuario));
            
            showNotification('Login realizado com sucesso!', 'success');
            
            // Redirecionar para dashboard
            setTimeout(() => {
                window.location.href = 'pages/dashboard.html';
            }, 1000);
        } else {
            showNotification(result.message, 'error');
        }
    } catch (error) {
        showNotification('Erro ao conectar com o servidor', 'error');
        console.error(error);
    }
});

// Alternar para primeiro acesso
primeiroAcessoLink.addEventListener('click', (e) => {
    e.preventDefault();
    loginForm.classList.add('hidden');
    primeiroAcessoForm.classList.remove('hidden');
});

// Voltar ao login
voltarLoginLink.addEventListener('click', (e) => {
    e.preventDefault();
    primeiroAcessoForm.classList.add('hidden');
    loginForm.classList.remove('hidden');
    step1.classList.remove('hidden');
    step2.classList.add('hidden');
});

// Solicitar código
solicitarCodigoBtn.addEventListener('click', async () => {
    const email = document.getElementById('emailCodigo').value;

    if (!email) {
        showNotification('Por favor, informe o email', 'warning');
        return;
    }

    try {
        const response = await fetch(`${API_URL}/auth/solicitar-codigo`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ email })
        });

        const result = await response.json();

        if (response.ok) {
            showNotification(result.message, 'success');
            step1.classList.add('hidden');
            step2.classList.remove('hidden');
            
            // Em desenvolvimento, mostrar o código
            if (result.data && result.data.codigo) {
                setTimeout(() => {
                    showNotification(`Código: ${result.data.codigo}`, 'info');
                }, 100);
            }
        } else {
            showNotification(result.message, 'error');
        }
    } catch (error) {
        showNotification('Erro ao conectar com o servidor', 'error');
        console.error(error);
    }
});

// Confirmar código e cadastrar senha
primeiroAcessoForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const email = document.getElementById('emailCodigo').value;
    const codigo = document.getElementById('codigo').value;
    const novaSenha = document.getElementById('novaSenha').value;
    const confirmarSenha = document.getElementById('confirmarSenha').value;

    if (novaSenha !== confirmarSenha) {
        showNotification('As senhas não coincidem', 'error');
        return;
    }

    if (novaSenha.length < 6) {
        showNotification('A senha deve ter no mínimo 6 caracteres', 'error');
        return;
    }

    try {
        const response = await fetch(`${API_URL}/auth/confirmar-codigo`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ email, codigo, nova_senha: novaSenha })
        });

        const result = await response.json();

        if (response.ok) {
            showNotification(result.message, 'success');
            
            // Voltar ao login após 2 segundos
            setTimeout(() => {
                primeiroAcessoForm.classList.add('hidden');
                loginForm.classList.remove('hidden');
                step1.classList.remove('hidden');
                step2.classList.add('hidden');
                primeiroAcessoForm.reset();
            }, 2000);
        } else {
            showNotification(result.message, 'error');
        }
    } catch (error) {
        showNotification('Erro ao conectar com o servidor', 'error');
        console.error(error);
    }
});
