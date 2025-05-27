<!DOCTYPE html>
<html
    lang="id"
    class="light-style layout-menu-fixed"
    dir="ltr"
    data-theme="theme-default"
    data-assets-path="{{ asset('public/sneat/') }}"
    data-template="vertical-menu-template-free"
>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"/>
    <title>{{ config('app.name') }}</title>
    <meta name="description" content=""/>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('surat.png') }}"/>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet"/>

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('sneat/vendor/fonts/boxicons.css') }}"/>

    <!-- Core CSS -->
    <link rel="stylesheet" class="template-customizer-core-css" href="{{ asset('sneat/vendor/css/core.css') }}"/>
    <link rel="stylesheet" class="template-customizer-theme-css" href="{{ asset('sneat/vendor/css/theme-default.css') }}"/>
    <link rel="stylesheet" href="{{ asset('sneat/css/demo.css') }}"/>

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('sneat/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}"/>
    <link rel="stylesheet" href="{{ asset('sneat/vendor/libs/sweetalert2/sweetalert2.min.css') }}"/>

    <!-- Page CSS -->
    @stack('style')

    <!-- Helpers -->
    <script src="{{ asset('sneat/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('sneat/js/config.js') }}"></script>
</head>

<body>
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Sidebar -->
        @include('components.sidebar')

        <!-- Layout page -->
        <div class="layout-page">
            <!-- Navbar -->
            @include('components.navbar')

            <!-- Content wrapper -->
            <div class="content-wrapper">
                <!-- Content -->
                <div class="container-xxl flex-grow-1 container-p-y">
                    @yield('content')
                </div>

                <!-- Footer -->
                @include('components.footer')

                <div class="content-backdrop fade"></div>
            </div>
        </div>
    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>
</div>

<!-- Core JS -->
<script src="{{ asset('sneat/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('sneat/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('sneat/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('sneat/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('sneat/vendor/js/menu.js') }}"></script>

<!-- Vendors JS -->
<script src="{{ asset('sneat/vendor/libs/masonry/masonry.js') }}"></script>
<script src="{{ asset('sneat/vendor/libs/sweetalert2/sweetalert2.all.min.js') }}"></script>

<!-- Main JS -->
<script src="{{ asset('sneat/js/main.js') }}"></script>

<!-- Delete confirmation -->
<script>
    $(document).on('click', '.btn-delete', function () {
        Swal.fire({
           title: "{{ __('menu.general.delete_confirm') }}",
            text: "{{ __('menu.general.delete_warning') }}",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#696cff',
            confirmButtonText: "{{ __('menu.general.delete') }}",
            cancelButtonText: "{{ __('menu.general.cancel') }}"
        }).then((result) => {
            if (result.isConfirmed) {
                $(this).parent('form').submit();
            }
        });
    });
</script>

<!-- Toast notifications -->
@if(session('success'))
    <script>
        Toast.fire({
            icon: 'success',
            title: "{{ session('success') }}"
        });
    </script>
@elseif(session('error'))
    <script>
        Toast.fire({
            icon: 'error',
            title: "{{ session('error') }}"
        });
    </script>
@elseif(session('info'))
    <script>
        Toast.fire({
            icon: 'info',
            title: "{{ session('info') }}"
        });
    </script>
@endif

@stack('script')

<!-- GitHub buttons -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
</body>
</html>
