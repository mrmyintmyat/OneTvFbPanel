@extends('layouts.home')
@section('style')
    <style>
        #collapseSetting a:nth-child(3) {
            border-radius: 5px;
            background: rgba(255, 255, 255, 0.251);
        }

        #SvgjsG1016 * {
            height: 100px;
        }

        .remove-button {
            cursor: pointer;
            color: #ff0000;
            font-size: 14px;
            margin-top: 5px;
        }

        .img-url-group {
            margin-bottom: 15px;
        }

        /* .nav-tabs .nav-link.active {
                            background-color: #f8f9fa;
                        }

                        .nav-tabs .nav-link {
                            border: 1px solid #dee2e6;
                        } */

        .form-switch .form-check-input {
            cursor: pointer;
        }
    </style>
@endsection

@section('page')
    <div class="card text-start border-0 px-lg-0 px-2 mb-3" style="background: #ffffff00;">
        <div class="card-body pe-0 ">
            @if (!session('error'))
                <div class="d-flex w-100 ">
                    <form method="post" action="/slider-setting" class="w-100 row d-flex justify-content-between px-2 g-3"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="col-lg-6">
                            <label for="duration" class="form-label">Duration:</label>
                            <div class="d-flex justify-content-center align-items-center shadow-sm bg-white rounded-3"
                                style="height: 50px;">
                                <h5 class="d-flex align-items-center m-0">Status
                                    <div
                                        class="form-check form-switch d-flex align-items-center justify-content-center py-0 ms-3">
                                        <input class="form-check-input" name="status" type="checkbox" role="switch"
                                            id="flexSwitchCheckChecked">
                                    </div>
                                </h5>
                                <h5 class="d-flex align-items-center m-0 ms-4">Autoplay
                                    <div
                                        class="form-check form-switch d-flex align-items-center justify-content-center py-0 ms-3">
                                        <input class="form-check-input" name="autoplay" type="checkbox" role="switch"
                                            id="flexSwitchCheckChecked">
                                    </div>
                                </h5>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <label for="duration" class="form-label">Duration:</label>
                            <input type="number" name="duration" id="duration" min="1" class="form-control m-0"
                                value="{{ old('duration') }}" required>
                            @error('duration')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-12">
                            <div class="bg-white rounded-3 pt-3 px-0">
                                <div id="img-url-container" class="col-lg-12 px-3">
                                    <div class="img-url-group mb-0 row">
                                        <div class="d-flex team_logo_container">
                                            <div class="custom-file">
                                                <input id="img_url_1" type="file"
                                                    class="form-control @error('img_url') is-invalid @enderror m-0 custom-file-input"
                                                    name="img_url[]" value="{{ old('img_url_1') }}" autocomplete="img_url_1"
                                                    accept="image/*" placeholder="logo url" required>
                                                <label class="custom-file-label" for="img_url_1">choose file</label>
                                            </div>

                                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                                <li class="nav-item d-flex" role="presentation">
                                                    <button onclick="Change_input('img_url_1', 'file')"
                                                        class="nav-link fw-normal active" id="upload-tab"
                                                        data-bs-toggle="tab" data-bs-target="#upload-tab-pane"
                                                        type="button" role="tab" aria-controls="upload-tab-pane"
                                                        aria-selected="true">Upload</button>
                                                    <button onclick="Change_input('img_url_1', 'url')"
                                                        class="nav-link fw-normal" id="url-tab" data-bs-toggle="tab"
                                                        data-bs-target="#url-tab-pane" type="button" role="tab"
                                                        aria-controls="url-tab-pane" aria-selected="true">URL</button>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-12 team_logo_container">
                                            <input type="url" name="click_url[]" id="click_url_1" class="form-control"
                                                value="{{ old('click_url_1') }}" placeholder="click url" required>
                                            @error('click_url_1')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 d-flex justify-content-start m-0">
                                    <button type="button"
                                        class="btn btn-white text-black fw-semibold w-100 mb-2 border-top"
                                        onclick="addImageUrlField()">Add Another
                                        Image URL</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="w-100 p-0">
                                <button type="submit" class="btn py-2 bg-menu w-100 text-white">
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
    <script src="/js/plus_server-slider_setting.js?v=<?php echo time(); ?>"></script>
    <script>
        let imgUrlCount = 2;

        function addImageUrlField() {
            const container = document.getElementById('img-url-container');
            const div = document.createElement('div');
            div.classList.add('img-url-group', 'row', 'mb-0');
            const id = 'img_url_' + imgUrlCount;
            div.innerHTML = `
                <div class="d-flex team_logo_container">
                                        <div class="custom-file">
                                            <input id="${id}" type="file"
                                                class="form-control @error('img_url') is-invalid @enderror m-0 custom-file-input"
                                                name="img_url[]" value="{{ old('${id}') }}"
                                                autocomplete="${id}" accept="image/*" placeholder="logo url"
                                                required>
                                            <label class="custom-file-label" for="${id}">choose file</label>
                                        </div>

                                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                                            <li class="nav-item d-flex" role="presentation">
                                                <button onclick="Change_input('${id}', 'file')"
                                                    class="nav-link fw-normal active" id="upload-tab"
                                                    data-bs-toggle="tab" data-bs-target="#upload-tab-pane"
                                                    type="button" role="tab" aria-controls="upload-tab-pane"
                                                    aria-selected="true">Upload</button>
                                                <button onclick="Change_input('${id}', 'url')"
                                                    class="nav-link fw-normal" id="url-tab" data-bs-toggle="tab"
                                                    data-bs-target="#url-tab-pane" type="button" role="tab"
                                                    aria-controls="url-tab-pane" aria-selected="true">URL</button>
                                                    <span class="remove-button d-flex align-items-center px-3"
                                                    onclick="removeImageUrlField(this)"><i class="fa-solid fa-trash"></i></span>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-12 team_logo_container">
                                            <input type="url" name="click_url[]" id="click_url_${imgUrlCount}" class="form-control"
                                                value="{{ old('click_url_${imgUrlCount}') }}" placeholder="click url" required>
                                            @error('click_url_${imgUrlCount}')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
            `;
            container.appendChild(div);
            document.getElementById(id).addEventListener('change', function(e) {
                const fileName = e.target.files[0] ? e.target.files[0].name : 'Choose file';
                const nextSibling = e.target.nextElementSibling;
                nextSibling.innerText = fileName;
            });
            imgUrlCount++;
        }

        function removeImageUrlField(button) {
            const container = document.getElementById('img-url-container');
            if (container.children.length > 1) {
                button.parentElement.parentElement.parentElement.parentElement.remove();
            } else {
                alert("You must have at least one Image URL/File field.");
            }
        }

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

        document.querySelector('#img_url_1').addEventListener('change', function(e) {
            var fileName = document.getElementById("img_url_1").files[0].name;
            var nextSibling = e.target.nextElementSibling;
            nextSibling.innerText = fileName;
        });
    </script>
@endsection
