@extends('enclosures.layout')

@section('title', 'Kifutók')

@section('content')
    <h1 class="ps-3">
        @auth
            @if (Auth::user()->admin)
                Összes kifutó
            @else
                Saját kifutók
            @endif
        @endauth
    </h1>
    <hr />
    <div class="table-responsive">
        <table class="table align-middle table-hover">
            <thead class="text-center table-light">
                <tr>
                    <th style="width: 20%">Név</th>
                    <th style="width: 25%">Állatok száma</th>
                    <th style="width: 25%">Állatok limitje</th>
                    <th style="width: 30%"></th>
                </tr>
            </thead>
            <tbody class="text-center">
                @foreach ($enclosures as $enclosure)
                    <tr class="@if($enclosure->animals()->get()->count() * 100 / $enclosure->limit > 80) table-danger @elseif($enclosure->animals()->get()->count() * 100 / $enclosure->limit > 60) table-warning @endif">
                        <td>
                            <div>
                                <a href="{{ route('enclosures.show', ['enclosure' => $enclosure->id]) }}">{{ $enclosure->name }}</a>
                            </div>
                        </td>
                        <td>
                            <div> {{ $enclosure->animals()->count() }} </div>
                        </td>
                        <td>
                            <div> {{ $enclosure->limit }} </div>
                        </td>
                        <td>
                            <button class="btn btn-outline-secondary">
                                <a href="{{ route('enclosures.show', ['enclosure' => $enclosure->id]) }}"><i class="fa-solid fa-angles-right fa-fw"></i></a>
                            </button>
                            @auth
                                @if (Auth::user()->admin && $enclosure->id != 1)
                                    <a href="{{ route('enclosures.edit', ['enclosure' => $enclosure->id]) }}" class="btn btn-primary mx-1" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Szerkesztés">
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
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $enclosures->links() }}
    </div>
@endsection
