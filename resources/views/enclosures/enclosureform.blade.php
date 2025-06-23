@extends('enclosures.layout')

@section('title', isset($enclosure) ? $enclosure->name : 'Új kifutó')

@section('content')
    <h1 class="ps-3">
        @isset($enclosure)
            {{ $enclosure->name }}
        @else
            Új kifutó
        @endisset
    </h1>
    <hr />
    <form enctype="multipart/form-data" method="POST" action="{{ isset($enclosure) ? route('enclosures.update', [$enclosure->id]) : route('enclosures.store') }}">
        @csrf
        @isset($enclosure)
            @method('PUT')
        @endisset
        <div class="row mb-3">
            <div class="col">
                <input
                    type="text"
                    class="form-control @error('name') is-invalid @enderror"
                    placeholder="Kifutó neve"
                    name="name"
                    id="name"
                    value="{{ old('name', $enclosure->name ?? '') }}"
                />
                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        <div class="mb-3">
            <input type="number"
                   value="{{ old('limit', $enclosure->limit ?? "") }}"
                   class="form-control @error('limit') is-invalid @enderror"
                   name="limit" id="limit"
                   placeholder="Kifutóban elhelyezhető állatok limitje">
            @error('limit')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="feeding_at" class="label">Etetési idő: </label>
            <input type="time"
                   name="feeding_at"
                   id="feeding_at"
                   value="{{ old('feeding_at', $enclosure->feeding_at ?? "") }}">
            @error('feeding_at')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        @isset($enclosure)
            <div class="mb-3">
                <label class="form-label" for="caretakers">A kifutó gondozói:</label>
                <div id="caretakers">
                    @foreach ($users as $user)
                        <div class="form-check">
                            <input
                                type="checkbox"
                                class="form-check-input @error('caretakers') is-invalid @enderror"
                                name="caretakers[]"
                                value="{{ $user->id }}"
                                id="caretaker{{ $user->id }}"
                                {{ collect(old('caretakers', $enclosure->users->pluck('id')->toArray()))->contains($user->id) ? 'checked' : '' }} >
                                <label class="form-check-label" for="caretaker{{ $user->id }}">
                                    {{ $user->name }}
                                </label>
                        </div>
                    @endforeach
                </div>
                @error('caretakers')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        @endisset
        <div class="row">
            <button type="submit" class="btn btn-primary">Mentés</button>
        </div>
    </form>
@endsection
