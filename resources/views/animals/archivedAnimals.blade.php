@extends('enclosures.layout')

@section('title', 'Archivált állatok')

@section('content')
    <h1 class="ps-3">Archivált állatok</h1>
    <hr />
    <div class="table-responsive">
        <table class="table align-middle table-hover">
            <thead class="text-center table-light">
                <tr>
                    <th style="width: 20%">Név</th>
                    <th style="width: 25%">Faj</th>
                    <th style="width: 25%">Archiválás időpontja</th>
                    <th style="width: 30%"></th>
                </tr>
            </thead>
            <tbody class="text-center">
                @foreach ($archivedAnimals as $animal)
                    <tr>
                        <td>
                            <div> {{ $animal->name }} </div>
                        </td>
                        <td>
                            <div> {{ $animal->species }} </div>
                        </td>
                        <td>
                            <div> {{ $animal->deleted_at }} </div>
                        </td>
                        <td>
                            <form action="{{ route('animals.restore', ['id' => $animal->id]) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button class="btn btn-success mx-1" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Visszaállítás">
                                    <i class="fa-solid fa-rotate-left fa-fw fa-xl"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
