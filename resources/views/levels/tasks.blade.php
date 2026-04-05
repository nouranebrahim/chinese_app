<!-- @* resources/views/levels/tasks.blade.php *@ -->
@extends('layouts.app')
@section('content')
<h2>{{ ucfirst($level) }} Level - Select Task Type</h2>
<ul>
    <li><a href="{{ url('/levels/' . $level . '/tasks/reading/participants') }}">Reading</a></li>
    <li><a href="{{ url('/levels/' . $level . '/tasks/picture_description/participants') }}">Picture Description</a></li>
    <li><a href="{{ url('/levels/' . $level . '/tasks/narration/participants') }}">Narration</a></li>
</ul>
@endsection


@* resources/views/levels/participants.blade.php *@
@extends('layouts.app')
@section('content')
<h2>{{ ucfirst($level) }} - {{ ucfirst(str_replace('_', ' ', $task)) }} Task</h2>
<p>Participants who completed this task:</p>
<ul>
    @foreach ($users as $user)
        <li><a href="{{ url('/users/' . $user->id . '/sounds') }}">{{ $user->name }}</a></li>
    @endforeach
</ul>
@endsection