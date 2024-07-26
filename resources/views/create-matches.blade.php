@extends('layouts.home')
@section('style')
    <style>
        .hover_menu_tag a:nth-child() {
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
    <div class="card text-start border-0 px-lg-0 px-2 " style="background: #ffffff00;">
        <div class="card-body pe-0 ">
            @if (!session('error'))
                <div class="d-flex w-100 ">
                    <form method="post" action="/matches" class="w-100 row d-flex justify-content-between px-2 g-3"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="col-lg-4 col-12 px-lg-3">
                            <label for="match_time" class="form-label fw-semibold">Match time</label>

                            <div class="">
                                <input id="match_time" type="datetime-local"
                                    class=" @error('match_time') is-invalid @enderror" name="match_time"
                                    value="{{ old('match_time') }}" required autocomplete="match_time">


                                @error('match_time')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-4 col-12 px-lg-3">
                            <label for="league" class="form-label fw-semibold">League </label>
                            <div>
                                <select id="league" name="league" multiple="multiple">
                                    @foreach ($leagues as $league)
                                        <option value="{{ $league->id }}" data-logo="{{ $league->logo }}">
                                            {{ $league->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('league_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-lg-4 col-12 px-lg-3 ">
                            <label for="match_status" class="form-label fw-semibold">Match status</label>

                            <div class="">
                                <select id="match_status" name="match_status"
                                    class=" @error('match_status') is-invalid @enderror" aria-label="Default select example"
                                    autocomplete="match_status">
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

                        <div class="col-lg-6 p-lg-3 m-0">
                            <div class="row ">
                                <label for="home_team_name" class="form-label fw-semibold d-flex align-items-center">
                                    Home team info
                                </label>

                                <div class="">
                                    <input id="home_team_name" type="text"
                                        class=" @error('home_team_name') is-invalid @enderror" name="home_team_name"
                                        value="{{ old('home_team_name') }}" required autocomplete="home_team_name"
                                        autofocus placeholder="Home team name">

                                    @error('home_team_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div>
                                    <div class="p-0">
                                        <div class="d-flex mb-2 team_logo_container">
                                            <div class="custom-file">
                                                <input id="home_team_logo" type="file"
                                                    class="form-control @error('home_team_logo') is-invalid @enderror m-0 custom-file-input"
                                                    name="home_team_logo" value="{{ old('home_team_logo') }}"
                                                    autocomplete="home_team_logo" accept="image/*" placeholder="LOGO URL"
                                                    required>
                                                <label class="custom-file-label" for="home_team_logo">Choose file</label>
                                            </div>

                                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                                <li class="nav-item d-flex" role="presentation">
                                                    <button onclick="Change_input('home_team_logo', 'file')"
                                                        class="nav-link fw-normal active" id="upload-tab"
                                                        data-bs-toggle="tab" data-bs-target="#upload-tab-pane"
                                                        type="button" role="tab" aria-controls="upload-tab-pane"
                                                        aria-selected="true">Upload</button>
                                                    <button onclick="Change_input('home_team_logo', 'url')"
                                                        class="nav-link fw-normal" id="url-tab" data-bs-toggle="tab"
                                                        data-bs-target="#url-tab-pane" type="button" role="tab"
                                                        aria-controls="url-tab-pane" aria-selected="true">URL</button>
                                                </li>
                                            </ul>
                                        </div>

                                        @error('home_team_logo')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row ">
                                <div class="">
                                    <input id="home_team_score" type="text"
                                        class=" @error('home_team_score') is-invalid @enderror" name="home_team_score"
                                        value="{{ old('home_team_score') }}" autocomplete="home_team_score"
                                        placeholder="Home team score">

                                    @error('home_team_score')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 p-lg-3 m-0">
                            <div class="row ">
                                <label for="away_team_name" class="form-label fw-semibold d-flex align-items-center">
                                    Away team info
                                </label>

                                <div class="">
                                    <input id="away_team_name" type="text"
                                        class=" @error('away_team_name') is-invalid @enderror" name="away_team_name"
                                        value="{{ old('away_team_name') }}" required autocomplete="away_team_name"
                                        autofocus placeholder="Away team name">

                                    @error('away_team_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div>
                                    <div class="p-0">
                                        <div class="d-flex mb-2 team_logo_container">
                                            <div class="custom-file">
                                                <input id="away_team_logo" type="file"
                                                    class="form-control @error('away_team_logo') is-invalid @enderror m-0 custom-file-input"
                                                    name="away_team_logo" value="{{ old('away_team_logo') }}"
                                                    autocomplete="away_team_logo" accept="image/*" placeholder="LOGO URL"
                                                    required>
                                                <label class="custom-file-label" for="away_team_logo">Choose file</label>
                                            </div>

                                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                                <li class="nav-item d-flex" role="presentation">
                                                    <button onclick="Change_input('away_team_logo', 'file')"
                                                        class="nav-link fw-normal active" id="upload-tab"
                                                        data-bs-toggle="tab" data-bs-target="#upload-tab-pane"
                                                        type="button" role="tab" aria-controls="upload-tab-pane"
                                                        aria-selected="true">Upload</button>
                                                    <button onclick="Change_input('away_team_logo', 'url')"
                                                        class="nav-link fw-normal" id="url-tab" data-bs-toggle="tab"
                                                        data-bs-target="#url-tab-pane" type="button" role="tab"
                                                        aria-controls="url-tab-pane" aria-selected="true">URL</button>
                                                </li>
                                            </ul>
                                        </div>

                                        @error('away_team_logo')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row ">
                                <div class="">
                                    <input id="away_team_score" type="text"
                                        class=" @error('away_team_score') is-invalid @enderror" name="away_team_score"
                                        value="{{ old('away_team_score') }}" autocomplete="away_team_score"
                                        placeholder="Away team score">

                                    @error('away_team_score')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="m-0 p-lg-3">
                            <ul class="nav nav-tabs mb-2" id="myTab" role="tablist">
                                <li id="server-btns-container" class="nav-item d-flex flex-row" role="presentation" style="overflow-x: auto; overflow-y: hidden;">
                                    <button class="server-btns nav-link  active text-nowrap" id="server-1-tab"
                                        data-bs-toggle="tab" data-bs-target="#server-1" type="button" role="tab"
                                        aria-controls="server-1-tab-pane" aria-selected="true" style="border-radius: 10px 0px 0px 10px;">Server 1</button>
                                </li>
                                <button id="add-server-btn" type="button" class="px-3 btn bg-menu text-white" style="border-radius: 0px 10px 10px 0px;">
                                    <i class="fa-solid fa-plus text-white"></i>
                                </button>
                            </ul>
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="server-1" role="tabpanel"
                                    aria-labelledby="server-1-tab" tabindex="0">
                                    <div class="row">
                                        <div class="">
                                            <input required id="server_name" type="text" class=""
                                                name="server_name[]" value="" autocomplete="server_name"
                                                placeholder="name">
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="">
                                            <input id="server_url" type="url"
                                                class=" @error('server_url') is-invalid @enderror" name="server_url[]"
                                                value="{{ old('server_url.0') }}" autocomplete="server_url"
                                                placeholder="url">

                                            @error('server_url')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="">
                                            <input id="server_referer" type="url"
                                                class=" @error('server_referer') is-invalid @enderror"
                                                name="server_referer[]" value="{{ old('server_referer.0') }}"
                                                autocomplete="server_referer" placeholder="referer">

                                            @error('server_referer')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="">
                                        <select id="server_type" name="server_type[]"
                                            class=" @error('server_type') is-invalid @enderror" aria-label="Default select example"
                                            autocomplete="server_type" required>
                                            <option value="" disabled selected>Select Type</option>
                                            <optgroup class="ms-3 collapse show" id="collapseExample">
                                                <option value="Direct Player" {{ old('server_type.0') == 'Direct Player' ? 'selected' : '' }}>
                                                    Direct Player
                                                </option>
                                                <option value="Embed Player" {{ old('server_type.0') == 'Embed Player' ? 'selected' : '' }}>
                                                    Embed Player
                                                </option>
                                            </optgroup>
                                        </select>

                                        @error('server_type')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="m-0 d-flex justify-content-center col-12 p-0 px-3">
                            <div class="w-100">
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
        $(document).ready(function() {
            // Add the beforeunload event listener inside the document ready function
            window.addEventListener('beforeunload', function(event) {
                // Cancel the event as returning a non-empty string will prompt the user with a confirmation dialog
                event.preventDefault();
                // Set the message to display in the confirmation dialog
                event.returnValue =
                    'Are you sure you want to leave this page? Your changes may not be saved.';
            });

            // Your other JavaScript code here...
        });

        $('#match_status').on('change', function() {
            if ($(this).val() === 'Live' || $(this).val() === 'Highlight') {
                $('#away_team_score, #home_team_score, #server_url, #server_referer').prop('required', true);
            } else {
                $('#away_team_score, #home_team_score, #server_url, #server_referer').prop('required', false);
            }
        });

        function Change_input(id, type) {
            const inputElement = document.getElementById(id);
            const labelElement = inputElement.nextElementSibling; // Assuming the label is immediately after the input

            if (type === 'file') {
                inputElement.type = 'file';
                if (!labelElement || labelElement.tagName.toLowerCase() !== 'label') {
                    const newLabel = document.createElement('label');
                    newLabel.className = 'custom-file-label';
                    newLabel.setAttribute('for', id);
                    newLabel.textContent = 'Choose file';
                    inputElement.parentNode.insertBefore(newLabel, inputElement.nextSibling);
                }
            } else if (type === 'url') {
                inputElement.type = 'url';
                if (labelElement && labelElement.tagName.toLowerCase() === 'label') {
                    labelElement.remove();
                }
            }
        }


        $(document).ready(function() {
            $('#league').select2({
                tags: true, // Disable the creation of new tags
                tokenSeparators: [',', ' '], // Allow commas and spaces as separators
                width: '100%',
                maximumSelectionLength: 1,
                templateResult: formatState,
                templateSelection: formatState
            });
        });

        function formatState(state) {
            if (!state.id) {
                return state.text;
            }

            var $state = $(
                '<span><img style="width: 1.5rem; height: auto;" src="' + $(state.element).data('logo') +
                '" class="img-flag rounded-circle" /> ' + state.text + '</span>'
            );

            return $state;
        }

        document.querySelector('.custom-file-input').addEventListener('change', function(e) {
            var fileName = document.getElementById("home_team_logo").files[0].name;
            var nextSibling = e.target.nextElementSibling;
            nextSibling.innerText = fileName;
        });

        document.querySelector('#away_team_logo').addEventListener('change', function(e) {
            var fileName = document.getElementById("away_team_logo").files[0].name;
            var nextSibling = e.target.nextElementSibling;
            nextSibling.innerText = fileName;
        });
    </script>
@endsection
