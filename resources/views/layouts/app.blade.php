<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @stop
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <script>
    // Function to initialize plugins when jQuery is available
    function initializePlugins() {
        // Check if jQuery is loaded
        if (typeof jQuery !== 'undefined') {
            console.log('jQuery is available, initializing plugins');
            // Select2 initialization
            if (typeof $.fn.select2 !== 'undefined') {
                $('.select2').select2({
                    placeholder: "Select roles",
                    allowClear: true
                });
            } else {
                console.error('Select2 is not loaded yet.');
            }
            
            // Toastr configuration
            if (typeof toastr !== 'undefined') {
                toastr.options = {
                    "closeButton": true,
                    "progressBar": true,
                    "positionClass": "toast-top-right",
                    "showDuration": "300",
                    "hideDuration": "1000",
                    "timeOut": "5000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                };

                // Display toastr messages
                @if(session('success'))
                    toastr.success('{{ session('success') }}');
                @endif

                @if(session('error'))
                    toastr.error('{{ session('error') }}');
                @endif

                @if(session('info'))
                    toastr.info('{{ session('info') }}');
                @endif
                
                @if(session('warning'))
                    toastr.warning('{{ session('warning') }}');
                @endif
            } else {
                console.error('Toastr is not loaded yet.');
            }
        } else {
            console.error('jQuery is not loaded yet. Plugin initialization skipped.');
        }
    }

    // Listen for both events to ensure initialization happens
    document.addEventListener('DOMContentLoaded', function() {
        // Check if jQuery is already loaded (might be loaded synchronously)
        if (window.jQueryLoaded || typeof jQuery !== 'undefined') {
            initializePlugins();
        } else {
            // Wait for jQuery to be loaded
            document.addEventListener('jQueryLoaded', initializePlugins);
            // Fallback in case the event doesn't fire
            setTimeout(function() {
                if (typeof jQuery !== 'undefined') {
                    initializePlugins();
                }
            }, 1000);
        }
    });
    </script>
</body>
</html>
