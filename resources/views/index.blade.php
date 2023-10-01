@php
    use Carbon\Carbon;
    use Illuminate\Support\Facades\Session;
@endphp
@extends('layouts.home')
@section('style')
    <style>
        .hover_menu_tag a:nth-child(1) {
            border-left: 3px solid #ff0505 !important;
            background: rgba(255, 255, 255, 0.251);
        }

        #SvgjsG1016 * {
            height: 100px;
        }
    </style>
@endsection
@section('page')
    <div class="row matchs_container g-2 my-2 px-4">
        @foreach ($matches as $match)
            <div class="col-lg-4 col-sm-6 col-12 col-desktop text-dark">
                <div class="shadow-sm p-0 border bg_ani">
                    <div class="league_text fw-semibold d-flex w-100 justify-content-between">
                        <div class="border border-top-0 py-1 bg-white">
                            <div class="p-2 py-0 text-nowrap d-flex">
                                {{-- <img class="me-1" style="width: 20px;" src="{{ $match->league_logo }}" alt=""
                                    sizes="" srcset=""> --}}
                                <span class="d-inline-block text-truncate" style="max-width: 230px;">
                                    {{ $match->league_name }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <a class="btn btn-light rounded-0 p-1 shadow" href="/matches/{{ $match->id }}/edit">
                                <span class="py-1 px-3">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </span>
                            </a>
                            <button onclick="NotiModal({{ $match->id }})" class="btn btn-light rounded-0 p-1 shadow"
                                type="button" data-bs-toggle="modal" data-bs-target="#notiModel">
                                <span class="py-1 px-3">
                                    <i class="fa-solid fa-bell"></i>
                                </span>
                            </button>
                        </div>
                    </div>

                    <div class="p-4">
                        <div class="team-pair d-flex justify-content-around">
                            <div style="width: 3.8rem;" class="home d-flex flex-column align-items-center">
                                <img class="w-100 h-100" src="{{ $match->home_team_logo }}"
                                    alt="{{ $match->home_team_name }} Logo">
                                <span id="home_team_name_{{ $match->id }}"
                                    class="text-center fw-semibold text-nowrap team_name mt-1 d-inline-block text-truncate"
                                    style="max-width: 150px;">{{ $match->home_team_name }}</span>
                            </div>
                            <div
                                class="date-time d-flex flex-column text-center justify-content-center text-center fw-semibold">
                                @if ($match->match_status == 'Live')
                                    <div class="btn btn-success px-3 py-1">Live</div>
                                @else
                                    <div
                                        class="date-time d-flex flex-column text-center justify-content-center text-center fw-semibold">
                                        @php
                                            $match_time = Carbon::createFromTimestamp($match->match_time, Session::get('timezone'));
                                        @endphp
                                        <span class="time-font_size">
                                            {{ $match_time->format('H:i') }}
                                        </span>
                                        <span class="date-font_size">
                                            {{ $match_time->format('Y-m-d') }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <div style="width: 3.8rem;" class="away d-flex flex-column align-items-center">
                                <img class="w-100 h-100" src="{{ $match->away_team_logo }}"
                                    alt="{{ $match->away_team_name }} Logo">
                                <span id="away_team_name_{{ $match->id }}"
                                    class="text-center fw-semibold text-nowrap team_name mt-1 d-inline-block text-truncate"
                                    style="max-width: 150px;">{{ $match->away_team_name }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="col-11">
            {{ $matches->links('layouts.bootstrap-5') }}
        </div>
    </div>

    <div class="modal fade" id="notiModel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    {{-- <h1 class="modal-title fs-5" id="notiModelLabel">Modal title</h1> --}}
                    <button type="button" id="closebtn" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mt-3 w-100 tab-pane fade show active" id="list-sdnoti-tab-pane" role="tabpanel"
                        aria-labelledby="list-sdnoti-tab" tabindex="0">
                        <form id="notiform" method="post" action="/notification"
                            class="w-100 row d-flex justify-content-around px-2 g-3" enctype="multipart/form-data">
                            @csrf
                            <div class="shadow-sm m-0 p-3">
                                <div class="row mb-3">
                                    <div class="">
                                        <input id="title" type="text"
                                            class="form-control @error('title') is-invalid @enderror rounded-0"
                                            name="title" value="{{ old('title') }}" required autocomplete="title"
                                            autofocus placeholder="Title">

                                        @error('title')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <textarea class="form-control @error('body') is-invalid @enderror" id="validationTextarea" placeholder="Body"
                                        name="body" required>Click here to watch.</textarea>
                                    @error('body')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="row mb-3">
                                    <div class="">
                                        <input id="img_url" type="url"
                                            class="form-control @error('img_url') is-invalid @enderror rounded-0"
                                            name="img_url" value="{{ old('img_url') }}" autocomplete="img_url" autofocus
                                            placeholder="Image Url">

                                        @error('img_url')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-0 col-12 mt-3 ms-1 p-0">
                                    <div class="w-100 p-0">
                                        <button type="submit" class="btn py-2 bg-menu  w-100 text-white">
                                            {{ __('SEND') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>

        function NotiModal(id) {
            let home_name = $("#home_team_name_" + id).text();
            let away_name = $("#away_team_name_" + id).text();
            let title_input = $("#title");

            let title_text = home_name + " vs " + away_name;
            title_input.val(title_text);
        }

        const form = document.getElementById('notiform');

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(form);

            const url = '/notification';
            axios.post(url, formData)
                .then(function(response) {
                    $('#closebtn').click();
                    $('#toast').addClass('show');
                })
                .catch(function(error) {
                    $('#closebtn').click();
                    $('#error_toast').addClass('show');
                });
        });
    </script>
@endsection
