<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use Illuminate\Http\Request;

class PetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $r)
    {
        // Lógica para obtener la lista de mascotas
        $q = Pet::query();
        if ($r->has('species')) $q->where('species', $r->species);
        if ($r->has('city')) $q->whereHas('shelter', fn($q) => $q->where('city', $r->city));
        return $q->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $r)
    {
        //
        if (auth()->user()->role !== 'shelter') {
            return response()->json(['message' => 'only shelters can add pets'], 403);
        }
        $data = $r->validate([
            'name' => 'required|string|max:255',
            'species' => 'required|string|max:255',
            'breed' => 'nullable|string',
            'age' => 'required|integer|min:0',
            'size' => 'nullable|in: small,medium,large',
            'gender' => 'nullable|in: male,female',
            'description' => 'nullable|string',
            'image_url' => 'nullable|image|max:2048',
            'vaccinated' => 'boolean',
            'is_sterilized' => 'boolean',
        ]);
        $data['shelter_id'] = auth()->id();
        if ($r->hasFile('image_url')) {
            $path = $r->file('image_url')->store('pets', 'public');
            $data['image_url'] = $path;
        }
        $pet = Pet::create($data);
        return response()->json($pet, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Pet $pet)
    {
        //
        return response()->json($pet->load('shelter', 'applications'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $r, Pet $pet)
    {
        //
        if (auth()->id() !== $pet->shelter_id) return response()->json(['message' => 'Not authorized'], 403);
        $data = $r->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in: available,adopted',

        ]);
        if ($r->hasFile('image_url')) {
            $path = $r->file('image_url')->store('pets', 'public');
            $data['image_url'] = $path;
        }

        $pet->update($data);
        return response()->json($pet);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pet $pet)
    {
        //
        if (auth()->id() !== $pet->shelter_id) return response()->json(['message' => 'Not authorized'], 403);
        $pet->delete();
        return response()->json(null, 204);
    }
}
