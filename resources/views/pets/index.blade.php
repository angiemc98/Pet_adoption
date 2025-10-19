@extends('layout')

@section('content')
<div class="container">
    <h1 class="mt-4 mb-3 text-center">🐶 Mascotas disponibles para adopción</h1>
    <div id="pets" class="row g-4 justify-content-center"></div>
</div>

<script>
async function loadPets() {
    const role = localStorage.getItem('role');
    try {
        const res = await fetch('/api/pets');
        if (!res.ok) throw new Error('Error al cargar mascotas');
        const data = await res.json();

        const container = document.getElementById('pets');
        if (!data.length) {
            container.innerHTML = `<p class="text-muted text-center">No hay mascotas registradas 🐾</p>`;
            return;
        }

        container.innerHTML = data.map(pet => `
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <img src="/storage/${pet.image ?? 'placeholder.png'}" class="card-img-top" style="height:200px; object-fit:cover;">
                    <div class="card-body">
                        <h5>${pet.name}</h5>
                        <p>${pet.species ?? ''} • ${pet.gender ?? ''}</p>
                        ${role === 'adopter' ? `<button class="btn btn-primary btn-sm" onclick="apply(${pet.id})">Adoptar</button>` : ''}
                    </div>
                </div>
            </div>
        `).join('');
    } catch (err) {
        console.error(err);
        alert('Hubo un error al cargar las mascotas');
    }
}

async function apply(petId) {
    const token = localStorage.getItem('token');
    if (!token) {
        alert('Debes iniciar sesión para adoptar 🐕');
        window.location.href = '/login';
        return;
    }

    try {
        const res = await fetch('/api/applications', {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + token,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                pet_id: petId,
                reason_for_adoption: 'I would love to care for this pet!',
                has_experience: true
            })
        });

        const data = await res.json();
        if (res.ok) {
            alert('Solicitud enviada con éxito 🎉');
        } else {
            alert('Error: ' + (data.message ?? 'No se pudo enviar la solicitud'));
        }
    } catch (error) {
        console.error('Error al adoptar:', error);
        alert('Error de conexión con el servidor');
    }
}

loadPets();
</script>
@endsection
