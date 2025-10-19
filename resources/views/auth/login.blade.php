@extends('layout')

@section('content')
<div class="container">
    <h2 class="mb-3">Iniciar sesión</h2>
    <form id="loginForm">
        <input type="email" class="form-control mb-2" placeholder="Email" id="email" required>
        <input type="password" class="form-control mb-2" placeholder="Contraseña" id="password" required>
        <button class="btn btn-success w-100">Entrar</button>
    </form>
</div>

<script>
document.getElementById('loginForm').addEventListener('submit', async e => {
    e.preventDefault();
    try {
        const res = await fetch('/api/login', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({
                email: document.getElementById('email').value,
                password: document.getElementById('password').value
            })
        });
        const data = await res.json();

        if (res.ok) {
            // ✅ Guarda token y rol
            localStorage.setItem('token', data.token);
            localStorage.setItem('role', data.user.role);

            alert('Login correcto ✅');

            // 🚀 Redirección según rol
            if (data.user.role === 'shelter') {
                window.location.href = '/dashboard';
            } else {
                window.location.href = '/';
            }

        } else {
            alert(data.message || 'Credenciales inválidas');
        }
    } catch (err) {
        alert('Error de conexión con el servidor');
    }
});
</script>

@endsection
