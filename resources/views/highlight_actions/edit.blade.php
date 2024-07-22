@php
    use Carbon\Carbon;
@endphp
@extends('layouts.home')
@section('page')
@if (!session('error'))
    <div class="card text-start border-0 rounded-0 px-lg-0 px-2 mb-3" id="edit_from_container">
        <div class="card-body pe-0">
            <div class="border-bottom border-2 d-flex justify-content-between py-2 pe-5 align-items-center">
                <h5 class="">EDIT MATCH</h5>
                <button onclick="Delete_match({{$match->id}})" class="btn btn-sm btn-danger rounded-0">DELETE MATCH</button>
            </div>
                <div class="d-flex mt-3 w-100">
                    <form method="post" action="/highlights/{{ $match->id }}"
                        class="w-100 row d-flex justify-content-between px-2 g-3" enctype="multipart/form-data">
                        @csrf @method('PUT')

                        <div class="p-3">
                            <div class="row mb-3">
                                <label for="match_time" class="form-label fw-semibold">MATCH TIME</label>
                                @php
                                $time = $match->match_time;
                                $match_time = $time * 1000;
                                @endphp
                                @php
                                    $timestampSeconds = $match_time / 1000;

                                    $dateTime = Carbon::createFromTimestamp($timestampSeconds);
                                    $match_time = $dateTime->format('Y-m-d H:i:s');
                                @endphp
                                <div class="">
                                    <input id="match_time" type="datetime-local"
                                        class="form-control @error('match_time') is-invalid @enderror rounded-0"
                                        name="match_time" value="{{ $match_time }}" required
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
                                    <select id="league" name="league" class="form-select rounded-0"
                                        aria-label="Default select example">
                                        <div class="ms-3 collapse show" id="collapseExample" style="">
                                            <option value="{{ $match->league_name }},{{ $match->league_logo }}" selected>
                                                <span class="ms-2 text-truncate">{{ $match->league_name }}</span>
                                            </option>
                                            @foreach ($leagues as $league)
                                                <option value="{{ $league->name }},{{ $league->logo }}"  {{ $match->league_name ==  $league->name ? 'selected' : '' }}>
                                                    <span class="ms-2 text-truncate">{{ $league->name }}</span>
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

                            <div class="row">
                                <label for="match_status" class="form-label fw-semibold">MATCH STATUS</label>

                                <div class="">
                                    <input id="match_status" type="text"
                                        class="form-control @error('match_status') is-invalid @enderror rounded-0"
                                        name="match_status" value="{{ $match->match_status }}"
                                        autocomplete="match_status" disabled>

                                    @error('match_status')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 p-3 mt-0">
                            <div class="row mb-3 mt-0">
                                <label for="home_team_name" class="form-label fw-semibold">HOME TEAM NAME</label>

                                <div class="">
                                    <input id="home_team_name" type="text"
                                        class="form-control @error('home_team_name') is-invalid @enderror rounded-0"
                                        name="home_team_name" value="{{ $match->home_team_name }}" required
                                        autocomplete="home_team_name" autofocus>

                                    @error('home_team_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3 mt-0">
                                <label for="home_team_logo" class="form-label fw-semibold d-flex align-items-center">HOME
                                    TEAM LOGO <ul class="nav nav-tabs" id="myTab" role="tablist">
                                        <li class="nav-item d-flex" role="presentation">
                                            <button onclick="Change_input('home_team_logo', 'file')"
                                                class="nav-link fw-normal ms-2" id="upload-tab" data-bs-toggle="tab"
                                                data-bs-target="#upload-tab-pane" type="button" role="tab"
                                                aria-controls="upload-tab-pane" aria-selected="true">Upload Logo</button>
                                            <button onclick="Change_input('home_team_logo', 'url')"
                                                class="nav-link fw-normal active" id="url-tab" data-bs-toggle="tab"
                                                data-bs-target="#url-tab-pane" type="button" role="tab"
                                                aria-controls="url-tab-pane" aria-selected="true">URL</button>
                                        </li>
                                    </ul>
                                </label>
                                <div>
                                    <input id="home_team_logo" type="text"
                                        class="form-control @error('home_team_logo') is-invalid @enderror rounded-0"
                                        name="home_team_logo" value="{{ $match->home_team_logo }}"
                                        autocomplete="home_team_logo" accept="image/*" required>

                                    @error('home_team_logo')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="home_team_score" class="form-label fw-semibold">HOME TEAM SCORE</label>

                                <div class="">
                                    <input id="home_team_score" type="text"
                                        class="form-control @error('home_team_score') is-invalid @enderror rounded-0"
                                        name="home_team_score" value="{{ $match->home_team_score }}"
                                        autocomplete="home_team_score">

                                    @error('home_team_score')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 p-3 mt-0">
                            <div class="row mb-3">
                                <label for="away_team_name" class="form-label fw-semibold">AWAY TEAM NAME</label>

                                <div class="">
                                    <input id="away_team_name" type="text"
                                        class="form-control @error('away_team_name') is-invalid @enderror rounded-0"
                                        name="away_team_name"
                                        value="
                                        {{ $match->away_team_name }}"
                                        required autocomplete="away_team_name">

                                    @error('away_team_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="away_team_logo" class="form-label fw-semibold d-flex align-items-center">AWAY
                                    TEAM LOGO
                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                        <li class="nav-item d-flex" role="presentation">
                                            <button onclick="Change_input('away_team_logo', 'file')"
                                                class="nav-link fw-normal ms-2" id="upload-tab" data-bs-toggle="tab"
                                                data-bs-target="#upload-tab-pane" type="button" role="tab"
                                                aria-controls="upload-tab-pane" aria-selected="true">Upload Logo</button>
                                            <button onclick="Change_input('away_team_logo', 'url')"
                                                class="nav-link fw-normal active" id="url-tab" data-bs-toggle="tab"
                                                data-bs-target="#url-tab-pane" type="button" role="tab"
                                                aria-controls="url-tab-pane" aria-selected="true">URL</button>
                                        </li>
                                    </ul>
                                </label>
                                <div class="">
                                    <input id="away_team_logo" type="text"
                                        class="form-control @error('away_team_logo') is-invalid @enderror rounded-0"
                                        name="away_team_logo" value="{{ $match->away_team_logo }}" required
                                        autocomplete="away_team_logo" accept="image/*">

                                    @error('away_team_logo')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="away_team_score" class="form-label fw-semibold">AWAY TEAM SCORE</label>

                                <div class="">
                                    <input id="away_team_score" type="text"
                                        class="form-control @error('away_team_score') is-invalid @enderror rounded-0"
                                        name="away_team_score" value="{{ $match->away_team_score }}"
                                        autocomplete="away_team_score">

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

                        <div class="shadow-sm p-3">
                            <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
                                <li id="server-btns-container" class="nav-item d-flex flex-row" style="overflow-x: auto;" role="presentation">
                                    @foreach ($servers as $index => $server)
                                        <button
                                            class="server-btns nav-link rounded-0 {{ $index === 0 ? 'active' : '' }} text-nowrap"
                                            id="server-{{ $index + 1 }}-tab" data-bs-toggle="tab"
                                            data-bs-target="#server-{{ $index + 1 }}" type="button" role="tab"
                                            aria-controls="server-{{ $index + 1 }}"
                                            aria-selected="{{ $index === 0 ? 'true' : 'false' }}">
                                            Server {{ $index + 1 }}<span class="delete-server-btn">&times;</span>
                                        </button>
                                    @endforeach

                                </li>
                                <button id="add-server-btn" type="button" class="px-3 btn btn-info rounded-0">+</button>
                            </ul>
                            <div class="tab-content" id="pills-tabContent">
                                @foreach ($servers as $index => $server)
                                    <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}"
                                        id="server-{{ $index + 1 }}" role="tabpanel"
                                        aria-labelledby="server-{{ $index + 1 }}-tab" tabindex="0">
                                        <div class="row mb-3">
                                            <label for="server_url" class="form-label fw-semibold">URL</label>
                                            <div class="">
                                                <input id="server_url" type="url"
                                                    class="form-control @error('server_url') is-invalid @enderror rounded-0"
                                                    name="server_url[]" value="{{ $server['url'] }}"
                                                    autocomplete="server_url">
                                                @error('server_url')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="server_referer" class="form-label fw-semibold">REFERER</label>
                                            <div class="">
                                                <input id="server_referer" type="url"
                                                    class="form-control @error('server_referer') is-invalid @enderror rounded-0"
                                                    name="server_referer[]" value="{{ $server['referer'] }}"
                                                    autocomplete="server_referer">
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


                        <div>

                            <div class="row mb-0 col-12 mt-3 ms-1 p-0">
                                <div class="w-100 p-0">
                                    <button type="submit" class="btn py-2 bg-menu w-100 text-white">
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
    <script src="/js/plus_server.js"></script>
    <script>
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

        function Delete_match(id) {
            if (confirm("Are you sure?")) {
                axios.delete('/highlights/' + id)
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
    </script>
@endsection
