<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Genie</title>
    <link rel="icon" type="image/x-icon" href="/favicon_io/favicon.ico">
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
</head>
<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        @auth
            @include('partials.sidebar')
        @endauth
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                @include('partials.topbar')
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    @yield('breadcrumbs')
                    @yield('content')
                </div>
                <!-- End Page Content -->
            </div>
            <!-- End Main Content -->
        </div>
        <!-- End Content Wrapper -->
    </div>
    <!-- End Page Wrapper -->

    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
    @stack('scripts')
    
    <script>
        // Helper function to handle company logo display with fallback
        function displayCompanyLogo(imgElement, logoPath, companyName, size = '50px') {
            if (logoPath && logoPath.trim() !== '') {
                imgElement.src = logoPath;
                imgElement.alt = companyName + ' logo';
                imgElement.style.width = size;
                imgElement.style.height = size;
                imgElement.style.objectFit = 'cover';
                imgElement.classList.add('img-thumbnail');
                
                // Add error handler for broken images
                imgElement.onerror = function() {
                    this.src = '{{ asset("images/default-company.svg") }}';
                    this.alt = 'Default company logo';
                    this.classList.remove('img-thumbnail');
                    this.classList.add('default-logo');
                };
            } else {
                // No logo provided, show default
                imgElement.src = '{{ asset("images/default-company.svg") }}';
                imgElement.alt = 'Default company logo';
                imgElement.style.width = size;
                imgElement.style.height = size;
                imgElement.classList.remove('img-thumbnail');
                imgElement.classList.add('default-logo');
            }
        }
    </script>
    
    <style>
        .default-logo {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            border-radius: 8px;
            padding: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .company-logo-placeholder {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            color: white;
            border-radius: 8px;
            font-size: 1.2em;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
    </style>
</body>
</html> 