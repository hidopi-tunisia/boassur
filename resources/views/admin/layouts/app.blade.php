<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Presence Assistance Tourisme</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@200;400;600&family=Red+Hat+Display:wght@400;500;700;900&display=swap" rel="stylesheet">
    <link rel=" stylesheet" href="{{ mix('css/app.css') }}">
    <link href="https://www.unpkg.com/trix@1.3.1/dist/trix.css" rel="stylesheet">

    @livewireStyles
    @stack('styles')
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.1/dist/alpine.min.js" defer></script>
    <script src="{{ mix('js/app.js') }}" defer></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
</head>

<body class="text-gray-800 bg-gray-100 antialiased">
    @include('admin.layouts.sidebar')

    <div class="relative md:ml-64 bg-gray-100">
        @include('admin.layouts.topbar')
        <div class="relative mb-6 mx-4 -m-20 shadow-lg bg-white">
            <div class="text-left">
                {{ $slot }}
            </div>
        </div>
        <footer class="block py-4">
            <div class="container mx-auto px-4">
                <hr class="mb-4 border-b-1 border-gray-300" />
                <div class="flex flex-wrap items-center md:justify-between justify-center">
                    <div class="w-full md:w-4/12 px-4">
                        <div class="text-sm text-gray-600 font-semibold py-1 text-center md:text-left">
                            Copyright © Presence Assistance
                        </div>
                    </div>
                    <div class="w-full md:w-8/12 px-4">
                    </div>
                </div>
            </div>
        </footer>
    </div>

    @stack('modals')

    <script src="https://www.unpkg.com/trix@1.3.1/dist/trix.js"></script>

    @livewireScripts
    @stack('scripts')
</body>


<script type="text/javascript">
                        $(function() {
                            $('input[name="datefilter"]').daterangepicker({
                                autoUpdateInput: false,
                                locale: {
                                    cancelLabel: 'Clear'
                                }
                            });
                            $('input[name="datefilter"]').on('apply.daterangepicker', function(ev, picker) {
                                $(this).val(picker.startDate.format('Y-m-d') + ' - ' + picker.endDate.format('Y-m-d'));
      console.log(picker.startDate.format('Y-m-d'))
                            });
                            $('input[name="datefilter"]').on('cancel.daterangepicker', function(ev, picker) {
                                $(this).val('');
                            });
                        });
                    </script>
</html>