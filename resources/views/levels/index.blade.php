<!-- @* resources/views/levels/index.blade.php *@ -->
@extends('layouts.app')
@section('content')
<h1>Select Proficiency Level</h1>
<ul>
    @foreach ($levels as $level)
        <li><a href="{{ url('/levels/' . $level . '/users') }}">{{ ucfirst($level) }}</a></li>
    @endforeach
</ul>
@endsection


