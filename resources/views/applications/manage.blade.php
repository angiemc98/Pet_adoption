@extends('layout')

@section('content')
<div class="container">
    <h1 class="mt-4 mb-3 text-center">📨 Solicitudes de adopción recibidas</h1>
    <div id="applications" class="row g-4 justify-content-center"></div>
</div>

<script>
async function loadApplications() {
    const token = localStorage.getItem('token');
    const role = localStorage.getItem('role');

    if (!token || role !== 'shelter') {
        alert('Acceso restringido. Solo los refugios pueden ver esta página.');
        window.location.href = '/';
        return;
    }

    try {
        const res = await fetch('/api/applications', {
            headers: { 'Authorization': 'Bearer ' + token }
        });
        if (!res.ok) throw new Error('Error al cargar solicitudes');
        const data = await res.json();

        const container = document.getElementById('applications');
        if (!data.length) {
            container.innerHTML = `<p class="text-muted text-center">Aún no has recibido solicitudes 🐾</p>`;
            return;
        }

        container.innerHTML = data.map(app => `
            <div class="col-md-6">
                <div class="card shadow-sm p-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="mb-0">${app.pet?.name ?? 'Mascota desconocida'}</h5>
                        <span class="badge bg-${getStatusColor(app.status)}">${getStatusText(app.status)}</span>
                    </div>

                    <hr>

                    <p class="mb-1"><strong>👤 Adoptante:</strong> ${app.adopter?.name ?? 'Desconocido'}</p>
                    <p class="mb-1"><strong>📧 Email:</strong> ${app.adopter?.email ?? 'N/A'}</p>
                    <p class="mb-1"><strong>📝 Motivo:</strong> ${app.reason_for_adoption ?? 'Sin motivo'}</p>
                    <p class="mb-1"><strong>🐕 Experiencia previa:</strong> ${app.has_experience ? 'Sí' : 'No'}</p>
                    <p class="text-muted small mb-3"><strong>📅 Fecha:</strong> ${new Date(app.created_at).toLocaleDateString('es-ES')}</p>

                    ${app.status === 'pending' ? `
                        <div class="d-flex gap-2">
                            <button class="btn btn-success btn-sm flex-fill" onclick="updateStatus(${app.id}, 'approved')">
                                ✅ Aprobar
                            </button>
                            <button class="btn btn-danger btn-sm flex-fill" onclick="updateStatus(${app.id}, 'rejected')">
                                ❌ Rechazar
                            </button>
                        </div>
                    ` : ''}
                </div>
            </div>
        `).join('');
    } catch (err) {
        console.error(err);
        alert('Error al cargar las solicitudes');
    }
}

async function updateStatus(applicationId, status) {
    const token = localStorage.getItem('token');
    const confirmMsg = status === 'approved'
        ? '¿Seguro que deseas aprobar esta solicitud?'
        : '¿Seguro que deseas rechazar esta solicitud?';

    if (!confirm(confirmMsg)) return;

    try {
        const res = await fetch(`/api/applications/${applicationId}`, {
            method: 'PATCH',
            headers: {
                'Authorization': 'Bearer ' + token,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ status })
        });

        if (res.ok) {
            alert(status === 'approved' ? 'Solicitud aprobada ✅' : 'Solicitud rechazada ❌');
            loadApplications(); // Recargar lista
        } else {
            alert('Error al actualizar el estado');
        }
    } catch (err) {
        console.error(err);
        alert('Error al procesar la solicitud');
    }
}

function getStatusColor(status) {
    switch (status) {
        case 'approved': return 'success';
        case 'rejected': return 'danger';
        case 'pending': return 'warning';
        default: return 'secondary';
    }
}

function getStatusText(status) {
    switch (status) {
        case 'approved': return 'Aprobada';
        case 'rejected': return 'Rechazada';
        case 'pending': return 'Pendiente';
        default: return status;
    }
}

loadApplications();
</script>
@endsection
