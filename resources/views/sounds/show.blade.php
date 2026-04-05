<!-- resources/views/sounds/show.blade.php -->
@extends('layouts.app')
@section('content')
<h1>Sound Details: {{ ucfirst($sound->type) }}</h1>
<p><strong>User:</strong> {{ $sound->user->name }}</p>
<!-- <p><strong>File:</strong> {{ $sound->sound_name }}</p> -->

<audio id="sharedAudio" hidden>
    <source id="sharedSource" src="{{ asset($sound->sound_name) }}" type="audio/wav">
    Your browser does not support the audio element.
</audio>

<!-- Tabs -->
<ul class="nav nav-tabs" id="dataTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="sentences-tab" data-bs-toggle="tab" data-bs-target="#sentences" type="button" role="tab">Sentences</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="isolated-tab" data-bs-toggle="tab" data-bs-target="#isolated" type="button" role="tab">Isolated Words</button>
    </li>
</ul>

<div class="tab-content mt-3">

    <!-- Sentences Tab -->
    <div class="tab-pane fade show active" id="sentences" role="tabpanel">
        <input type="text" class="form-control mb-3" id="sentenceSearch" placeholder="Search sentences or words...">

        @foreach ($sound->sentences as $sentence)
            <div class="mb-4 sentence-block searchable-sentence">
                <p class="sentence-audio text-primary"
                   onclick="playSegment('{{ asset($sound->sound_name) }}', {{ timeToSeconds($sentence->start_time) }}, {{ timeToSeconds($sentence->end_time) }})">
                   <strong>Sentence:</strong> {{ $sentence->subject_sentence }} 🔊
                </p>
                <p><strong>Correct:</strong> {{ $sentence->correct_sentence }}</p>
                <h5>Words:</h5>
                <table class="table table-bordered table-sm">
                    <thead class="table-light">
                        <tr>
                            <th>Arabic</th>
                            <th>Subject Pronunciation</th>
                            <th>Correct Pronunciation</th>
                            <th>Phonological Errors</th>
                            <th>Notes</th>
                            <th>Play</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sentence->words as $word)
                            <tr>
                                <td>{{ $word->arabic_word }}</td>
                                <td>{{ $word->subject_pronunciation }}</td>
                                <td>{{ $word->correct_pronunciation }}</td>
                                <td>{{ $word->phonological_errors ?? '-' }}</td>
                                <td>{{ $word->notes ?? '-' }}</td>
                                <td>
                                    <span class="word-audio text-primary"
                                          onclick="playSegment('{{ asset($sound->sound_name) }}', {{ timeToSeconds($word->start_time) }}, {{ timeToSeconds($word->end_time) }})">
                                          🔊 Play
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    </div>

    <!-- Isolated Words Tab -->
    <div class="tab-pane fade" id="isolated" role="tabpanel">
        <input type="text" class="form-control mb-3" id="isolatedSearch" placeholder="Search isolated words...">

        <table class="table table-bordered table-sm" id="isolatedTable">
            <thead class="table-light">
                <tr>
                    <th>Arabic</th>
                    <th>Subject Pronunciation</th>
                    <th>Correct Pronunciation</th>
                    <th>Phonological Errors</th>
                    <th>Notes</th>
                    <th>Play</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($isolatedWords as $word)
                    <tr class="searchable-isolated">
                        <td>{{ $word->arabic_word }}</td>
                        <td>{{ $word->subject_pronunciation }}</td>
                        <td>{{ $word->correct_pronunciation }}</td>
                        <td>{{ $word->phonological_errors ?? '-' }}</td>
                        <td>{{ $word->notes ?? '-' }}</td>
                        <td>
                            <span class="word-audio text-primary"
                                  onclick="playSegment('{{ asset($sound->sound_name) }}', {{ timeToSeconds($word->start_time) }}, {{ timeToSeconds($word->end_time) }})">
                                  🔊 Play
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@php
    function timeToSeconds($time) {
        [$h, $m, $s] = sscanf($time, '%d:%d:%f');
        return $h * 3600 + $m * 60 + $s;
    }
@endphp

<script>
 function playSegment(audioPath, startTime, endTime) {
        const audio = document.getElementById('sharedAudio');
        const source = document.getElementById('sharedSource');

        const start = parseFloat(startTime);
        const end = parseFloat(endTime);

        // If a new audio file is selected
        if (source.src !== audioPath) {
            source.src = audioPath;
            audio.load();
            audio.onloadedmetadata = () => {
                audio.pause();
                audio.currentTime = start;
                audio.onseeked = () => {
                    audio.play();
                };
            };
        } else {
            // Already loaded
            audio.pause();
            audio.currentTime = start;
            audio.onseeked = () => {
                audio.play();
            };
        }

        // Stop playback exactly at endTime
        const stop = () => {
            if (audio.currentTime >= end) {
                audio.pause();
                audio.removeEventListener('timeupdate', stop);
            }
        };
        audio.addEventListener('timeupdate', stop);
    }
    // Local search for sentences
    document.getElementById('sentenceSearch').addEventListener('keyup', function () {
        const query = this.value.toLowerCase();
        document.querySelectorAll('.searchable-sentence').forEach(function (block) {
            block.style.display = block.textContent.toLowerCase().includes(query) ? '' : 'none';
        });
    });

    // Local search for isolated words
    document.getElementById('isolatedSearch').addEventListener('keyup', function () {
        const query = this.value.toLowerCase();
        document.querySelectorAll('#isolatedTable tbody tr').forEach(function (row) {
            row.style.display = row.textContent.toLowerCase().includes(query) ? '' : 'none';
        });
    });
</script>
@endsection
