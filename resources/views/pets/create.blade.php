@extends('layout')

@section('content')
<div class="container">
    <h1 class="text-center mt-4 mb-4">➕ Registrar nueva mascota</h1>

    <form id="createPetForm" class="card p-4 shadow-sm col-md-6 mx-auto">
        <input type="text" id="name" class="form-control mb-3" placeholder="Nombre de la mascota" required>
        <input type="text" id="species" class="form-control mb-3" placeholder="Especie (ej. perro, gato)" required>
        <input type="text" id="breed" class="form-control mb-3" placeholder="Raza (opcional)">
        <select id="gender" class="form-select mb-3">
            <option value="">Selecciona género</option>
            <option value="male">Macho</option>
            <option value="female">Hembra</option>
        </select>
        <input type="file" id="photo_url" class="form-control mb-3">
        <input type="number" id="age" class="form-control mb-3" placeholder="Edad (años)" required>
        <select id="size" class="form-select mb-3">
        <option value="small">Pequeño</option>
        <option value="medium">Mediano</option>
        <option value="large">Grande</option>
        </select>

        <div class="form-check mb-2">
        <input type="checkbox" id="vaccinated" class="form-check-input">
        <label for="vaccinated" class="form-check-label">Vacunado</label>
        </div>

        <div class="form-check mb-3">
        <input type="checkbox" id="is_sterilized" class="form-check-input">
        <label for="is_sterilized" class="form-check-label">Esterilizado</label>
        </div>

        <button class="btn btn-success w-100">Guardar mascota</button>
    </form>
</div>

<script>
document.getElementById('createPetForm').addEventListener('submit', async e => {
    e.preventDefault();

    const token = localStorage.getItem('token');
    const role = localStorage.getItem('role');

    if (!token || role !== 'shelter') {
        alert('Solo los refugios pueden crear mascotas 🏠');
        return;
    }

    const formData = new FormData();
    formData.append('name', document.getElementById('name').value);
    formData.append('species', document.getElementById('species').value);
    formData.append('breed', document.getElementById('breed').value);
    formData.append('gender', document.getElementById('gender').value);
    formData.append('age', document.getElementById('age').value);
    formData.append('size', document.getElementById('size').value);
    formData.append('vaccinated', document.getElementById('vaccinated').checked ? '1' : '0');
    formData.append('is_sterilized', document.getElementById('is_sterilized').checked ? '1' : '0');


    const photo = document.getElementById('photo_url').files[0];
    if (photo) formData.append('photo_url', photo);

    console.log("📤 Enviando datos al backend:");
    for (let [k, v] of formData.entries()) console.log(k, v);

    try {
        const res = await fetch('/api/pets', {
            method: 'POST',
            headers: { 'Authorization': 'Bearer ' + token },
            body: formData
        });

        const data = await res.json();
        console.log('📥 Respuesta del servidor:', data);

        if (res.ok) {
            alert('✅ Mascota registrada correctamente');
            window.location.href = '/dashboard';
        } else {
            alert('❌ Error: ' + (data.message ?? 'Ver consola para más detalles'));
        }
    } catch (err) {
        console.error('🚨 Error de conexión:', err);
        alert('Error de conexión con el servidor');
    }
});
</script>
@endsection
