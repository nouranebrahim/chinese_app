<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arabic Learner Corpus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 70px !important; /* keep navbar from overlapping */
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif !important;
            font-size: 25px !important; /* visibly larger */
            line-height: 1.7 !important;
            color: #212529 !important;
            background-color: #fdfdfd !important;
        }

        h1, h2, h3, h4, h5 {
            font-weight: 700 !important;
            color: #222 !important;
        }

        table {
            font-size: 25px !important;
        }

        .sidebar {
            height: 100vh !important;
            position: fixed !important;
            top: 70px !important;
            left: 0 !important;
            width: 220px !important;
            background-color: #f8f9fa !important;
            border-right: 1px solid #dee2e6 !important;
            overflow-y: auto !important;
            font-size: 18px !important;
            display: flex;              /* مهم */
            flex-direction: column;
        }
        
        .contact-box {
            position: absolute;
            bottom: 70px;   /* تتحكمي في ارتفاعها */
            left: 0px;
            right: 0px;
        }
        .content {
            margin-left: 240px !important;
            padding: 30px !important;
        }

        .navbar {
            font-size: 20px !important;
        }

        .nav-link {
            font-size: 25px !important;
            font-weight: 600 !important;
        }

        .word-audio, .sentence-audio {
            cursor: pointer !important;
            color: #007bff !important;
            font-weight: 600 !important;
        }

        .word-audio:hover, .sentence-audio:hover {
            text-decoration: underline !important;
        }
    </style>
</head>
<body>
    <!-- Top Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="/" style="font-size: 22px;">ACCL Corpus</a>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar">
        <h6 class="text-center mt-2" style="font-size: 19px;">Levels</h6>
        <ul class="nav flex-column px-2">
            <li class="nav-item"><a href="{{ url('/levels/novice/users') }}" class="nav-link">Novice</a></li>
            <li class="nav-item"><a href="{{ url('/levels/intermediate/users') }}" class="nav-link">Intermediate</a></li>
            <li class="nav-item"><a href="{{ url('/levels/advanced/users') }}" class="nav-link">Advanced</a></li>
        </ul>
        
        <div style="margin-top: auto; padding: 10px;" class="contact-box">
            <div style="
                background: #f1f1f1;
                padding: 8px;
                border-radius: 6px;
                text-align: center;
                box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            ">
                <span style="font-weight: bold; font-size: 13px; color: #333;">
                    Contact Us:
                </span>
                <a href="mailto:nouran.ibrahim01723@alexu.edu.eg"
                   style="font-weight: bold; font-size: 11px; color: #007bff; text-decoration: none;">
                    nouran.ibrahim01723@alexu.edu.eg
                </a>
            </div>
</div>
    </div>

    <!-- Main Content -->
    <div class="content">
        @yield('content')
    </div>

    <!-- Shared Audio Player -->
    <audio id="sharedAudio" hidden>
        <source id="sharedSource" type="audio/wav">
        Your browser does not support the audio element.
    </audio>
    <script>
    let audioCtx, sourceNode, gainNode, currentAudioPath;

    async function playSegment(audioPath, startTime, endTime) {
        // Initialize audio context once
        if (!audioCtx) {
            audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            gainNode = audioCtx.createGain();
            gainNode.gain.value = 10; // 🔊 boost volume ~3.5x
            gainNode.connect(audioCtx.destination);
        }

        // Stop any previous playback
        if (sourceNode) {
            try { sourceNode.stop(); } catch(e) {}
        }

        // Fetch and decode the audio if not already loaded
        if (currentAudioPath !== audioPath) {
            const response = await fetch(audioPath);
            const arrayBuffer = await response.arrayBuffer();
            window.audioBuffer = await audioCtx.decodeAudioData(arrayBuffer);
            currentAudioPath = audioPath;
        }

        // Create a buffer source for the selected segment
        sourceNode = audioCtx.createBufferSource();
        sourceNode.buffer = window.audioBuffer;
        sourceNode.connect(gainNode);

        const start = parseFloat(startTime);
        const duration = Math.max(0, parseFloat(endTime) - start);
        sourceNode.start(0, start, duration);

        sourceNode.onended = () => { sourceNode = null; };
    }
</script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
