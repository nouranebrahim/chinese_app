@extends('layouts.app')
@section('content')
<h2>Participants in {{ ucfirst($level) }} Level</h2>

<!-- Local Search -->
<input type="text" id="searchInput" class="form-control mb-3" placeholder="Search participants...">

<ul id="participantsList">
    @foreach ($users as $user)
        <li><a href="{{ url('/users/' . $user->id . '/sounds') }}">{{ $user->name }}</a></li>
    @endforeach
</ul>

<script>
document.getElementById('searchInput').addEventListener('keyup', function() {
    const filter = this.value.toLowerCase();
    const items = document.querySelectorAll('#participantsList li');
    items.forEach(item => {
        const text = item.textContent.toLowerCase();
        item.style.display = text.includes(filter) ? '' : 'none';
    });
});
</script>
@endsection
