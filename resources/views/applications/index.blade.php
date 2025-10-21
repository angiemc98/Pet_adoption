@extends('layout')

@section('content')
<div class="container">
    <h1 class="text-center mt-4 mb-4">📨 Mis solicitudes de adopción</h1>
    <div id="applications" class="row justify-content-center"></div>
</div>

<script>
async function loadApplications() {
    const token = localStorage.getItem('token');
    const role = localStorage.getItem('role');

    if (!token || role !== 'adopter') {
        alert('Acceso restringido. Solo los adoptantes pueden ver sus solicitudes.');
        window.location.href = '/';
        return;
    }

    try {
        const res = await fetch('/api/applications', {  // Ruta corregida
            headers: { 'Authorization': 'Bearer ' + token }
        });

        if (!res.ok) {
            throw new Error(`Error HTTP: ${res.status}`);
        }

        const data = await res.json();
        console.log('Respuesta de la API:', data);  // Para depurar

        const container = document.getElementById('applications');

        if (!Array.isArray(data) || !data.length) {
            container.innerHTML = `<p class="text-muted text-center">Aún no has enviado solicitudes 🐾</p>`;
            return;
        }

        container.innerHTML = data.map(app => `
            <div class="card mb-3 col-md-6 shadow-sm">
                <div class="card-body">
                    <h5>${app.pet?.name ?? 'Mascota desconocida'}</h5>
                    <p><strong>Estado:</strong>
                        <span class="badge ${
                            app.status === 'approved' ? 'bg-success' :
                            app.status === 'rejected' ? 'bg-danger' : 'bg-warning text-dark'
                        }">${app.status}</span>
                    </p>
                    <p><strong>Motivo:</strong> ${app.message ?? 'N/A'}</p>  <!-- Campo corregido -->
                </div>
            </div>
        `).join('');
    } catch (err) {
        console.error('Error al cargar solicitudes:', err);
        alert('No se pudieron cargar las solicitudes: ' + err.message);
    }
}

loadApplications();
</script>
@endsection
