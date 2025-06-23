@extends('enclosures.layout')

@section('title', 'Főoldal')

@section('content')
    <h1 class="ps-3">Főoldal</h1>
    <hr />
    @auth
        <h2>Üdvözöllek, {{ Auth::user()->name }}!</h2>
    @endauth
    <p>Az állatkert jelenlegi adatai:</p>
    <ul>
        <li>A kifutók száma: {{ $totalEnclosures }} </li>
        <li>Az állatok száma: {{ $totalAnimals }}</li>
    </ul>
    <p>Íme a mai teendőid:</p>
    <div class="table-responsive">
        <table class="table align-middle table-hover">
            <thead class="text-center table-light">
                <tr>
                    <th style="width: 20%">Kifutó neve</th>
                    <th style="width: 25%">Etetési idő</th>
                    <th style="width: 30%"></th>
                </tr>
            </thead>
            <tbody class="text-center">
                @foreach ($enclosures as $enclosure)
                    <tr>
                        <td>
                            <div>
                                <a href="{{ route('enclosures.show', ['enclosure' => $enclosure->id]) }}">{{ $enclosure->name }}</a>
                            </div>
                        </td>
                        <td>
                            <div> {{ $enclosure->feeding_at }} </div>
                        </td>
                        <td>
                            <button class="btn btn-outline-secondary">
                                <a href="{{ route('enclosures.show', ['enclosure' => $enclosure->id]) }}"><i class="fa-solid fa-angles-right fa-fw"></i></a>
                            </button>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
