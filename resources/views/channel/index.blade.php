@php
    use Carbon\Carbon;
    use Illuminate\Support\Facades\Session;
@endphp
@extends('layouts.home')
@section('style')
    <style>
        .hover_menu_tag a:nth-child(8) {
            /* border-left: 3px solid #ff0505 !important; */
            border-radius: 5px;
            background: rgba(255, 255, 255, 0.251);
        }

        #SvgjsG1016 * {
            height: 100px;
        }
    </style>
@endsection
@section('page')
    <div class="row matchs_container g-2 my-2 px-4">
        <a href="/channel/create" class="col-lg-2 col-sm-6 col-12 col-desktop text-dark text-decoration-none ">
            <div class="shadow-sm p-0 border bg_ani rounded-4 bg-white h-100 d-flex align-items-center justify-content-center">
                <div class="p-4">
                    <div class="team-pair d-flex justify-content-around">
                        <div style="width: 3.8rem;" class="home d-flex flex-column align-items-center">
                            <i class="fa-solid fa-plus fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
        @foreach ($channels as $channel)
            <a href="/channel/{{ $channel->id }}/edit" class="col-lg-2 col-sm-6 col-12 col-desktop text-dark h-100">
                <div class="shadow-sm p-0 border bg_ani rounded-4 bg-white">
                    {{-- <div class="league_text fw-semibold d-flex w-100 justify-content-between">
                        <div>
                            <a class="btn btn-white rounded-0 p-0" href="/channel/{{ $channel->id }}/edit">
                                <span class="px-2">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </span>
                            </a>
                            <button onclick="NotiModal({{ $channel->id }})" class="btn btn-white p-0"
                                type="button" data-bs-toggle="modal" data-bs-target="#notiModel" style="border-start-end-radius: 1rem;">
                                <span class="px-2">
                                    <i class="fa-solid fa-bell"></i>
                                </span>
                            </button>
                        </div>
                    </div> --}}

                    <div class="p-4">
                        <div class="team-pair d-flex justify-content-around">
                            <div style="width: 3.8rem;" class="home d-flex flex-column align-items-center">
                                <img class="w-100 h-100" src="{{ $channel->channel_logo }}"
                                    alt="{{ $channel->channel_name }} Logo">
                                <span id="channel_name_{{ $channel->id }}"
                                    class="text-center fw-semibold text-nowrap team_name d-inline-block text-truncate"
                                    style="max-width: 150px;">{{ $channel->channel_name }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        @endforeach

        <div class="col-11">
            {{ $channels->links('layouts.bootstrap-5') }}
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
            let home_name = $("#channel_name_" + id).text();
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
