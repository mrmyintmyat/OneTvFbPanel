 <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="/fontawesome-free-6.4.0-web/css/all.min.css">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/date_input/daterangepicker-master/daterangepicker.css" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
@yield('style')

<body class="overflow-auto">
    <div aria-live="polite" aria-atomic="true" class="position-relative">
        <div class="toast-container top-0 end-0 p-3">
            <div id="toast" @if (session('success')) class="toast show" @endif class="toast"
                role="alert" aria-live="assertive" aria-atomic="true" data-aos="fade-left">
                <div class="toast-header">
                    <i class="fa-solid fa-circle-check rounded me-2" style="color: #13C39C;"></i>
                    <strong class="me-auto">Success</strong>
                    <small class="text-muted">just now</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    DONE
                </div>
            </div>
        </div>
    </div>

    <div aria-live="polite" aria-atomic="true" class="position-relative">
        <div class="toast-container top-0 end-0 p-3">
            <div id="error_toast" class="toast"
                role="alert" aria-live="assertive" aria-atomic="true" data-aos="fade-left">
                <div class="toast-header">
                    <i class="fa-solid fa-circle-exclamation rounded me-2" style="color: #d01b1b;"></i>
                    <strong class="me-auto">error</strong>
                    <small class="text-muted">just now</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    User not found.
                </div>
            </div>
        </div>
    </div>

    @if (session('success_user'))
        <div aria-live="polite" aria-atomic="true" class="position-relative">
            <div class="toast-container top-0 end-0 p-3">


                <div id="liveToast" class="toast show" role="alert" aria-live="assertive" aria-atomic="true" data-aos="fade-left">
                    <div class="toast-header">
                        <i class="fa-solid fa-circle-check rounded me-2" style="color: #13C39C;"></i>
                        <strong class="me-auto">Success</strong>
                        <small class="text-muted">just now</small>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        {{ session('success_user') }}
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="text-center" style="overflow-x: hidden;">
        <div class="row">
            <aside class="col-md-2 menu bg-menu p-0 d-flex flex-row flex-md-column">
                <h1 class="text-white h4 text-center my-4 d-md-block d-none">
                    <i class="fa-solid fa-lock"></i>
                    <span class=" ms-1 d-none d-lg-inline">Admin</span>
                </h1>
                <div class="w-100">
                    <div
                        class="list-group rounded-0 hover_menu_tag ps-3 d-flex align-content-around flex-row flex-md-column">
                        <a href="/admin" id="focus_tag"
                            class="list-group-item list-group-item-action text-center p-2 border-0 d-flex justify-content-center justify-content-md-start align-items-center text-white text-lg-start bg-menu"
                            aria-current="true">
                            <div class="d-flex flex-column flex-md-row align-items-center">
                                <i class="fas fa-home"></i>
                                <span class="ms-2 d-md-block d-none">HOME</span>
                            </div>
                        </a>
                        <a href="/admin/vn_matches" id="focus_tag"
                            class="list-group-item list-group-item-action text-center p-2 border-0 d-flex justify-content-center justify-content-md-start align-items-center text-white text-lg-start bg-menu"
                            aria-current="true">
                            <div class="d-flex flex-column flex-md-row align-items-center">
                                <i class="fas fa-home"></i>
                                <span class="ms-2 d-md-block d-none">VN MATCHES</span>
                            </div>
                        </a>
                        <a href="{{route('admin.users')}}" id="focus_tag"
                            class="list-group-item list-group-item-action text-center p-2 border-0 d-flex justify-content-center justify-content-md-start align-items-center text-white text-lg-start bg-menu"
                            aria-current="true">
                            <div class="d-flex flex-column flex-md-row align-items-center">
                                <i class="fa-solid fa-users"></i>
                                <span class="ms-2 d-md-block d-none">Users</span>
                            </div>
                        </a>
                        <a href="/admin/create" id="focus_tag"
                            class="list-group-item list-group-item-action text-center p-2 border-0 d-flex justify-content-center justify-content-md-start align-items-center text-white text-lg-start bg-menu"
                            aria-current="true">
                            <div class="d-flex flex-column flex-md-row align-items-center">
                                <i class="fa-solid fa-circle-plus"></i>
                                <span class="ms-2 d-md-block d-none">Create User</span>
                            </div>
                        </a>
                        <a href="/" id="focus_tag"
                            class="list-group-item list-group-item-action text-center p-2 border-0 d-flex justify-content-center justify-content-md-start align-items-center text-white text-lg-start bg-menu"
                            aria-current="true">
                            <div class="d-flex flex-column flex-md-row align-items-center">
                                <i class="fa-solid fa-chart-line"></i>
                                <span class="ms-2 d-md-block d-none">Dashboard</span>
                            </div>
                        </a>
                    </div>
                </div>
            </aside>
            <main class="col-md-10 overflow-scroll main_page p-0">
                <nav class="navbar navbar-expand shadow-sm border-bottom p-0">
                    <div class="container-fluid">
                        <div class="flex-grow"></div>
                        <ul class="navbar-nav">
                            <li class="nav-item dropdown"><a href="#" class="nav-link dropdown-toggle"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                            class="d-none">
                                            @csrf
                                        </form>


                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                          document.getElementById('logout-form').submit();"
                                            class="text-decoration-none dropdown-item">
                                            Logout
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
                <section class="scroll_page px-4 h-100" style="overflow-x: hidden;">
                    @yield('page')
                </section>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="/date_input/daterangepicker-master/moment.min.js"></script>
    <script src="/date_input/daterangepicker-master/daterangepicker.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        AOS.init();
    </script>
    @yield('script')
</body>

</html>
