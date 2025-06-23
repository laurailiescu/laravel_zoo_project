@extends('enclosures.layout')

@section('title', $enclosure->name)

@section('content')
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <div class="d-flex">
        <h1 class="ps-3 me-auto">
            <span>
                {{ $enclosure->name }}
            </span>
            <span class="badge">
                @if ($enclosure->animals()->where('is_predator', true)->exists())
                    <span class="badge rounded-pill bg-danger fs-6">Ragadozót tartalmaz</span>
                @endif
            </span>
        </h1>
        @auth
            @if (Auth::user()->admin && $enclosure->id != 1)
                <a href="{{ route('enclosures.edit', ['enclosure' => $enclosure->id]) }}" class="btn btn-primary mx-1" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Szerkesztés" >
                    <i class="fa-solid fa-pen-to-square fa-fw fa-xl"></i>
                </a>
                <form action="{{ route('enclosures.destroy', ['enclosure' => $enclosure->id]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger mx-1" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Törlés">
                        <i class="fa-solid fa-trash fa-fw fa-xl"></i>
                    </button>
                </form>
            @endif
        @endauth
    </div>
    <ul style="list-style-type:none;">
        <li>Állatok száma: {{ $enclosure->animals->count() }}</li>
        <li>Állatok limitje: {{ $enclosure->limit }}</li>
    </ul>
    <hr />
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-5">
        @foreach ($enclosure->animals()->orderBy('species')->orderBy('born_at', 'desc')->get() as $animal)
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <img src="{{ $animal->filename && Storage::exists($animal->filename_hash) ? Storage::url($animal->filename_hash) : Storage::url('placeholder.png')  }}" class="card-img-top img-fluid" alt="{{ $animal->name }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $animal->name }}</h5>
                        <p class="card-text">
                            <strong>Faj:</strong> {{ $animal->species }} <br>
                            <strong>Született:</strong> {{ \Carbon\Carbon::parse($animal->born_at)->format('Y. m. d.') }}
                        </p>
                        @auth
                            @if (Auth::user()->admin)
                                <div class="d-flex justify-content-between align-align-items-center px-2">
                                    <a href="{{ route('animals.edit', ['animal' => $animal->id]) }}" class="btn btn-primary mx-1" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Szerkesztés" >
                                        <i class="fa-solid fa-pen-to-square fa-fw fa-xl"></i>
                                    </a>
                                    <form action="{{ route('animals.destroy', ['animal' => $animal->id]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger mx-1" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Archiválás">
                                            <i class="fa-solid fa-archive fa-fw fa-xl"></i>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
