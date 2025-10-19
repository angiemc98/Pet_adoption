@extends('layout')

@section('content')
<div class="container">
    <h1 class="mt-4 mb-3 text-center">📋 Mis Mascotas Registradas</h1>
    <div id="pets" class="row g-4 justify-content-center"></div>
</div>

<script>
async function loadMyPets() {
    const token = localStorage.getItem('token');
    const role = localStorage.getItem('role');

    if (!token || role !== 'shelter') {
        alert('Acceso restringido. Solo los refugios pueden ver esta página.');
        window.location.href = '/';
        return;
    }

    try {
        const res = await fetch('/api/pets', {
            headers: { 'Authorization': 'Bearer ' + token }
        });
        const data = await res.json();

        const container = document.getElementById('pets');

        if (!Array.isArray(data) || !data.length) {
            container.innerHTML = `<p class="text-muted text-center">Aún no tienes mascotas registradas 🐾</p>`;
            return;
        }

        // Filtrar solo las del shelter autenticado (si tu API no lo hace)
        const myPets = data.filter(pet => pet.shelter_id === data[0]?.shelter_id);

        container.innerHTML = myPets.map(pet => `
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <img src="/storage/${pet.image ?? 'placeholder.png'}" class="card-img-top" style="height:200px; object-fit:cover;">
                    <div class="card-body">
                        <h5>${pet.name}</h5>
                        <p>${pet.species ?? ''} • ${pet.gender ?? ''}</p>
                        <button class="btn btn-warning btn-sm" onclick="editPet(${pet.id})">Editar</button>
                        <button class="btn btn-danger btn-sm" onclick="deletePet(${pet.id})">Eliminar</button>
                    </div>
                </div>
            </div>
        `).join('');
    } catch (err) {
        console.error(err);
        alert('Error al cargar tus mascotas');
    }
}

async function deletePet(id) {
    if (!confirm('¿Seguro que deseas eliminar esta mascota?')) return;

    const token = localStorage.getItem('token');
    try {
        const res = await fetch(`/api/pets/${id}`, {
            method: 'DELETE',
            headers: { 'Authorization': 'Bearer ' + token }
        });
        if (res.ok) {
            alert('Mascota eliminada ✅');
            loadMyPets();
        } else {
            alert('No se pudo eliminar la mascota.');
        }
    } catch (err) {
        alert('Error al eliminar.');
    }
}

function editPet(id) {
    window.location.href = `/pets/edit?id=${id}`;
}

loadMyPets();
</script>
@endsection
