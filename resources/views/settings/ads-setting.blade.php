@php
    use Carbon\Carbon;
@endphp
@extends('layouts.home')
@section('style')
    <style>
        #collapseSetting a:nth-child(2) {
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
    @if (!session('error'))
        <div class="card text-start mt-lg-2 px-lg-0 px-2 mb-3" style="background: #ffffff00;">
            <div class="card-body pe-0">
                <div class="d-flex mt-3 w-100">
                    <form action="/ads_setting/{{ $id }}" class="w-100 row d-flex justify-content-around px-2 g-3"
                        method="post" enctype="multipart/form-data">
                        @csrf @method('PUT')

                        <div class="col-lg-6 p-lg-3">
                            <div>
                                <h4 class="d-flex">sponsorGoogle
                                    <div class="form-check form-switch ms-3">
                                        <input class="form-check-input" name="sponsorGoogle_status" type="checkbox"
                                            role="switch" id="flexSwitchCheckChecked"
                                            {{ $sponsorGoogle['status'] ? 'checked' : '' }}>
                                    </div>
                                </h4>
                            </div>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <button class="nav-link active" id="nav-android-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-android" type="button" role="tab" aria-controls="nav-android"
                                    aria-selected="true">Android</button>
                                <button class="nav-link" id="nav-Ios-tab" data-bs-toggle="tab" data-bs-target="#nav-Ios"
                                    type="button" role="tab" aria-controls="nav-Ios" aria-selected="false">Ios</button>
                            </div>
                            <div class="tab-content" id="nav-tabContent">
                                <div class="tab-pane fade show active" id="nav-android" role="tabpanel"
                                    aria-labelledby="nav-android-tab" tabindex="0">
                                    <div class="row mb-3">
                                        <label for="android_banner" class="form-label ">
                                            Android_banner
                                        </label>

                                        <div class="">
                                            <input id="android_banner" type="text" class="form-control "
                                                name="android_banner" value="{{ $sponsorGoogle['android_banner'] }}"
                                                required="" autocomplete="android_banner" autofocus="">

                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="android_inter" class="form-label ">
                                            Android_inter
                                        </label>

                                        <div class="">
                                            <input id="android_inter" type="text" class="form-control "
                                                name="android_inter" value="{{ $sponsorGoogle['android_inter'] }}"
                                                required="" autocomplete="android_inter" autofocus="">

                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="android_appopen" class="form-label ">
                                            Android_appopen
                                        </label>

                                        <div class="">
                                            <input id="android_appopen" type="text" class="form-control "
                                                name="android_appopen" value="{{ $sponsorGoogle['android_appopen'] }}"
                                                required="" autocomplete="android_appopen" autofocus="">

                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav-Ios" role="tabpanel" aria-labelledby="nav-Ios-tab"
                                    tabindex="0">
                                    <div class="row mb-3">
                                        <label for="ios_banner" class="form-label ">
                                            Ios_banner
                                        </label>

                                        <div class="">
                                            <input id="ios_banner" type="text" class="form-control " name="ios_banner"
                                                value="{{ $sponsorGoogle['ios_banner'] }}" required=""
                                                autocomplete="ios_banner" autofocus="">

                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="ios_inter" class="form-label ">
                                            Ios_inter
                                        </label>

                                        <div class="">
                                            <input id="ios_inter" type="text" class="form-control " name="ios_inter"
                                                value="{{ $sponsorGoogle['ios_inter'] }}" required=""
                                                autocomplete="ios_inter" autofocus="">

                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="ios_appopen" class="form-label ">
                                            Ios_appopen
                                        </label>

                                        <div class="">
                                            <input id="ios_appopen" type="text" class="form-control "
                                                name="ios_appopen" value="{{ $sponsorGoogle['ios_appopen'] }}"
                                                required="" autocomplete="ios_appopen" autofocus="">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 p-lg-3">
                            <div>
                                <h4 class="d-flex">sponsorText
                                    <div class="form-check form-switch ms-3">
                                        <input class="form-check-input" name="sponsorText_status" type="checkbox"
                                            role="switch" id="flexSwitchCheckChecked"
                                            {{ $sponsorText['status'] ? 'checked' : '' }}>
                                    </div>
                                </h4>
                            </div>
                            <div class="row mb-3">
                                <label for="text" class="form-label ">
                                    Text
                                </label>

                                <div class="">
                                    <textarea class="form-control" id="text" rows="3" name="text" required autocomplete="text"
                                        autofocus="">{{ $sponsorText['text'] }}
                                </textarea>

                                </div>
                            </div>
                            <div>
                                <h4 class="d-flex">sponsorInter
                                    <div class="form-check form-switch ms-3">
                                        <input class="form-check-input" name="sponsorInter_status" type="checkbox"
                                            role="switch" id="flexSwitchCheckChecked"
                                            {{ $sponsorInter['status'] ? 'checked' : '' }}>
                                    </div>
                                </h4>
                            </div>
                            <div class="row mb-3">
                                <label for="inter_adImage" class="form-label ">
                                    Ad Image
                                </label>

                                <div class="">
                                    <input id="inter_adImage" type="text" class="form-control " name="inter_adImage"
                                        value="{{ $sponsorInter['adImage'] }}" required=""
                                        autocomplete="inter_adImage" autofocus="">

                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="inter_adUrl" class="form-label  d-flex align-items-center">
                                    Ad URL
                                </label>
                                <div>
                                    <input id="inter_adUrl" type="text" class="form-control " name="inter_adUrl"
                                        value="{{ $sponsorInter['adUrl'] }}" autocomplete="inter_adUrl">
                                </div>
                            </div>
                        </div>
                        <div class="p-lg-3 m-0">
                            <div>
                                <h4 class="d-flex">sponsorBanner
                                    <div class="form-check form-switch ms-3">
                                        <input class="form-check-input" name="sponsorBanner_status" type="checkbox"
                                            role="switch" id="flexSwitchCheckChecked"
                                            {{ $sponsorBanner['status'] ? 'checked' : '' }}>
                                    </div>
                                </h4>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="bg-white rounded-3 pt-3 px-0">
                                        <div id="img-url-container" class="col-lg-12 px-3">
                                            @foreach ($settings->imageUrls as $index => $imageUrl)
                                                <div class="img-url-group row">
                                                    <div class="d-flex mb-2 team_logo_container">
                                                        <div class="custom-file">
                                                            <input id="img_url_{{ $index + 1 }}" type="url"
                                                                class="form-control @error('img_url') is-invalid @enderror m-0 custom-file-input"
                                                                name="img_url[]"
                                                                value="{{ old('img_url.' . $index, $imageUrl->img_url) }}"
                                                                autocomplete="img_url_1" accept="image/*"
                                                                placeholder="LOGO URL" required>
                                                        </div>

                                                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                                                            <li class="nav-item d-flex" role="presentation">
                                                                <button
                                                                    onclick="Change_input('img_url_{{ $index + 1 }}', 'file')"
                                                                    class="nav-link fw-normal" id="upload-tab"
                                                                    data-bs-toggle="tab" data-bs-target="#upload-tab-pane"
                                                                    type="button" role="tab"
                                                                    aria-controls="upload-tab-pane"
                                                                    aria-selected="{{ !filter_var($imageUrl->img_url, FILTER_VALIDATE_URL) ? 'true' : 'false' }}">Upload</button>
                                                                <button
                                                                    onclick="Change_input('img_url_{{ $index + 1 }}', 'url')"
                                                                    class="nav-link fw-normal active" id="url-tab"
                                                                    data-bs-toggle="tab" data-bs-target="#url-tab-pane"
                                                                    type="button" role="tab"
                                                                    aria-controls="url-tab-pane"
                                                                    aria-selected="{{ !filter_var($imageUrl->img_url, FILTER_VALIDATE_URL) ? 'true' : 'false' }}">URL</button>
                                                                <span class="remove-button d-flex align-items-center px-3"
                                                                    onclick="removeImageUrlField(this)"><i
                                                                        class="fa-solid fa-trash text-danger"></i></span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-12 team_logo_container">
                                                        <input type="url" name="click_url[]"
                                                            id="click_url_{{ $index + 1 }}" class="form-control"
                                                            value="{{ $imageUrl->click_url }}" placeholder="click url"
                                                            required>
                                                        @error('click_url_{{ $index + 1 }}')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="col-12 d-flex justify-content-start m-0">
                                            <button type="button"
                                                class="btn btn-white text-black fw-semibold w-100 mb-2 border-top"
                                                onclick="addImageUrlField()">Add Another
                                                Image URL</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-3">
                                <div class="w-100 p-0">
                                    <button type="submit"
                                        class="btn py-2 bg-menu w-100 text-white fw-semibold">UPDATE</button>
                                </div>
                            </div>
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
    <script>
        let imgUrlCount = {{ $sliderSetting->imageUrls->count() + 1 }};

        function addImageUrlField() {
            const container = document.getElementById('img-url-container');
            const div = document.createElement('div');
            div.classList.add('img-url-group', 'row');
            const id = 'img_url_' + imgUrlCount;
            div.innerHTML = `
        <div class="d-flex mb-2 team_logo_container">
                                <div class="custom-file">
                                    <input id="${id}" type="file"
                                        class="form-control @error('img_url') is-invalid @enderror m-0 custom-file-input"
                                        name="img_url[]" value="{{ old('${id}') }}"
                                        autocomplete="${id}" accept="image/*" placeholder="LOGO URL"
                                        required>
                                    <label class="custom-file-label" for="${id}">Choose file</label>
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
                                            onclick="removeImageUrlField(this)"><i class="fa-solid fa-trash text-danger"></i></span>
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
