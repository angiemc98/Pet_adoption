<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Adoption</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/">🐾 Pet Adoption</a>
            <div id="navLinks">
                <a href="/login" class="btn btn-light btn-sm me-2">Login</a>
                <a href="/register" class="btn btn-light btn-sm me-2">Registro</a>
            </div>
        </div>
    </nav>

    <main class="container py-4">
        @yield('content')
    </main>

    <script>
    document.addEventListener("DOMContentLoaded", () => {
        const token = localStorage.getItem('token');
        const role = localStorage.getItem('role');
        const navLinks = document.getElementById('navLinks');

        if (token) {
            let menu = '';
            if (role === 'shelter') {
                menu += `<a href="/dashboard" class="btn btn-warning btn-sm me-2">Mis Mascotas</a>`;
                menu += `<a href="/pets/create" class="btn btn-success btn-sm me-2">Añadir Mascota</a>`;
                menu += `<a href="/applications/manage" class="btn btn-info btn-sm me-2">Solicitudes Recibidas</a>`;
            } else if (role === 'adopter') {
                menu += `<a href="/applications" class="btn btn-outline-light btn-sm me-2">Mis Solicitudes</a>`;
            }
            menu += `<button id="logoutBtn" class="btn btn-danger btn-sm">Salir</button>`;
            navLinks.innerHTML = menu;

            // Logout handler
            document.getElementById('logoutBtn').addEventListener('click', async () => {
                try {
                    await fetch('/api/logout', {
                        method: 'POST',
                        headers: { 'Authorization': 'Bearer ' + token }
                    });
                } catch {}
                localStorage.removeItem('token');
                localStorage.removeItem('role');
                alert('Sesión cerrada correctamente 👋');
                window.location.href = '/';
            });
        }
    });
    </script>

</body>
</html>
