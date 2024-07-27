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
    <div class="card text-start border-0 px-lg-0 px-2 mb-3" style="background: #ffffff00;">
        <div class="card-body pe-0 ">
            @if (!session('error'))
                <div class="d-flex w-100 ">
                    <form method="post" action="/channel" class="w-100 row d-flex justify-content-between px-2 g-3"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="row p-3">
                            <div class="col-12 mb-3">
                                <div class="">
                                    <input id="channel_name" type="text"
                                        class=" @error('channel_name') is-invalid @enderror"
                                        name="channel_name" value="{{ old('channel_name') }}" required
                                        autocomplete="channel_name" autofocus placeholder="CHANNEL NAME">

                                    @error('channel_name')
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
                                                <input id="channel_logo" type="file"
                                                    class="form-control @error('channel_logo') is-invalid @enderror m-0 custom-file-input"
                                                    name="channel_logo" value="{{ old('channel_logo') }}"
                                                    autocomplete="channel_logo" accept="image/*" placeholder="LOGO URL"
                                                    required>
                                                <label class="custom-file-label" for="channel_logo">Choose file</label>
                                            </div>

                                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                                <li class="nav-item d-flex" role="presentation">
                                                    <button onclick="Change_input('channel_logo', 'file')"
                                                        class="nav-link fw-normal active" id="upload-tab"
                                                        data-bs-toggle="tab" data-bs-target="#upload-tab-pane"
                                                        type="button" role="tab" aria-controls="upload-tab-pane"
                                                        aria-selected="true">Upload</button>
                                                    <button onclick="Change_input('channel_logo', 'url')"
                                                        class="nav-link fw-normal" id="url-tab" data-bs-toggle="tab"
                                                        data-bs-target="#url-tab-pane" type="button" role="tab"
                                                        aria-controls="url-tab-pane" aria-selected="true">URL</button>
                                                </li>
                                            </ul>
                                        </div>

                                        @error('channel_logo')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class=" p-lg-3">
                                <ul class="nav nav-tabs mb-2" id="myTab" role="tablist">
                                    <li id="server-btns-container" class="nav-item d-flex flex-row" role="presentation">
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
                                        <div class="row ">
                                            <div class="">
                                                <input id="server_name" type="text"
                                                    class=" @error('server_name') is-invalid @enderror"
                                                    name="server_name[]" value="{{ old('server_name.0') }}"
                                                    autocomplete="server_name" placeholder="name" required>

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
                                                    class=" @error('server_url') is-invalid @enderror"
                                                    name="server_url[]" value="{{ old('server_url.0') }}"
                                                    autocomplete="server_url" placeholder="url" required>

                                                @error('server_url')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row ">
                                            <div class="">
                                                <input id="server_referer" type="text"
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
                        </div>
                            <div class="row mb-0 col-12 ms-1 p-0">
                                <div class="w-100 p-0 px-3">
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
    <script src="/js/plus_server-channel.js"></script>
    <script>
       $('#match_status').on('change', function() {
            if ($(this).val() === 'Live' || $(this).val() === 'Highlight') {
                $('#away_team_score, #home_team_score, #server_url').prop('required', true);
            } else {
                $('#away_team_score, #home_team_score, #server_url').prop('required', false);
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

        document.querySelector('.custom-file-input').addEventListener('change', function(e) {
            var fileName = document.getElementById("channel_logo").files[0].name;
            var nextSibling = e.target.nextElementSibling;
            nextSibling.innerText = fileName;
        });

    </script>
@endsection
