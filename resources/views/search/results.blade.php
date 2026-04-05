@extends('layouts.app')

@section('content')
<h1>Search Results for "{{ $query }}"</h1>

<!-- فلتر النطاق -->
<form method="GET" action="{{ route('search') }}" class="mb-3">
    <input type="hidden" name="q" value="{{ $query }}">
    <label for="scope">Search in:</label>
    <select name="scope" id="scope" onchange="this.form.submit()">
        <option value="all" {{ $scope == 'all' ? 'selected' : '' }}>All</option>
        <option value="sentences" {{ $scope == 'sentences' ? 'selected' : '' }}>Sentences Only</option>
        <option value="words" {{ $scope == 'words' ? 'selected' : '' }}>Words Only</option>
    </select>
</form>

<audio id="sharedAudio" hidden>
    <source id="sharedSource" src="" type="audio/wav">
    Your browser does not support the audio element.
</audio>

@php
    function timeToSeconds($time) {
        [$h, $m, $s] = sscanf($time, '%d:%d:%f');
        return $h * 3600 + $m * 60 + $s;
    }
@endphp

@if($scope == 'all' || $scope == 'sentences')
    <h3>Sentences</h3>
    @if($sentences->isEmpty())
        <p>No sentences found.</p>
    @else
        <ul class="list-group mb-4">
            @foreach ($sentences as $sentence)
                <li class="list-group-item">
                    <strong>{{ $sentence->subject_sentence }}</strong> 
                    <span class="text-primary" 
                          onclick="playSegment('{{ asset($sentence->sound->sound_name) }}', {{ timeToSeconds($sentence->start_time) }}, {{ timeToSeconds($sentence->end_time) - 0.05 }})">
                        🔊 Play
                    </span>
                    <br>
                    <em>Correct:</em> {{ $sentence->correct_sentence }} <br>
                    <small>User: {{ $sentence->sound->user->name }} | Sound: {{ $sentence->sound->sound_name }}</small>
                </li>
            @endforeach
        </ul>
    @endif
@endif

@if($scope == 'all' || $scope == 'words')
    <h3>Words</h3>
    @if($words->isEmpty())
        <p>No words found.</p>
    @else
        <table class="table table-bordered table-sm">
            <thead class="table-light">
                <tr>
                    <th>Arabic</th>
                    <th>Subject Pronunciation</th>
                    <th>Correct Pronunciation</th>
                    <th>Errors</th>
                    <th>Notes</th>
                    <th>Play</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($words as $word)
                    <tr>
                        <td>{{ $word->arabic_word }}</td>
                        <td>{{ $word->subject_pronunciation }}</td>
                        <td>{{ $word->correct_pronunciation }}</td>
                        <td>{{ $word->phonological_errors ?? '-' }}</td>
                        <td>{{ $word->notes ?? '-' }}</td>
                        <td>
                            <span class="text-primary"
                                  onclick="playSegment('{{ asset($word->sound->sound_name) }}', {{ timeToSeconds($word->start_time) }}, {{ timeToSeconds($word->end_time) - 0.05 }})">
                                🔊 Play
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endif

<script>
    function playSegment(audioPath, startTime, endTime) {
        const audio = document.getElementById('sharedAudio');
        const source = document.getElementById('sharedSource');

        if (source.src !== audioPath) {
            source.src = audioPath;
            audio.load();

            // أول مرة نعمل load ننتظر metadata
            audio.onloadedmetadata = () => {
                audio.currentTime = parseFloat(startTime);
                audio.play();
            };
        } else {
            // لو نفس الملف متشغل قبل كده
            audio.currentTime = parseFloat(startTime);
            audio.play();
        }

        const stop = () => {
            if (audio.currentTime >= parseFloat(endTime)) {
                audio.pause();
                audio.removeEventListener('timeupdate', stop);
            }
        };

        audio.addEventListener('timeupdate', stop);
    }
</script>
@endsection
