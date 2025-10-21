<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AdoptionApplication;
use App\Models\Pet;
use Illuminate\Http\Request;

class AdoptionApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
{
    $user = auth()->user();

   if ($user->role === 'adopter') {
    return response()->json(
        \App\Models\AdoptionApplication::where('adopter_id', $user->id)
            ->with('pet')
            ->get()
    );
}

    if ($user->role === 'shelter') {
        return response()->json(
            \App\Models\AdoptionApplication::whereHas('pet', function($q) use ($user) {
                $q->where('shelter_id', $user->id);
            })->with('pet', 'adopter')->get()
        );
    }

    return response()->json(['message' => 'Rol no autorizado'], 403);
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
        $r-> validate([
            'pet_id' => 'required|exists:pets,id',
            'reason_for_adoption' => 'required|string',
            'has_experience' => 'boolean',
        ]);
        $user = auth()->user();
        $pet = Pet::findOrFail($r->pet_id);
        if ($pet->shelter_id === $user->id) {
            return response()->json(['message' => 'Cannot apply to adopt your own pet'], 403);
        }
        $application = AdoptionApplication::firstOrCreate(
            [
                'pet_id' => $r->pet_id,
                'adopter_id' => $user->id,
            ],
            [
                'reason_for_adoption' => $r->reason_for_adoption ?? null, 'has_experience' => $r->has_experience ?? false,
            ]
        );
        return response()->json($application, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $r, AdoptionApplication $application)
    {
        //
        $pet = $application->pet;
        if (auth()->id() !== $pet->shelter_id) {
            return response()->json(['message' => 'Not authorized'], 403);
        }
        $r->validate(['status'=> 'required|in:approved,rejected']);
        $application->update(['status' => $r->status]);

        if ($r->status === 'approved') {
            $pet->update(['status' => 'adopted']);
            AdoptionApplication::where('pet_id', $pet->id)->where('id', '<>', $application->id)->update(['status' => 'rejected']);
        }
        return response()->json($application);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
