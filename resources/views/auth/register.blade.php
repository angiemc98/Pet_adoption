@extends('layout')

@section('content')
<div class="col-md-6 mx-auto">
    <h2 class="mb-4 text-center">Crear cuenta</h2>
    <form id="registerForm" class="card card-body shadow">
        <input type="text" id="name" class="form-control mb-2" placeholder="Nombre completo" required>
        <input type="email" id="email" class="form-control mb-2" placeholder="Correo electrónico" required>
        <input type="password" id="password" class="form-control mb-2" placeholder="Contraseña" required>
        <select id="role" class="form-select mb-2">
        <option value="adopter">Adoptante</option>
        <option value="shelter">Refugio</option>
        </select>
        <input type="text" id="phone" class="form-control mb-2" placeholder="Teléfono">
        <input type="text" id="city" class="form-control mb-2" placeholder="Ciudad">
        <button class="btn btn-success w-100">Registrarme</button>
    </form>
</div>

<script>
document.getElementById('registerForm').addEventListener('submit', async e => {
    e.preventDefault();

    const res = await fetch('/api/register', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
        name: document.getElementById('name').value,
        email: document.getElementById('email').value,
        password: document.getElementById('password').value,
        role: document.getElementById('role').value,
        phone: document.getElementById('phone').value,
        city: document.getElementById('city').value
        })
    });

    const data = await res.json();
    if (res.ok) {
        alert('Usuario registrado correctamente ✅');
        localStorage.setItem('token', data.token);
        window.location.href = '/';
    } else {
        alert('Error: ' + (data.message ?? 'No se pudo registrar'));
    }
});
</script>
@endsection
