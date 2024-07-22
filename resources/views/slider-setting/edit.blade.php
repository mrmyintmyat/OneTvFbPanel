@extends('layouts.home')
@section('style')
    <style>
        .hover_menu_tag a:nth-child() {
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

        .nav-tabs .nav-link.active {
            background-color: #f8f9fa;
        }

        .nav-tabs .nav-link {
            border: 1px solid #dee2e6;
        }

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
                    <form method="post" action="{{ route('slider-setting.update', $sliderSetting->id) }}" class="w-100 row d-flex justify-content-between px-2 g-3" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="col-lg-6">
                            <label for="duration" class="form-label">Duration:</label>
                            <div class="d-flex justify-content-center align-items-center shadow-sm bg-white rounded-3" style="height: 50px;">
                                <h5 class="d-flex align-items-center m-0">Status
                                    <div class="form-check form-switch d-flex align-items-center justify-content-center py-0 ms-3">
                                        <input class="form-check-input" name="status" type="checkbox" role="switch"
                                            id="flexSwitchCheckChecked" {{ $sliderSetting->status ? 'checked' : '' }}>
                                    </div>
                                </h5>
                                <h5 class="d-flex align-items-center m-0 ms-4">Autoplay
                                    <div class="form-check form-switch d-flex align-items-center justify-content-center py-0 ms-3">
                                        <input class="form-check-input" name="autoplay" type="checkbox" role="switch"
                                            id="flexSwitchCheckChecked" {{ $sliderSetting->autoplay ? 'checked' : '' }}>
                                    </div>
                                </h5>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <label for="duration" class="form-label">Duration:</label>
                            <input type="number" name="duration" id="duration" min="1" class="form-control m-0"
                                value="{{ old('duration', $sliderSetting->duration) }}" required>
                            @error('duration')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label for="click_url" class="form-label">Click URL:</label>
                            <input type="url" name="click_url" id="click_url" class="form-control"
                                value="{{ old('click_url', $sliderSetting->click_url) }}" required>
                            @error('click_url')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div id="img-url-container" class="col-lg-12">
                            @foreach($sliderSetting->imageUrls as $index => $imageUrl)
                                <div class="img-url-group row">
                                    <label for="img_url_{{ $index + 1 }}" class="form-label fw-semibold d-flex align-items-center">
                                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                                            <li class="nav-item d-flex" role="presentation">
                                                <button onclick="changeInput('img_url_{{ $index + 1 }}', 'file')"
                                                    class="nav-link fw-normal {{ !filter_var($imageUrl->img_url, FILTER_VALIDATE_URL) ? 'active' : '' }} ms-2" id="upload-tab" data-bs-toggle="tab"
                                                    data-bs-target="#upload-tab-pane" type="button" role="tab"
                                                    aria-controls="upload-tab-pane" aria-selected="{{ !filter_var($imageUrl->img_url, FILTER_VALIDATE_URL) ? 'true' : 'false' }}">Upload Logo</button>
                                                <button onclick="changeInput('img_url_{{ $index + 1 }}', 'url')" class="nav-link fw-normal {{ filter_var($imageUrl->img_url, FILTER_VALIDATE_URL) ? 'active' : '' }}"
                                                    id="url-tab" data-bs-toggle="tab" data-bs-target="#url-tab-pane"
                                                    type="button" role="tab" aria-controls="url-tab-pane"
                                                    aria-selected="{{ filter_var($imageUrl->img_url, FILTER_VALIDATE_URL) ? 'true' : 'false' }}">URL</button>
                                            </li>
                                        </ul>
                                    </label>
                                    <div class="col-12">
                                        <input id="img_url_{{ $index + 1 }}" type="{{ filter_var($imageUrl->img_url, FILTER_VALIDATE_URL) ? 'url' : 'file' }}"
                                            class="form-control @error('img_url') is-invalid @enderror" name="img_url[]"
                                            value="{{ old('img_url.' . $index, $imageUrl->img_url) }}" autocomplete="img_url" accept="image/*" placeholder="LOGO URL">
                                        @error('img_url')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <span class="remove-button" onclick="removeImageUrlField(this)">Remove</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="col-12 d-flex justify-content-start m-0 px-3">
                            <button type="button" class="btn btn-secondary" onclick="addImageUrlField()">Add Another Image URL</button>
                        </div>
                        <div class="row mb-0 col-12 ms-1 p-0">
                            <div class="w-100 p-0 px-3">
                                <button type="submit" class="btn py-2 bg-menu w-100 text-white">
                                    {{ __('UPDATE') }}
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
    <script src="/js/plus_server-slider_setting.js"></script>
    <script>
        let imgUrlCount = {{ $sliderSetting->imageUrls->count() + 1 }};

        function addImageUrlField() {
            const container = document.getElementById('img-url-container');
            const div = document.createElement('div');
            div.classList.add('img-url-group', 'row');
            const id = 'img_url_' + imgUrlCount;
            div.innerHTML = `
                <label for="${id}" class="form-label fw-semibold d-flex align-items-center">Image
                    <ul class="nav nav-tabs ms-3" role="tablist">
                        <li class="nav-item d-flex" role="presentation">
                            <button onclick="changeInput('${id}', 'file')" class="nav-link fw-normal active ms-2" data-bs-toggle="tab" data-bs-target="#upload-tab-pane" type="button" role="tab" aria-controls="upload-tab-pane" aria-selected="true">Upload Logo</button>
                            <button onclick="changeInput('${id}', 'url')" class="nav-link fw-normal" data-bs-toggle="tab" data-bs-target="#url-tab-pane" type="button" role="tab" aria-controls="url-tab-pane" aria-selected="false">URL</button>
                            <span class="remove-button" onclick="removeImageUrlField(this)">Remove</span>
                        </li>
                    </ul>
                </label>
                <div class="col-12">
                    <input id="${id}" type="file" class="form-control" name="img_url[]" autocomplete="off" accept="image/*" placeholder="LOGO URL">
                </div>
            `;
            container.appendChild(div);
            imgUrlCount++;
        }

        function removeImageUrlField(button) {
            const container = document.getElementById('img-url-container');
            if (container.children.length > 1) {
                button.parentElement.parentElement.remove();
            } else {
                alert("You must have at least one Image URL/File field.");
            }
        }

        function changeInput(inputId, type) {
            const inputElement = document.getElementById(inputId);
            inputElement.type = type === 'file' ? 'file' : 'url';
            inputElement.value = '';
            inputElement.placeholder = type === 'file' ? '' : 'LOGO URL';
            inputElement.accept = type === 'file' ? 'image/*' : '';
        }
    </script>
@endsection
