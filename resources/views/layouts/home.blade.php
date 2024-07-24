<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdminPanel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="/fontawesome-free-6.4.0-web/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <link href="/tagsinput/css/select2.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css?v=<?php echo time(); ?>">
</head>
@yield('style')

<body class="overflow-auto">
    @if (session('success'))
        <div aria-live="polite" aria-atomic="true" class="position-relative">
            <div class="toast-container top-0 end-0 p-3">
                <div id="liveToast" class="toast show" role="alert" aria-live="assertive" aria-atomic="true"
                    data-aos="fade-left">
                    <div class="toast-header">
                        <i class="fa-solid fa-circle-check rounded me-2" style="color: #13C39C;"></i>
                        <strong class="me-auto">Success</strong>
                        <small class="text-muted">just now</small>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        {{ session('success') }}
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div aria-live="polite" aria-atomic="true" class="position-relative">
        <div class="toast-container top-0 end-0 p-3">
            <div id="toast" @if (session('done')) class="toast show" @endif class="toast"
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

    @if (session('error'))
        <div aria-live="polite" aria-atomic="true" class="position-relative">
            <div class="toast-container top-0 end-0 p-3">
                <div id="error_toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true"
                    data-aos="fade-left">
                    <div class="toast-header">
                        <i class="fa-solid fa-circle-exclamation rounded me-2" style="color: #d01b1b;"></i>
                        <strong class="me-auto">error</strong>
                        <small class="text-muted">just now</small>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        {{ session('error') }}
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="text-center" style="overflow-x: hidden;">
        <div class="row">
            <aside
                class="col-lg-2 shadow navbar navbar-expand-lg menu bg-menu p-0 d-flex flex-column justify-content-start align-items-start">
                <h1 class="text-white h4 text-center my-4 d-lg-block d-none w-100">
                    <i class="fa-solid fa-lock"></i>
                    <span class=" ms-1 d-none d-lg-inline">OneTv Panel</span>
                </h1>

                <div class="offcanvas offcanvas-start bg-menu w-100" tabindex="-1" id="offcanvasNavbar"
                    aria-labelledby="offcanvasNavbarLabel">
                    <div class="offcanvas-header px-2">
                        <button type="button" class="btn-close float-end" data-bs-dismiss="offcanvas"
                            aria-label="Close"></button>
                    </div>
                    <div class="list-group rounded-0 hover_menu_tag ps-lg-3 d-flex align-content-aroundb w-100">
                        <a href="/" id="focus_tag"
                            class="list-group-item list-group-item-action text-center p-2 px-3 d-flex  align-items-center text-white text-lg-start bg-menu"
                            aria-current="true">
                            <div class="d-flex align-items-center fw-semibold">
                                <i class="fa-solid fa-futbol"></i>
                                <span class="ms-2">MATCHES</span>
                            </div>
                        </a>
                        {{-- <a href="/vn_matches" id="focus_tag"
                            class="list-group-item list-group-item-action text-center p-2 px-3 d-flex  align-items-center text-white text-lg-start bg-menu"
                            aria-current="true">
                            <div class="d-flex align-items-center fw-semibold">
                                <i class="fa-solid fa-baseball"></i>
                                <span class="ms-2">VN MATCHES</span>
                            </div>
                        </a> --}}
                        <a id="focus_tag" href="/highlights"
                            class="list-group-item list-group-item-action text-center p-2 px-3 d-flex  align-items-center text-white text-lg-start bg-menu">
                            <div class="d-flex align-items-center fw-semibold">
                                <i class="fa-solid fa-clock-rotate-left"></i>
                                <span class="ms-2">HIGHLIGHTS</span>
                            </div>
                        </a>
                        <a id="focus_tag" href="/channel"
                            class="list-group-item list-group-item-action text-center p-2 px-3 d-flex  align-items-center text-white text-lg-start bg-menu">
                            <div class="d-flex align-items-center fw-semibold">
                                <i class="fa-solid fa-tv"></i>
                                <span class="ms-2">CHANNELS</span>
                            </div>
                        </a>
                        <a id="focus_tag" href="/league"
                            class="list-group-item list-group-item-action text-center p-2 px-3 d-flex  align-items-center text-white text-lg-start bg-menu">
                            <div class="d-flex align-items-center fw-semibold">
                                <i class="fa-solid fa-globe"></i>
                                <span class="ms-2">MANAGE LEAGUE</span>
                            </div>
                        </a>
                        <a id="focus_tag" href="/notification"
                            class="list-group-item list-group-item-action text-center p-2 px-3 d-flex  align-items-center text-white text-lg-start bg-menu">
                            <div class="d-flex align-items-center fw-semibold">
                                <i class="fa-solid fa-bell"></i>
                                <span class="ms-2">NOTIFICATION</span>
                            </div>
                        </a>
                        <a id="focus_tag" href="/app_setting"
                            class="list-group-item list-group-item-action text-center p-2 px-3 d-flex  align-items-center text-white text-lg-start bg-menu">
                            <div class="d-flex align-items-center fw-semibold">
                                <i class="fa-solid fa-gear"></i>
                                <span class="ms-2">APP SETTING</span>
                            </div>
                        </a>
                        <a id="focus_tag" href="/slider-setting"
                            class="list-group-item list-group-item-action text-center p-2 px-3 d-flex  align-items-center text-white text-lg-start bg-menu">
                            <div class="d-flex align-items-center fw-semibold">
                                <i class="fa-solid fa-gears"></i>
                                <span class="ms-2">Slider Setting</span>
                            </div>
                        </a>
                        <a id="focus_tag" href="/password-change"
                            class="list-group-item list-group-item-action text-center p-2 px-3 d-flex  align-items-center text-white text-lg-start bg-menu">
                            <div class="d-flex align-items-center fw-semibold">
                                <i class="fa-solid fa-lock"></i>
                                <span class="ms-2">CHANGE PASSWORD</span>
                            </div>
                        </a>
                    </div>
                </div>
            </aside>
            <main class="col-lg-10 p-0">
                <nav class="navbar navbar-expand shadow-sm border-bottom p-0 py-1  px-3">
                    <div class="container-fluid">
                        <a class="float-start d-lg-none" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
                            aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon">
                                {{-- <i class="fas fa-navicon" style="color:#fff; font-size:28px;"></i> --}}
                            </span>
                        </a>
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
                <section class="scroll_page" style="overflow-x: hidden;">
                    @yield('page')
                </section>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="/tagsinput/js/select2.min.js"></script>
    <script>
        AOS.init();
    </script>
    @yield('script')
</body>

</html>
