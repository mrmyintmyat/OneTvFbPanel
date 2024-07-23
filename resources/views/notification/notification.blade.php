@extends('layouts.home')
@section('style')
    <style>
        .hover_menu_tag a:nth-child(5) {
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
    <div class="card text-start mt-lg-2 rounded-0 px-lg-0 px-2 mb-3">
        <div class="card-body pe-0">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="list-sdnoti-tab" data-bs-toggle="tab"
                        data-bs-target="#list-sdnoti-tab-pane" type="button" role="tab"
                        aria-controls="list-sdnoti-tab-pane" aria-selected="false">Send Notification</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="add-editkey-tab" data-bs-toggle="tab"
                        data-bs-target="#add-editkey-tab-pane" type="button" role="tab"
                        aria-controls="add-editkey-tab-pane" aria-selected="true">Edit Key</button>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="mt-3 w-100 tab-pane fade show active" id="list-sdnoti-tab-pane" role="tabpanel"
                    aria-labelledby="list-sdnoti-tab" tabindex="0">
                    <form method="post" action="/notification" class="w-100 row d-flex justify-content-around px-2 g-3"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="col-lg-5 shadow-sm p-3">
                            <div class="row mb-3">
                                <div class="">
                                    <input id="title" type="text"
                                        class="form-control @error('title') is-invalid @enderror rounded-0" name="title"
                                        value="{{ old('title') }}" required autocomplete="title" autofocus
                                        placeholder="Title">

                                    @error('title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <textarea class="form-control @error('body') is-invalid @enderror" id="validationTextarea"
                                    placeholder="Body" name="body" required></textarea>
                                @error('body')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="row mb-3">
                                <div class="">
                                    <input id="Url" type="url"
                                        class="form-control @error('Url') is-invalid @enderror rounded-0" name="Url"
                                        value="{{ old('Url') }}"  autocomplete="Url" autofocus placeholder="Url">

                                    @error('Url')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="">
                                    <input id="img_url" type="url"
                                        class="form-control @error('img_url') is-invalid @enderror rounded-0" name="img_url"
                                        value="{{ old('img_url') }}"  autocomplete="img_url" autofocus placeholder="Image Url">

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
                <div class="tab-pane fade" id="add-editkey-tab-pane" role="tabpanel" aria-labelledby="add-editkey-tab"
                    tabindex="0">
                    <form method="post" action="/edit-key" class="w-100 row d-flex justify-content-around px-2 g-3"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="col-lg-5 shadow-sm p-3">
                            <div class="row mb-3">
                                <div class="">
                                    <input id="key" type="text"
                                        class="form-control @error('key') is-invalid @enderror rounded-0" name="key"
                                        value="{{ $key }}" required autocomplete="key" autofocus
                                        placeholder="LEAGUE NAME">

                                    @error('key')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-0 col-12 mt-3 ms-1 p-0">
                                <div class="w-100 p-0">
                                    <button type="submit" class="btn py-2 bg-menu  w-100 text-white">
                                        {{ __('EDIT') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
