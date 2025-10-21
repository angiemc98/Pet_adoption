<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PetController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return Pet::where('status', 'available')->get();
        }

        if ($user->role === 'shelter') {
            return Pet::where('shelter_id', $user->id)->get();
        }

        if ($user->role === 'adopter') {
            return Pet::where('status', 'available')->get();
        }

        return Pet::all();
    }

    public function store(Request $r)
    {
        if (auth()->user()->role !== 'shelter') {
            return response()->json(['message' => 'only shelters can add pets'], 403);
        }

        $data = $r->validate([
            'name' => 'required|string|max:255',
            'species' => 'required|string|max:255',
            'breed' => 'nullable|string',
            'age' => 'required|integer|min:0',
            'size' => 'nullable|in:small,medium,large',
            'gender' => 'nullable|in:male,female,Macho,Hembra',
            'description' => 'nullable|string',
            'photo_url' => 'nullable|image|max:2048',
            'vaccinated' => 'boolean',
            'is_sterilized' => 'boolean',
        ]);

        $data['shelter_id'] = auth()->id();

        if ($r->hasFile('photo_url')) {
            $path = $r->file('photo_url')->store('pets', 'public');
            $data['photo_url'] = $path;
        }

        $pet = Pet::create($data);
        return response()->json($pet, 201);
    }

    public function show(Pet $pet)
    {
        $user = auth('sanctum')->user();

        // Si no es autenticado o no es dueño del refugio, solo devuelve si está disponible
        if (!$user || ($user->role === 'shelter' && $user->id !== $pet->shelter_id)) {
            if ($pet->status !== 'available') {
                return response()->json(['message' => 'No autorizado'], 403);
            }
        }

        return response()->json($pet->load('shelter', 'applications'));
    }

public function update(Request $r, Pet $pet)
{
    $user = auth('sanctum')->user();

    if (!$user || $user->id !== $pet->shelter_id) {
        return response()->json(['message' => 'No autorizado'], 403);
    }

    // Acepta tanto PUT como POST + _method=PUT
    if ($r->isMethod('post') && $r->input('_method') === 'PUT') {
        $r->setMethod('PUT');
    }

    $data = $r->validate([
        'name' => 'sometimes|required|string|max:255',
        'species' => 'nullable|string|max:255',
        'breed' => 'nullable|string|max:255',
        'gender' => 'nullable|in:male,female,Macho,Hembra',
        'description' => 'nullable|string',
        'photo_url' => 'nullable|image|max:2048',
    ]);

    if ($r->hasFile('photo_url')) {
        $path = $r->file('photo_url')->store('pets', 'public');
        $data['photo_url'] = $path;
    }

    Log::info('Datos recibidos para update:', $data);

    $ok = $pet->update($data);
    Log::info('Actualización exitosa?', [$ok]);
    Log::info('Pet actualizado:', $pet->toArray());

    return response()->json($pet);
}


    public function destroy(Pet $pet)
    {
        $user = auth('sanctum')->user();

        if (!$user || $user->id !== $pet->shelter_id) {
            return response()->json(['message' => 'Not authorized'], 403);
        }

        $pet->delete();
        return response()->json(null, 204);
    }
}
