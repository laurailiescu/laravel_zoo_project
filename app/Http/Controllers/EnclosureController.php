<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\Enclosure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnclosureController extends Controller
{
    /**
     * Display a listing of the enclosures.
     */
    public function index()
    {
        $enclosures = Auth::user()->admin
                        ? Enclosure::orderBy('name')
                            ->paginate(5)
                        : Auth::user()
                            ->enclosures()
                            ->orderBy('name')
                            ->paginate(5);
        return view('enclosures.enclosures', [
            'enclosures' => $enclosures,
        ]);
    }

    /**
     * Show the form for creating a new enclosure.
     */
    public function create()
    {
        if(!Auth::user()->admin) {
            abort(401);
        }

        return view('enclosures.enclosureform');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(!Auth::user()->admin) {
            abort(401);
        }

        $validated = $request->validate([
            'name' => 'required|string|min:1|max:100',
            'limit' => 'required|integer|min:1|max:150',
            'feeding_at' => 'required|date_format:H:i',
        ]);

        $enclosure = Enclosure::create($validated);

        $enclosure->users()->attach(Auth::id());

        return redirect()->route('enclosures.show', [
            'enclosure' => $enclosure->id,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $enclosure = Enclosure::findOrFail($id);

        // Authorization
        if(!$enclosure->users->contains(Auth::id()) && !Auth::user()->admin) {
            abort(401);
        }

        return view('enclosures.enclosure', [
            'enclosure' => $enclosure,
        ]);
    }

    /**
     * Show the form for editing the enclosure.
     */
    public function edit(string $id)
    {
        // Authorization
        if(!Auth::user()->admin || $id == 1) {
            abort(401);
        }

        $users = User::all();

        $enclosure = Enclosure::findOrFail($id);

        return view('enclosures.enclosureform', [
            'enclosure' => $enclosure,
            'users' => $users,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Authorization
        if(!Auth::user()->admin || $id == 1) {
            abort(401);
        }

        $enclosure = Enclosure::findOrFail($id);

        // Validáció
        $validated = $request->validate([
            'name' => 'required|string|min:1|max:100',
            'limit' => [
                'required',
                'integer',
                'min:1',
                'max:150',
                function ($attribute, $value, $error) use ($id) {
                    $animalsCount = Enclosure::find($id)->animals()->count();

                    if($value < $animalsCount) {
                        return $error('The animals limit cannot be smaller than current animal count.');
                    }
                }
            ],
            'feeding_at' => 'required|date_format:H:i',
            'caretakers' => 'nullable|array',
            'caretakers.*' => 'exists:users,id',
        ]);

        $enclosure->update($validated);
        $enclosure->users()->sync($validated['caretakers'] ?? []);

        return redirect()->route('enclosures.show', [
            'enclosure' => $enclosure,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Authorization
        if(!Auth::user()->admin || $id == 1) {
            abort(401);
        }

        $enclosure = Enclosure::findOrFail($id);

        if($enclosure->animals()->count() > 0) {
            return redirect()->route('enclosures.show', ['enclosure' => $enclosure])->with('error', 'Ki kell törölni minden állatot a kifutóból mielőtt azt törlöd.');
        }

        foreach(Animal::withTrashed()->where('enclosure_id', $enclosure->id)->get() as $animal) {
            $animal->enclosure_id = 1;
            $animal->save();
        }

        $enclosure->delete();

        return redirect()->route('enclosures.index');
    }
}
