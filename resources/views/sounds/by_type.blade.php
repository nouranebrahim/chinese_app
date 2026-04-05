<!-- @* resources/views/sounds/by_type.blade.php *@ -->
@extends('layouts.app')
@section('content')
<h1>Sounds of Type: {{ ucfirst($type) }}</h1>
<ul>
    @foreach ($sounds as $sound)
        <li>
            {{ $sound->user->name }} - 
            <a href="{{ url('/sounds/' . $sound->id) }}">{{ $sound->sound_name }}</a>
        </li>
    @endforeach
</ul>
@endsection

