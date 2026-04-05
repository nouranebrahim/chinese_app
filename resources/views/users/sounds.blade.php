<!-- @* resources/views/users/sounds.blade.php *@ -->
@extends('layouts.app')
@section('content')
<h1>Sound Recordings for {{ $user->name }}</h1>
<ul>
    @foreach ($sounds as $sound)
        <li>
            <strong>{{ ucfirst($sound->type) }}</strong> - 
            <a href="{{ url('/sounds/' . $sound->id) }}">View Details ({{ $sound->sentences_count }} sentences)</a>
        </li>
    @endforeach
</ul>
@endsection