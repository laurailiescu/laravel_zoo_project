<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Animal;
use App\Models\Enclosure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AnimalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Itt az index függvényt az egyszerűség kedvéért az archivált állatok listájához használtam

        // Authorization
        if(!Auth::user()->admin){
            abort(401);
        }

        $archivedAnimals = Animal::onlyTrashed()
                                 ->orderBy('deleted_at', 'desc')
                                 ->get();

        return view('animals.archivedAnimals', [
            'archivedAnimals' => $archivedAnimals,
        ]);
    }

    /**
     * Show the form for creating a new animal.
     */
    public function create()
    {
        // Authorization
        if(!Auth::user()->admin) {
            abort(401);
        }

        $enclosures = Enclosure::all();

        return view('animals.animalform', [
            'enclosures' => $enclosures,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Authorization
        if(!Auth::user()->admin) {
            abort(401);
        }

        $validated = $request->validate([
            'name' => 'required|string|min:1|max:100',
            'species' => 'required|string|min:1|max:100',
            'is_predator' => 'required|boolean',
            'born_at' => 'required|date',
            'enclosure_id' => [
                'required',
                'integer',
                function ($attribute, $value, $error) use ($request) {
                    $enclosure = Enclosure::find($value);

                    if($enclosure->id == 1) {
                        return $error('You cannot place animals in this enclosure.');
                    }

                    if(!$enclosure) {
                        return $error('The given enclosure does not exist.');
                    }

                    if($enclosure->animals->count() >= $enclosure->limit) {
                        return $error('The given enclosure is full.');
                    }

                    $existingAnimals = $enclosure->animals;

                    if($existingAnimals->count() > 0) {
                        $enclosureHasPredators = $existingAnimals->first()->is_predator;

                        if((bool)$request->is_predator != $enclosureHasPredators) {
                            return $error('This enclosure can only contain ' . ($enclosureHasPredators ? 'predators.' : 'prey.'));
                        }
                    }
                }
            ],
            'file' => 'nullable|file',
        ]);

        $validated['born_at'] = \Carbon\Carbon::parse($validated['born_at'])->format('Y-m-d H:i:s');

        if($request->hasFile('file')) {
            $filename = $request->file('file')->store();
            $animal = Animal::create([
                ...$validated,
                'filename' => $request->file('file')->getClientOriginalName(),
                'filename_hash' => $filename,
            ]);
        } else {
            $animal = Animal::create($validated);
        }

        return redirect()->route('enclosures.show', [
            'enclosure' => $animal->enclosure,
        ]);    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        abort(401);
    }

    /**
     * Show the form for editing the enclosure.
     */
    public function edit(string $id)
    {
        // Authorization
        if(!Auth::user()->admin) {
            abort(401);
        }

        $enclosures = Enclosure::all();
        $animal = Animal::findOrFail($id);

        return view('animals.animalform', [
            'animal' => $animal,
            'enclosures' => $enclosures,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Authorization
        if(!Auth::user()->admin) {
            abort(401);
        }

        $animal = Animal::findOrFail($id);

        // Validáció
        $validated = $request->validate([
            'name' => 'required|string|min:1|max:100',
            'species' => 'required|string|min:1|max:100',
            'is_predator' => 'required|boolean',
            'born_at' => 'required|date',
            'enclosure_id' => [
                'required',
                'integer',
                function ($attribute, $value, $error) use ($request, $animal) {
                    $enclosure = Enclosure::find($value);
                    if(!$enclosure) {
                        return $error('The given enclosure does not exist.');
                    }

                    if($value != $animal->enclosure->id && $enclosure->animals->count() >= $enclosure->limit) {
                        return $error('The given enclosure is full.');
                    }

                    $existingAnimals = $enclosure->animals;

                    if($existingAnimals->count() > 0) {
                        $enclosureHasPredators = $existingAnimals->first()->is_predator;

                        if((bool)$request->is_predator != $enclosureHasPredators) {
                            return $error('This enclosure can only contain ' . ($enclosureHasPredators ? 'predators.' : 'prey.'));
                        }
                    }
                }
            ],
            'file' => 'nullable|file',
        ]);

        $validated['born_at'] = \Carbon\Carbon::parse($validated['born_at'])->format('Y-m-d H:i:s');

        if($request->hasFile('file')) {
            if($animal->filename_hash && Storage::exists($animal->filename_hash) && $animal->filename != $animal->filename_hash) {
                Storage::delete($animal->filename_hash);
            }

            $filename = $request->file('file')->store();
            $animal->update([
                ...$validated,
                'filename' => $request->file('file')->getClientOriginalName(),
                'filename_hash' => $filename,
            ]);
        } else {
            $animal->update($validated);
        }

        return redirect()->route('enclosures.show', [
            'enclosure' => Animal::find($id)->enclosure,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Authorization
        if(!Auth::user()->admin) {
            abort(401);
        }

        $animal = Animal::findOrFail($id);

        $animal->delete();

        return redirect()->route('enclosures.show', ['enclosure' => $animal->enclosure]);
    }

    public function restore(string $id)
    {
        // Authorization
        if(!Auth::user()->admin) {
            abort(401);
        }

        $animal = Animal::onlyTrashed()->findOrFail($id);

        $animal->restore();

        return redirect()->route('animals.index');
    }
}
