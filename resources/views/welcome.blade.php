@extends('layouts.app')

@section('content')
<div class="d-flex flex-column align-items-center justify-content-center text-center" style="min-height: 80vh;">
    
    
    <!-- Logo -->
    <img src="{{ asset('images/logo.png') }}" alt="ACCL Logo" 
         class="mb-4 rounded-circle shadow" 
         style="width:150px; height:150px; object-fit:cover;">
    
    <!-- Title -->
    <h1 class="mb-3">Arabic Corpus of Chinese Learners (ACCL)</h1>
    
    <!-- Description -->
    <p class="lead" style="max-width: 800px;">
        This project presents the development of the Arabic Corpus of Chinese Learners (ACCL), 
        a specialized learner corpus designed to capture the spoken Arabic of Chinese students 
        across various proficiency levels—novice, intermediate, and advanced. The corpus comprises 
        spoken data obtained through tasks such as  reading aloud ,picture descriptionand narration. 
        These recordings are annotated with phonological errors using Praat 
        and Python, focusing on common errors like sound substitution, deletion, and other pronunciation 
        deviations.
    </p>



    <!-- Button -->
    <a href="{{ url('/levels') }}" class="btn btn-primary btn-lg mt-4 shadow">
        Explore Levels
    </a>
</div>
@endsection
