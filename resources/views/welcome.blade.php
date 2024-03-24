<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Time To Race!</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        {!! Vite::content('resources/js/app.js') !!}
    </script>

    <script type="module">
        import confetti from 'https://cdn.skypack.dev/canvas-confetti';
        window.createConfetti = function() {
            var duration = 15 * 1000;
            var animationEnd = Date.now() + duration;
            var defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 0 };

            function randomInRange(min, max) {
                return Math.random() * (max - min) + min;
            }

            var interval = setInterval(function() {
                var timeLeft = animationEnd - Date.now();

                if (timeLeft <= 0) {
                    return clearInterval(interval);
                }

                var particleCount = 50 * (timeLeft / duration);
                // since particles fall down, start a bit higher than random
                confetti({ ...defaults, particleCount, origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 } });
                confetti({ ...defaults, particleCount, origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 } });
                confetti({ ...defaults, particleCount, origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 } });
                confetti({ ...defaults, particleCount, origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 } });
                confetti({ ...defaults, particleCount, origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 } });
                confetti({ ...defaults, particleCount, origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 } });
                confetti({ ...defaults, particleCount, origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 } });
                confetti({ ...defaults, particleCount, origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 } });
                confetti({ ...defaults, particleCount, origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 } });
                confetti({ ...defaults, particleCount, origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 } });
            }, 250);
        }
    </script>

    <style>
        @keyframes spinAndScale {
            0% {
                transform: scale(2) rotate(0deg);
            }
            25% {
                transform: scale(0.5) rotate(90deg);
            }
            50% {
                transform: scale(1) rotate(180deg);
            }
            75% {
                transform: scale(1.5) rotate(270deg);
            }
            100% {
                transform: scale(2) rotate(360deg);
            }
        }

        .image-animation {
            animation: spinAndScale 1s infinite linear;
        }

    </style>

</head>
<body class="font-sans antialiased bg-gray-900 px-12">

<div id="race_panel" style="display: none;" class="relative">
    <h2 class="text-center text-3xl font-medium py-2 mb-6 mt-6 text-white">First to 50 clicks wins! Get clicking!</h2>

    <img src="{{ asset('img/cat.png') }}" class="image-animation mx-auto fixed top-1/3 left-1/3 hidden " id="cat">

    <div class="grid grid-cols-3 gap-3 text-center font-medium">
        @foreach($all_users as $user)
            <div class="col-span-1 border p-4" style="background-color: green;" id="user_cell_{{ $user->id }}">
                <div class="text-2xl text-white">{{ ucfirst($user->name) }}</div>
                <div class="text-5xl text-white reset_click" id="user_clicks_{{ $user->id }}">{{ $user->clicks }}</div>
            </div>
        @endforeach
    </div>
</div>

<div id="user_panel" style="display: block;">
    <h2 class="text-center text-3xl font-medium py-2 mb-6 text-white">Jump on your phones<br>Click your name below to start!<br></h2>
    @foreach($all_users as $user)
        <div>
            <div class="container mx-auto text-center py-2 m-0 bg-gray-500 border mb-2 text-xl font-medium">
                <a class="block text-white" href="/{{ strtolower($user->name) }}">{{ ucfirst($user->name) }}</a>
            </div>
        </div>
    @endforeach
</div>

<script>

    // loop the channels for each user, listen for the events being broadcast
    @foreach($all_users as $user)
        Echo.channel(`user.${{!! $user->id !!}}`)
        .listen('UserClickedEvent', (e) => {

            // increment the number of clicks
            document.getElementById('user_clicks_{!! $user->id !!}').innerHTML = e.user.clicks;

            // play the mp3 sound when the user reaches 5 clicks
            if (e.user.clicks >= 5) {
                var audio = new Audio('{{ asset('mp3/wow.mp3') }}');
                audio.play();

                // create confetti when the user reaches 5 clicks
                createConfetti();

                // show the cat image when the user reaches 5 clicks
                document.getElementById('cat').classList.remove('hidden');

                // flash the user cell when the user reaches 5 clicks, make it blue and red every 250ms
                var flash = setInterval(function () {
                    document.getElementById('user_cell_{!! $user->id !!}').style.backgroundColor = 'blue';
                    setTimeout(function () {
                        document.getElementById('user_cell_{!! $user->id !!}').style.backgroundColor = 'red';
                    }, 250);
                }, 500);

                return;
            }
        })
        // i could also adjust this so that the events are broadcast on the same channel
        .listen('UserResetClicksEvent', (e) => {
            document.getElementById('user_clicks_{!! $user->id !!}').innerHTML = 0;
        });
    @endforeach

    // you can make as many channels as you want
    Echo.channel(`user_reset_channel`)
        .listen('UserResetClicksEvent', (e) => {
            document.querySelector('.reset_click').innerHTML = 0;
        });

    // you can make as many channels as you want
    Echo.channel(`start_user_clicks`)
        .listen('StartUserClicksEvent', (e) => {
            // show the race panel, hide the user panel
            document.querySelector('#race_panel').style.display = 'block';
            document.querySelector('#user_panel').style.display = 'none';
        });

</script>

</body>
</html>
