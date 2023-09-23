@extends('layouts.home')
@section('style')
    <style>
        .hover_menu_tag a:nth-child(2) {
            border-left: 3px solid #ff0505 !important;
            background: rgba(255, 255, 255, 0.251);
        }

        #SvgjsG1016 * {
            height: 100px;
        }
    </style>
@endsection
@section('page')
    <div class="card text-start border-0 px-lg-0 px-2 mb-3">
        <div class="card-body pe-0 ">
            @if (!session('error'))
                <div class="d-flex w-100 ">
                    <form method="post" action="/matches" class="w-100 row d-flex justify-content-between px-2 g-3"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="shadow-sm p-3">
                            <div class="row">
                                <label for="match_time" class="form-label fw-semibold">MATCH TIME</label>

                                <div class="">
                                    <input id="match_time" type="datetime-local"
                                        class=" @error('match_time') is-invalid @enderror"
                                        name="match_time" value="{{ old('match_time') }}" required
                                        autocomplete="match_time">


                                    @error('match_time')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="league" class="form-label fw-semibold">LEAGUE NAME</label>
                                <div>
                                    <select id="league" name="league" class=""
                                        aria-label="Default select example">
                                        <option selected>Open this select menu</option>
                                        <div class="ms-3 collapse show" id="collapseExample" style="">
                                            @foreach ($leagues as $league)
                                            <option value="{{ $league->name }},{{ $league->logo }}">
                                                <span class="ms-2 text-truncate">
                                                    {{ $league->name }}</span>
                                            </option>
                                        @endforeach
                                        </div>
                                    </select>
                                </div>
                                @error('league_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="row mb-3">
                                <label for="match_status" class="form-label fw-semibold">MATCH STATUS</label>

                                <div class="">
                                    {{-- <input id="match_status" type="text"
                                        class=" @error('match_status') is-invalid @enderror"
                                        name="match_status" value="{{ old('match_status') }}" required
                                        autocomplete="match_status"> --}}

                                    <select id="match_status" name="match_status"
                                        class= @error('match_status') is-invalid @enderror"
                                        aria-label="Default select example" autocomplete="match_status">
                                        <option value="" disabled selected>Select Match Status</option>
                                        <optgroup class="ms-3 collapse show" id="collapseExample">
                                            <option value="Match" {{ old('match_status') == 'Match' ? 'selected' : '' }}>
                                                Match
                                            </option>
                                            <option value="Live" {{ old('match_status') == 'Live' ? 'selected' : '' }}>
                                                Live Match
                                            </option>
                                            <option value="Highlight"
                                                {{ old('match_status') == 'Highlight' ? 'selected' : '' }}>
                                                Highlight Match
                                            </option>
                                        </optgroup>
                                    </select>

                                    @error('match_status')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 shadow-sm p-3">
                            <div class="row mb-3">
                                {{-- <label for="home_team_name" class="form-label fw-semibold">HOME TEAM NAME</label> --}}

                                <div class="">
                                    <input id="home_team_name" type="text"
                                        class=" @error('home_team_name') is-invalid @enderror"
                                        name="home_team_name" value="{{ old('home_team_name') }}" required
                                        autocomplete="home_team_name" autofocus placeholder="HOME TEAM NAME">

                                    @error('home_team_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row shadow-sm mb-3 mx-1">
                                <label for="home_team_logo" class="form-label fw-semibold d-flex align-items-center">HOME
                                    TEAM LOGO <ul class="nav nav-tabs" id="myTab" role="tablist">
                                        <li class="nav-item d-flex" role="presentation">
                                            <button onclick="Change_input('home_team_logo', 'file')"
                                                class="nav-link fw-normal active ms-2" id="upload-tab" data-bs-toggle="tab"
                                                data-bs-target="#upload-tab-pane" type="button" role="tab"
                                                aria-controls="upload-tab-pane" aria-selected="true">Upload Logo</button>
                                            <button onclick="Change_input('home_team_logo', 'text')"
                                                class="nav-link fw-normal" id="url-tab" data-bs-toggle="tab"
                                                data-bs-target="#url-tab-pane" type="button" role="tab"
                                                aria-controls="url-tab-pane" aria-selected="true">URL</button>
                                        </li>
                                    </ul>
                                </label>
                                <div>
                                    <input id="home_team_logo" type="file"
                                        class="form-control @error('home_team_logo') is-invalid @enderror"
                                        name="home_team_logo" value="{{ old('home_team_logo') }}"
                                        autocomplete="home_team_logo" accept="image/*" placeholder="LOGO URL" required>

                                    @error('home_team_logo')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="">
                                    <input id="home_team_score" type="text"
                                        class=" @error('home_team_score') is-invalid @enderror"
                                        name="home_team_score" value="{{ old('home_team_score') }}"
                                        autocomplete="home_team_score" placeholder="HOME TEAM SCORE">

                                    @error('home_team_score')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 shadow-sm p-3">
                            <div class="row mb-3">
                                <div class="">
                                    <input id="away_team_name" type="text"
                                        class=" @error('away_team_name') is-invalid @enderror"
                                        name="away_team_name" value="{{ old('away_team_name') }}" required
                                        autocomplete="away_team_name" placeholder="AWAY TEAM NAME">

                                    @error('away_team_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row shadow-sm mb-3 mx-1 mb-3">
                                <label for="away_team_logo" class="form-label fw-semibold d-flex align-items-center">AWAY
                                    TEAM LOGO
                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                        <li class="nav-item d-flex" role="presentation">
                                            <button onclick="Change_input('away_team_logo', 'file')"
                                                class="nav-link fw-normal active ms-2" id="upload-tab" data-bs-toggle="tab"
                                                data-bs-target="#upload-tab-pane" type="button" role="tab"
                                                aria-controls="upload-tab-pane" aria-selected="true">Upload Logo</button>
                                            <button onclick="Change_input('away_team_logo', 'text')"
                                                class="nav-link fw-normal" id="url-tab" data-bs-toggle="tab"
                                                data-bs-target="#url-tab-pane" type="button" role="tab"
                                                aria-controls="url-tab-pane" aria-selected="true">URL</button>
                                        </li>
                                    </ul>
                                </label>
                                <div class="">
                                    <input id="away_team_logo" type="file"
                                        class="form-control @error('away_team_logo') is-invalid @enderror"
                                        name="away_team_logo" value="{{ old('away_team_logo') }}" required
                                        autocomplete="away_team_logo" accept="image/*" placeholder="LOGO URL">

                                    @error('away_team_logo')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="">
                                    <input id="away_team_score" type="text"
                                        class=" @error('away_team_score') is-invalid @enderror"
                                        name="away_team_score" value="{{ old('away_team_score') }}"
                                        autocomplete="away_team_score" placeholder="AWAY TEAM SCORE">

                                    @error('away_team_score')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="shadow-sm p-3">
                            <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
                                <li id="server-btns-container" class="nav-item d-flex flex-row" role="presentation">
                                    <button class="server-btns nav-link  active text-nowrap" id="server-1-tab"
                                        data-bs-toggle="tab" data-bs-target="#server-1" type="button" role="tab"
                                        aria-controls="server-1-tab-pane" aria-selected="true">Server 1</button>
                                </li>
                                <button id="add-server-btn" type="button" class="px-3 btn btn-info">+</button>
                            </ul>
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="server-1" role="tabpanel"
                                    aria-labelledby="server-1-tab" tabindex="0">

                                    <div class="row mb-3">
                                        <div class="">
                                            <input id="server_url" type="text"
                                                class=" @error('server_url') is-invalid @enderror"
                                                name="server_url[]" value="{{ old('server_url.0') }}"
                                                autocomplete="server_url" placeholder="URL">

                                            @error('server_url')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">

                                        <div class="">
                                            <input id="server_referer" type="text"
                                                class=" @error('server_referer') is-invalid @enderror"
                                                name="server_referer[]" value="{{ old('server_referer.0') }}"
                                                autocomplete="server_referer" placeholder="REFERER">

                                            @error('server_referer')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                            <div class="row mb-0 col-12 mt-3 ms-1 p-0">
                                <div class="w-100 p-0">
                                    <button type="submit" class="btn py-2 bg-menu  w-100 text-white">
                                        {{ __('CREATE') }}
                                    </button>
                                </div>
                            </div>
                    </form>
                </div>
            @else
                <h3>User Not found</h3>
            @endif

        </div>
    </div>
@endsection
@section('script')
    <script src="/js/plus_server.js"></script>
    <script>
       $('#match_status').on('change', function() {
            if ($(this).val() === 'Live' || $(this).val() === 'Highlight') {
                $('#away_team_score, #home_team_score, #server_url, #server_referer').prop('required', true);
            } else {
                $('#away_team_score, #home_team_score, #server_url, #server_referer').prop('required', false);
            }
        });
        function Change_input(id, type) {
            // Get the input element using the provided id
            const inputElement = document.getElementById(id);

            // Change the input type based on the provided type
            if (type === 'file') {
                inputElement.type = 'file';
            } else if (type === 'text') {
                inputElement.type = 'text';
            }
        }
    </script>
@endsection
