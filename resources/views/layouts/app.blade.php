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

        // Mobile sidebar toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggleTop');
            const sidebar = document.querySelector('.sidebar');
            const sidebarClose = document.querySelector('.sidebar-close');
            const overlay = document.createElement('div');
            
            overlay.className = 'sidebar-overlay';
            overlay.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
                z-index: 1040;
                display: none;
            `;
            
            document.body.appendChild(overlay);
            
            function closeSidebar() {
                sidebar.classList.remove('show');
                overlay.style.display = 'none';
            }
            
            function openSidebar() {
                sidebar.classList.add('show');
                overlay.style.display = 'block';
            }
            
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    if (sidebar.classList.contains('show')) {
                        closeSidebar();
                    } else {
                        openSidebar();
                    }
                });
            }
            
            if (sidebarClose) {
                sidebarClose.addEventListener('click', function() {
                    closeSidebar();
                });
            }
            
            // Close sidebar when clicking overlay
            overlay.addEventListener('click', function() {
                closeSidebar();
            });
            
            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(e) {
                if (window.innerWidth <= 768) {
                    if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                        closeSidebar();
                    }
                }
            });
            
            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    closeSidebar();
                }
            });
        });
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

        /* Responsive improvements */
        @media (max-width: 768px) {
            .container-fluid {
                padding-left: 10px;
                padding-right: 10px;
            }
            
            .card-header {
                flex-direction: column;
                align-items: stretch !important;
                gap: 10px;
            }
            
            .card-header h4 {
                margin-bottom: 0;
                text-align: center;
            }
            
            .btn {
                font-size: 0.875rem;
                padding: 0.375rem 0.75rem;
            }
            
            .btn-sm {
                font-size: 0.75rem;
                padding: 0.25rem 0.5rem;
            }
            
            .table-responsive {
                border: none;
            }
            
            .table th,
            .table td {
                padding: 0.5rem 0.25rem;
                font-size: 0.875rem;
            }
            
            .input-group {
                flex-direction: column;
            }
            
            .input-group .form-control {
                border-radius: 0.375rem !important;
                margin-bottom: 0.5rem;
            }
            
            .input-group .btn {
                border-radius: 0.375rem !important;
                width: 100%;
            }
            
            .breadcrumb {
                font-size: 0.875rem;
            }
            
            .alert {
                font-size: 0.875rem;
                padding: 0.75rem;
            }
        }

        @media (max-width: 576px) {
            .card-header h4 {
                font-size: 1.25rem;
            }
            
            .table th,
            .table td {
                padding: 0.375rem 0.125rem;
                font-size: 0.8rem;
            }
            
            .btn-sm {
                font-size: 0.7rem;
                padding: 0.2rem 0.4rem;
            }
            
            .pagination {
                justify-content: center;
                flex-wrap: wrap;
            }
            
            .pagination .page-link {
                padding: 0.375rem 0.5rem;
                font-size: 0.875rem;
            }
        }

        /* Mobile table improvements */
        @media (max-width: 768px) {
            .mobile-table {
                display: block;
            }
            
            .mobile-table thead {
                display: none;
            }
            
            .mobile-table tbody {
                display: block;
            }
            
            .mobile-table tr {
                display: block;
                margin-bottom: 1rem;
                border: 1px solid #dee2e6;
                border-radius: 0.375rem;
                padding: 1rem;
                background: #fff;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }
            
            .mobile-table td {
                display: block;
                text-align: left;
                padding: 0.5rem 0;
                border: none;
                position: relative;
                padding-left: 50%;
            }
            
            .mobile-table td:before {
                content: attr(data-label);
                position: absolute;
                left: 0;
                width: 45%;
                font-weight: bold;
                color: #495057;
            }
            
            .mobile-table .actions-cell {
                padding-left: 0;
                text-align: center;
                margin-top: 1rem;
                border-top: 1px solid #dee2e6;
                padding-top: 1rem;
            }
            
            .mobile-table .actions-cell:before {
                display: none;
            }
            
            .mobile-table .actions-cell .btn {
                margin: 0.25rem;
                display: inline-block;
            }
        }

        /* Sidebar mobile improvements */
        @media (max-width: 768px) {
            .sidebar {
                width: auto !important;
                min-width: 280px;
                max-width: 320px;
                position: fixed !important;
                top: 0;
                left: -100%;
                height: 100vh;
                z-index: 1050;
                transition: left 0.3s ease;
                padding: 0;
            }
            
            .sidebar.show {
                left: 0;
            }
            
            .sidebar-brand {
                padding: 1rem;
                display: flex;
                align-items: center;
                justify-content: space-between;
                border-bottom: 1px solid rgba(255,255,255,0.1);
            }
            
            .sidebar-brand-content {
                display: flex;
                align-items: center;
                flex: 1;
            }
            
            .sidebar-brand-icon {
                display: flex;
                align-items: center;
            }
            
            .sidebar-brand-text {
                font-weight: 600;
                font-size: 1.1rem;
            }
            
            .sidebar-close {
                background: none;
                border: none;
                color: white;
                font-size: 1.5rem;
                cursor: pointer;
                padding: 0.5rem;
                border-radius: 4px;
                transition: background-color 0.2s;
            }
            
            .sidebar-close:hover {
                background-color: rgba(255,255,255,0.1);
            }
            
            .sidebar .nav-item {
                margin: 0;
                border-bottom: 1px solid rgba(255,255,255,0.05);
            }
            
            .sidebar .nav-link {
                padding: 1rem 1.5rem;
                font-size: 1rem;
                display: flex;
                align-items: center;
                transition: background-color 0.2s;
            }
            
            .sidebar .nav-link:hover {
                background-color: rgba(255,255,255,0.1);
            }
            
            .sidebar .nav-link i {
                width: 20px;
                margin-right: 0.75rem;
                text-align: center;
            }
            
            .sidebar .nav-link span {
                flex: 1;
            }
            
            .sidebar .nav-item.active .nav-link {
                background-color: rgba(255,255,255,0.15);
                border-left: 4px solid #fff;
            }
            
            .sidebar .nav-item form {
                margin: 0;
            }
            
            .sidebar .nav-item .btn {
                width: 100%;
                text-align: left;
                padding: 1rem 1.5rem;
                background: none;
                border: none;
                color: white;
                font-size: 1rem;
                display: flex;
                align-items: center;
                transition: background-color 0.2s;
            }
            
            .sidebar .nav-item .btn:hover {
                background-color: rgba(255,255,255,0.1);
                color: white;
                text-decoration: none;
            }
            
            .sidebar .nav-item .btn i {
                width: 20px;
                margin-right: 0.75rem;
                text-align: center;
            }
            
            .sidebar .nav-item .btn span {
                flex: 1;
            }
            
            .sidebar-divider {
                margin: 0.5rem 1rem;
                border-color: rgba(255,255,255,0.1);
            }
        }

        /* Topbar mobile improvements */
        @media (max-width: 768px) {
            .topbar {
                padding: 0.5rem 1rem;
            }
            
            .topbar .navbar-nav {
                margin-left: auto;
            }
            
            .topbar .dropdown-menu {
                position: fixed !important;
                top: 60px !important;
                left: 0 !important;
                right: 0 !important;
                width: 100% !important;
                border-radius: 0;
                border: none;
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            }
        }

        /* Form responsive improvements */
        @media (max-width: 768px) {
            .form-control {
                font-size: 16px; /* Prevents zoom on iOS */
            }
            
            .form-label {
                font-weight: 600;
                margin-bottom: 0.5rem;
            }
            
            .card-body {
                padding: 1rem;
            }
            
            .btn {
                min-height: 44px; /* Better touch target */
            }
            
            .file-input-wrapper {
                position: relative;
                overflow: hidden;
                display: inline-block;
                width: 100%;
            }
            
            .file-input-wrapper input[type=file] {
                font-size: 100px;
                position: absolute;
                left: 0;
                top: 0;
                opacity: 0;
                cursor: pointer;
            }
            
            .file-input-wrapper .btn {
                display: block;
                width: 100%;
                text-align: center;
            }
        }

        /* Additional mobile improvements */
        @media (max-width: 576px) {
            .container {
                padding-left: 15px;
                padding-right: 15px;
            }
            
            .card {
                margin-bottom: 1rem;
            }
            
            .card-header {
                padding: 1rem;
                font-size: 1.1rem;
            }
            
            .form-control {
                padding: 0.75rem;
            }
            
            .btn {
                padding: 0.75rem 1rem;
                font-size: 1rem;
            }
        }
    </style>
</body>
</html> 