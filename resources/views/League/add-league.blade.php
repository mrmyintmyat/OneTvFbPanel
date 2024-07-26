@extends('layouts.home')
@section('style')
    <style>
        .hover_menu_tag a:nth-child(4) {
            /* border-left: 3px solid #ff0505 !important; */
            border-radius: 5px;
            background: rgba(255, 255, 255, 0.251);
        }

        #SvgjsG1016 * {
            height: 100px;
        }

        .tab-content>.active {
            display: inline-flex;
        }
    </style>
@endsection
@section('page')
    <div class="card text-start mt-lg-2 rounded-0 px-lg-0 px-2 mb-3">
        <div class="card-body pe-0">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="list-league-tab" data-bs-toggle="tab"
                        data-bs-target="#list-league-tab-pane" type="button" role="tab"
                        aria-controls="list-league-tab-pane" aria-selected="false">list-league</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="add-league-tab" data-bs-toggle="tab" data-bs-target="#add-league-tab-pane"
                        type="button" role="tab" aria-controls="add-league-tab-pane"
                        aria-selected="true">add-league</button>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active row g-2 my-2 pe-4" id="list-league-tab-pane" role="tabpanel"
                    aria-labelledby="list-league-tab" tabindex="0">
                    @foreach ($leagues as $league)
                        <a href="/league/{{ $league->id }}/edit"
                            class="col-lg-2 col-sm-6 col-12 col-desktop text-dark text-decoration-none">
                            <div class="shadow-sm p-0 border bg_ani rounded-4 bg-white h-100">

                                <div class="p-4">
                                    <div class="team-pair d-flex justify-content-around">
                                        <div class="home d-flex flex-column align-items-center">
                                            <img style="width: 3rem; height: 3rem;" class="" src="{{ $league->logo }}"
                                                alt="{{ $league->league_name }} Logo">
                                            <span id="league_name_{{ $league->id }}"
                                                class="text-center fw-semibold text-nowrap team_name d-inline-block text-truncate"
                                                style="max-width: 150px;">{{ $league->name }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                    {{ $leagues->links('layouts.bootstrap-5') }}
                </div>
                <div class="mt-3 w-100 tab-pane fade" id="add-league-tab-pane" role="tabpanel"
                    aria-labelledby="add-league-tab" tabindex="0">
                    <form method="post" action="/league" class="w-100 row d-flex justify-content-around px-2 g-3"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="col-lg-5 shadow-sm p-3">
                            <div class="row mb-3">
                                <div class="">
                                    <input id="league_name" type="text"
                                        class="form-control @error('league_name') is-invalid @enderror rounded-0"
                                        name="league_name" value="{{ old('league_name') }}" required
                                        autocomplete="league_name" autofocus placeholder="LEAGUE NAME">

                                    @error('league_name')
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
                                                <input id="league_logo" type="file"
                                                    class="form-control @error('league_logo') is-invalid @enderror m-0 custom-file-input"
                                                    name="league_logo" value="{{ old('league_logo') }}"
                                                    autocomplete="league_logo" accept="image/*" placeholder="LOGO URL"
                                                    required>
                                                <label class="custom-file-label" for="league_logo">Choose file</label>
                                            </div>

                                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                                <li class="nav-item d-flex" role="presentation">
                                                    <button onclick="Change_input('league_logo', 'file')"
                                                        class="nav-link fw-normal active" id="upload-tab"
                                                        data-bs-toggle="tab" data-bs-target="#upload-tab-pane"
                                                        type="button" role="tab" aria-controls="upload-tab-pane"
                                                        aria-selected="true">Upload</button>
                                                    <button onclick="Change_input('league_logo', 'url')"
                                                        class="nav-link fw-normal" id="url-tab" data-bs-toggle="tab"
                                                        data-bs-target="#url-tab-pane" type="button" role="tab"
                                                        aria-controls="url-tab-pane" aria-selected="true">URL</button>
                                                </li>
                                            </ul>
                                        </div>

                                        @error('league_logo')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- <div class="row mb-3">
                                <label for="league_logo" class="form-label fw-semibold d-flex align-items-center">LEAGUE
                                    LOGO
                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                        <li class="nav-item d-flex" role="presentation">
                                            <button onclick="Change_input('league_logo', 'file')"
                                                class="nav-link fw-normal active ms-2" id="upload-tab" data-bs-toggle="tab"
                                                data-bs-target="#upload-tab-pane" type="button" role="tab"
                                                aria-controls="upload-tab-pane" aria-selected="true">Upload Logo</button>
                                            <button onclick="Change_input('league_logo', 'text')" class="nav-link fw-normal"
                                                id="url-tab" data-bs-toggle="tab" data-bs-target="#url-tab-pane"
                                                type="button" role="tab" aria-controls="url-tab-pane"
                                                aria-selected="true">URL</button>
                                        </li>
                                    </ul>
                                </label>
                                <div>
                                    <input id="league_logo" type="file"
                                        class="form-control @error('league_logo') is-invalid @enderror rounded-0"
                                        name="league_logo" value="{{ old('league_logo') }}" autocomplete="league_logo"
                                        accept="image/*" placeholder="LOGO URL">

                                    @error('league_logo')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div> --}}
                            <div class="row mb-0 col-12 mt-3 ms-1 p-0">
                                <div class="w-100 p-0">
                                    <button type="submit" class="btn py-2 bg-menu  w-100 text-white">
                                        {{ __('ADD') }}
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
@section('script')
    <script>
        function Delete_league(id) {
            if (confirm("Are you sure?")) {
                axios.delete('/league/' + id)
                    .then(function(response) {
                        $('#' + id).remove();
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

        document.querySelector('.custom-file-input').addEventListener('change', function(e) {
            var fileName = document.getElementById("league_logo").files[0].name;
            var nextSibling = e.target.nextElementSibling;
            nextSibling.innerText = fileName;
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
    </script>
@endsection
