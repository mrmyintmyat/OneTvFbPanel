@extends('layouts.home')
@section('page')
    <div class="card text-start mt-lg-2 rounded-0 px-lg-0 px-2 mb-3">
        <div class="card-body pe-0">
            <div class="mt-3 w-100">
                <form method="post" action="/league/{{ $league->id }}"
                    class="w-100 row d-flex justify-content-around px-2 g-3" enctype="multipart/form-data">
                    @csrf @method('PUT')

                    <div class="col-lg-5 shadow-sm p-3">
                        <!-- League Name Input -->
                        <div class="row mb-3">
                            <div class="">
                                <input id="league_name" type="text"
                                    class="form-control @error('league_name') is-invalid @enderror rounded-0"
                                    name="league_name" value="{{ $league->name }}" required autocomplete="league_name"
                                    autofocus placeholder="LEAGUE NAME">

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
                                            <input id="league_logo" type="url"
                                                class="form-control @error('league_logo') is-invalid @enderror m-0 custom-file-input"
                                                name="league_logo" value="{{ $league->logo }}"
                                                autocomplete="league_logo" accept="image/*" placeholder="LOGO URL" required>
                                        </div>

                                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                                            <li class="nav-item d-flex" role="presentation">
                                                <button onclick="Change_input('league_logo', 'file')"
                                                    class="nav-link fw-normal" id="upload-tab" data-bs-toggle="tab"
                                                    data-bs-target="#upload-tab-pane" type="button" role="tab"
                                                    aria-controls="upload-tab-pane" aria-selected="true">Upload</button>
                                                <button onclick="Change_input('league_logo', 'url')"
                                                    class="nav-link fw-normal active" id="url-tab" data-bs-toggle="tab"
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

                        <!-- Submit Button -->
                        <div class="row mb-0 col-12 mt-3 ms-1 p-0">
                            <div class="w-100 p-0">
                                <button type="submit" class="btn py-2 bg-menu w-100 text-white">
                                    {{ __('UPDATE') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
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
