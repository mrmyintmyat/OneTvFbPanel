@php
    use Carbon\Carbon;
    use Illuminate\Support\Facades\Session;
@endphp
@extends('layouts.home')
@section('style')
    <style>
        /* #match_time {
                      position: relative;
                    }

                    #match_time input[type="datetime-local"] {
                      position: absolute;
                      top: 0;
                      left: 0;
                      width: 100%;
                      height: 100%;
                      opacity: 0;
                      cursor: pointer;
                    } */
    </style>
@endsection
@section('page')
    @if (!session('error'))
        <div class="card text-start px-lg-0 px-2  border-0" id="edit_from_container">
            <div class="card-body pe-0">
                <div class="border-bottom border-2 d-flex justify-content-between py-2 pe-5 align-items-center">
                    <h5 class="">Edit match</h5>
                    <button onclick="Delete_match({{ $match->id }})" class="btn btn-sm btn-danger ">DELETE MATCH</button>
                </div>
                <div class="d-flex mt-3 w-100">
                    <form method="post" action="/matches/{{ $match->id }}?match={{ $route_match }}"
                        class="w-100 row d-flex justify-content-between px-2 g-3" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        <div class="col-lg-4 col-12 px-lg-3">
                            <label for="match_time" class="form-label fw-semibold">MATCH TIME</label>
                            @php
                                $dateTime = Carbon::createFromTimestamp($match->match_time, Session::get('timezone'));
                                $match_time = $dateTime->format('Y-m-d H:i:s');
                            @endphp
                            <div class="">
                                <input id="match_time" type="datetime-local"
                                    class="@error('match_time') is-invalid @enderror m-0" name="match_time"
                                    value="{{ $match_time }}" required autocomplete="match_time">

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
                                        <option value="{{ $league->name }}" data-logo="{{ $league->logo }}" {{ $match->league->name == $league->name ? 'selected' : '' }}>
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

                        <div class="col-lg-4 col-12 px-lg-3">
                            <label for="match_status" class="form-label fw-semibold">MATCH STATUS</label>

                            <div class="">
                                <select id="match_status" name="match_status"
                                    class="form-select  @error('match_status') is-invalid @enderror m-0 border-0"
                                    aria-label="Default select example" autocomplete="match_status">
                                    <option value="" disabled selected>Select Match Status</option>
                                    <optgroup class="ms-3 collapse show" id="collapseExample">
                                        <option value="Match" {{ $match->match_status == 'Match' ? 'selected' : '' }}>
                                            Match
                                        </option>
                                        <option value="Live" {{ $match->match_status == 'Live' ? 'selected' : '' }}>
                                            Live Match
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
                                        value="{{ $match->home_team_name }}" required autocomplete="home_team_name"
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
                                                <input id="home_team_logo" type="url"
                                                    class="form-control @error('home_team_logo') is-invalid @enderror m-0 custom-file-input"
                                                    name="home_team_logo" value="{{ $match->home_team_logo }}"
                                                    autocomplete="home_team_logo" accept="image/*" placeholder="LOGO URL"
                                                    required>
                                            </div>

                                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                                <li class="nav-item d-flex" role="presentation">
                                                    <button onclick="Change_input('home_team_logo', 'file')"
                                                        class="nav-link fw-normal" id="upload-tab" data-bs-toggle="tab"
                                                        data-bs-target="#upload-tab-pane" type="button" role="tab"
                                                        aria-controls="upload-tab-pane" aria-selected="true">Upload</button>
                                                    <button onclick="Change_input('home_team_logo', 'url')"
                                                        class="nav-link fw-normal active" id="url-tab"
                                                        data-bs-toggle="tab" data-bs-target="#url-tab-pane"
                                                        type="button" role="tab" aria-controls="url-tab-pane"
                                                        aria-selected="true">URL</button>
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
                                        value="{{ $match->home_team_score }}" autocomplete="home_team_score"
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
                                        value="{{ $match->away_team_name }}" required autocomplete="away_team_name"
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
                                                <input id="away_team_logo" type="url"
                                                    class="form-control @error('away_team_logo') is-invalid @enderror m-0 custom-file-input"
                                                    name="away_team_logo" value="{{ $match->away_team_logo }}"
                                                    autocomplete="away_team_logo" accept="image/*" placeholder="LOGO URL"
                                                    required>
                                            </div>

                                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                                <li class="nav-item d-flex" role="presentation">
                                                    <button onclick="Change_input('away_team_logo', 'file')"
                                                        class="nav-link fw-normal" id="upload-tab" data-bs-toggle="tab"
                                                        data-bs-target="#upload-tab-pane" type="button" role="tab"
                                                        aria-controls="upload-tab-pane"
                                                        aria-selected="true">Upload</button>
                                                    <button onclick="Change_input('away_team_logo', 'url')"
                                                        class="nav-link fw-normal active" id="url-tab"
                                                        data-bs-toggle="tab" data-bs-target="#url-tab-pane"
                                                        type="button" role="tab" aria-controls="url-tab-pane"
                                                        aria-selected="true">URL</button>
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
                                        value="{{ $match->away_team_score }}" autocomplete="away_team_score"
                                        placeholder="Away team score">

                                    @error('away_team_score')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        @php
                            $servers = json_decode($match->servers, true);
                        @endphp

                        <div class="p-lg-3 m-0 pt-lg-0">
                            <ul class="nav nav-tabs mb-2" id="myTab" role="tablist">
                                <li id="server-btns-container" class="nav-item d-flex flex-row"
                                    style="overflow-x: auto; overflow-y: hidden;" role="presentation">
                                    @foreach ($servers as $index => $server)
                                        <button
                                            class="server-btns nav-link  {{ $index === 0 ? 'active' : '' }} text-nowrap"
                                            id="server-{{ $index + 1 }}-tab" data-bs-toggle="tab"
                                            data-bs-target="#server-{{ $index + 1 }}" type="button" role="tab"
                                            aria-controls="server-{{ $index + 1 }}"
                                            aria-selected="{{ $index === 0 ? 'true' : 'false' }}">
                                            {{ $server['name'] }}<span class="delete-server-btn">&times;</span>
                                        </button>
                                    @endforeach

                                </li>
                                <button id="add-server-btn" type="button" class="px-3 btn bg-menu">
                                    <i class="fa-solid fa-plus text-white"></i>
                                </button>
                            </ul>
                            <div class="tab-content" id="pills-tabContent">
                                @foreach ($servers as $index => $server)
                                    <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}"
                                        id="server-{{ $index + 1 }}" role="tabpanel"
                                        aria-labelledby="server-{{ $index + 1 }}-tab" tabindex="0">
                                        <div class="row ">
                                            <div class="">
                                                <input required id="server_name" type="text"
                                                    class=" @error('server_name') is-invalid @enderror "
                                                    name="server_name[]" value="{{ $server['name'] }}"
                                                    autocomplete="server_name" placeholder="name">
                                                @error('server_name')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row ">
                                            <div class="">
                                                <input id="server_url" type="url"
                                                    class=" @error('server_url') is-invalid @enderror "
                                                    name="server_url[]" value="{{ $server['url'] }}"
                                                    autocomplete="server_url" placeholder="URL">
                                                @error('server_url')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row ">
                                            <div class="">
                                                <input id="server_referer" type="url"
                                                    class=" @error('server_referer') is-invalid @enderror "
                                                    name="server_referer[]" value="{{ $server['referer'] }}"
                                                    autocomplete="server_referer" placeholder="REFERER">
                                                @error('server_referer')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="m-0">

                            <div class="row mb-0 col-12 ms-1 p-0">
                                <div class="w-100 p-0">
                                    <button type="submit" class="btn py-2 bg-menu  w-100 text-white">
                                        {{ __('UPDATE') }}
                                    </button>
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    @else
        <h3 class="mt-3">Match Not found</h3>
    @endif
@endsection
@section('script')
    <script src="/js/plus_server.js?v=<?php echo time(); ?>"></script>
    <script>
        $('#match_status').on('change', function() {
            if ($(this).val() === 'Live') {
                $('#server_url, #server_referer').prop('required', true);
            } else {
                $('#server_url, #server_referer').prop('required', false);
            }
        });

        function Change_input(id, type) {
            // Get the input element using the provided id
            const inputElement = document.getElementById(id);

            // Change the input type based on the provided type
            if (type === 'file') {
                inputElement.type = 'file';
            } else if (type === 'url') {
                inputElement.type = 'url';
            }
        }

        $('#match_time').on('click', function() {
            // Trigger a click event on the input to show the date and time picker
            $(this).prev('input[type="datetime-local"]').click();
        });

        function Delete_match(id) {
            if (confirm("Are you sure?")) {
                axios.delete('/matches/' + id)
                    .then(function(response) {
                        var toast = document.getElementById('toast');
                        toast.classList.add('show');
                    })
                    .catch(function(error) {
                        if (error.response && error.response.data && error.response.data.error) {
                            var toast = document.getElementById('error_toast');
                            toast.classList.add('show');
                        } else {
                            alert('An error occurred while deleting the items.');
                        }
                    });
            }
        }

        $(".delete-server-btn").click(function(event) {
            const tabId = $(event.target).parent().attr("aria-controls");
            console.log(tabId)
            $(event.target).parent().remove();
            $(`#${tabId}`).remove();
        });

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
