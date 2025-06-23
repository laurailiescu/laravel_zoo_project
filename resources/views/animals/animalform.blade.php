@extends('enclosures.layout')

@section('title', isset($animal) ? $animal->name : 'Új állat')

@section('content')
    <h1 class="ps-3">
        @isset($animal)
            {{ $animal->name }}
        @else
            Új állat
        @endisset
    </h1>
    <hr />
    <form enctype="multipart/form-data" method="POST" action="{{ isset($animal) ? route('animals.update', [$animal->id]) : route('animals.store') }}">
        @csrf
        @isset($animal)
            @method('PUT')
        @endisset
        <div class="row mb-3">
            <div class="col">
                <input
                    type="text"
                    class="form-control @error('name') is-invalid @enderror"
                    placeholder="Állat neve"
                    name="name"
                    id="name"
                    value="{{ old('name', $animal->name ?? '') }}"
                />
                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col">
                <input
                    type="text"
                    class="form-control @error('species') is-invalid @enderror"
                    placeholder="Állat faja"
                    name="species"
                    id="species"
                    value="{{ old('species', $animal->species ?? '') }}"
                />
                @error('species')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <label class="form-check-label" for="is_predator"> Ragadozó-e: </label>
                <br>
                <input type="hidden" name="is_predator" value="0">
                <input type="checkbox"
                    class="form-check-input @error('is_predator') is-invalid @enderror"
                    name="is_predator"
                    id="is_predator"
                    value="1"
                    {{ old('is_predator', $animal->is_predator ?? false) ? 'checked' : '' }}>
                @error('is_predator')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col">
                <label for="born_at">Születési idő:</label>
                <br>
                <input type="datetime-local"
                    class = "@error('born_at') is-invalid @enderror"
                    id="born_at"
                    name="born_at"
                    value="{{ old('born_at', $animal->born_at ?? "") }}">
                    <br>
                @error('born_at')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <select class="form-select @error('enclosure_id') is-invalid @enderror" name="enclosure_id" id="enclosure_id">
                    <option value="x" disabled>Kifutó</option>
                    @foreach ($enclosures as $enclosure)
                        <option value="{{ $enclosure->id }}" @selected(old('enclosure_id', $animal->enclosure_id ?? '') == $enclosure->id)>{{ $enclosure->name }}</option>
                    @endforeach
                </select>
                @error('enclosure_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        <div class="mb-3">
            <input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file">
             @error('file')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="row">
            <button type="submit" class="btn btn-primary">Mentés</button>
        </div>
    </form>
@endsection
