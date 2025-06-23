<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\Enclosure;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $totalEnclosures = Enclosure::count();
        $totalAnimals = Animal::count();

        date_default_timezone_set("CET");
        $now = Carbon::now()->format('H:i');

        $upcomingFeedings = Auth::user()
                          ->enclosures()
                          ->where('feeding_at', '>', $now)
                          ->orderBy('feeding_at')
                          ->get();

        return view('enclosures.home', [
            'enclosures' => $upcomingFeedings,
            'totalEnclosures' => $totalEnclosures,
            'totalAnimals' => $totalAnimals,
        ]);
    }

    // A többi függvény nem elérhető, mivel nincsenek beroutolva a web.php-ban
}
