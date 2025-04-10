<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ $title }}</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body style="background-color: #eee;">
        
        <!-- Notifications Section -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @auth
            @if (auth()->user()->unreadNotifications->count() > 0)
                <div class="alert alert-info">
                    You have {{ auth()->user()->unreadNotifications->count() }} unread notifications.
                </div>
            @endif
        @endauth

        <!-- Content Section -->
        @yield('content')
    

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        @auth

            @vite('resources/js/app.js')
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const userId = {{ auth()->id() }};
                    console.log('User ID:', userId);

                    Echo.private(`App.Models.User.${userId}`)
                    .listen('task.reminder', (e) => {
                        // Log the event first to inspect it
                        console.log('Received event:', e);

                        // Parse the data if it's a string
                        if (e.data) {
                            try {
                                const eventData = JSON.parse(e.data);
                                console.log('Parsed event data:', eventData);
                            } catch (err) {
                                console.error('Error parsing event data:', err);
                            }
                        }
                    });
                });


            </script>
        @endauth
    </body>
</html>