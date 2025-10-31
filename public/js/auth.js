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
    notification.className = `notification ${type} show`;
    
    setTimeout(() => {
        notification.classList.remove('show');
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
    loginForm.style.display = 'none';
    primeiroAcessoForm.style.display = 'block';
});

// Voltar ao login
voltarLoginLink.addEventListener('click', (e) => {
    e.preventDefault();
    primeiroAcessoForm.style.display = 'none';
    loginForm.style.display = 'block';
    step1.style.display = 'block';
    step2.style.display = 'none';
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
            step1.style.display = 'none';
            step2.style.display = 'block';
            
            // Em desenvolvimento, mostrar o código
            if (result.data && result.data.codigo) {
                showNotification(`Código: ${result.data.codigo}`, 'info');
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
                primeiroAcessoForm.style.display = 'none';
                loginForm.style.display = 'block';
                step1.style.display = 'block';
                step2.style.display = 'none';
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
