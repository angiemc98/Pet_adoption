@extends('layout')

@section('content')
<div class="container">
  <h1 class="text-center mt-4 mb-4">✏️ Editar mascota</h1>
  <form id="editPetForm" class="card p-4 shadow-sm col-md-6 mx-auto">
    <input type="text" id="name" class="form-control mb-3" placeholder="Nombre de la mascota" required>
    <input type="text" id="species" class="form-control mb-3" placeholder="Especie" required>
    <input type="text" id="breed" class="form-control mb-3" placeholder="Raza">
    <select id="gender" class="form-select mb-3">
      <option value="Macho">Macho</option>
      <option value="Hembra">Hembra</option>
    </select>
    <input type="file" id="image" class="form-control mb-3">
    <button class="btn btn-primary w-100">Actualizar</button>
  </form>
</div>

<script>
const urlParams = new URLSearchParams(window.location.search);
const petId = urlParams.get('id');

async function loadPet() {
    const token = localStorage.getItem('token');
    const res = await fetch(`/api/pets/${petId}`, {
        headers: { 'Authorization': 'Bearer ' + token }
    });
    const pet = await res.json();
    document.getElementById('name').value = pet.name;
    document.getElementById('species').value = pet.species;
    document.getElementById('breed').value = pet.breed;
    document.getElementById('gender').value = pet.gender;
}

document.getElementById('editPetForm').addEventListener('submit', async e => {
    e.preventDefault();
    const token = localStorage.getItem('token');
    const formData = new FormData();
    formData.append('name', document.getElementById('name').value);
    formData.append('species', document.getElementById('species').value);
    formData.append('breed', document.getElementById('breed').value);
    formData.append('gender', document.getElementById('gender').value);
    const image = document.getElementById('image').files[0];
    if (image) formData.append('image', image);

    const res = await fetch(`/api/pets/${petId}?_method=PUT`, {
        method: 'POST',
        headers: { 'Authorization': 'Bearer ' + token },
        body: formData
    });
    if (res.ok) {
        alert('Mascota actualizada ✅');
        window.location.href = '/dashboard';
    } else {
        alert('Error al actualizar');
    }
});

loadPet();
</script>
@endsection
