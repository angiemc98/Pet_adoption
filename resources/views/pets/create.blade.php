@extends('layout')

@section('content')
<div class="container">
    <h1 class="text-center mt-4 mb-4">➕ Registrar nueva mascota</h1>

    <form id="createPetForm" class="card p-4 shadow-sm col-md-6 mx-auto">
        <input type="text" id="name" class="form-control mb-3" placeholder="Nombre de la mascota" required>
        <input type="text" id="species" class="form-control mb-3" placeholder="Especie (ej. perro, gato)" required>
        <input type="text" id="breed" class="form-control mb-3" placeholder="Raza (opcional)">
        <select id="gender" class="form-select mb-3">
        <option value="Macho">Macho</option>
        <option value="Hembra">Hembra</option>
        </select>
        <input type="file" id="image" class="form-control mb-3">
        <button class="btn btn-success w-100">Guardar mascota</button>
    </form>
</div>

<script>
document.getElementById('createPetForm').addEventListener('submit', async e => {
    e.preventDefault();
    const token = localStorage.getItem('token');
    const role = localStorage.getItem('role');

    if (!token || role !== 'shelter') {
        alert('Solo los refugios pueden crear mascotas');
        return;
    }

    const formData = new FormData();
    formData.append('name', document.getElementById('name').value);
    formData.append('species', document.getElementById('species').value);
    formData.append('breed', document.getElementById('breed').value);
    formData.append('gender', document.getElementById('gender').value);
    const image = document.getElementById('image').files[0];
    if (image) formData.append('image', image);

    try {
        const res = await fetch('/api/pets', {
        method: 'POST',
        headers: { 'Authorization': 'Bearer ' + token },
        body: formData
        });
        const data = await res.json();

        if (res.ok) {
        alert('Mascota registrada correctamente 🐾');
        window.location.href = '/dashboard';
        } else {
        alert('Error: ' + (data.message ?? 'No se pudo registrar'));
        }
    } catch (err) {
        alert('Error de conexión');
    }
});
</script>
@endsection
