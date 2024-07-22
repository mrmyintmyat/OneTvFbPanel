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
    </style>
@endsection
@section('page')
    <div class="card text-start border-0 px-lg-0 px-2 mb-3" style="background: #ffffff00;">
        <div class="card-body pe-0 ">
            @if (!session('error'))
                <div class="d-flex w-100 ">
                    <form method="post" action="{{ route('channel.update', $channel->id) }}" class="w-100 row d-flex justify-content-between px-2 g-3" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row p-3">
                            <div class="col-12 mb-3">
                                <div>
                                    <input id="channel_name" type="text" class="@error('channel_name') is-invalid @enderror" name="channel_name" value="{{ old('channel_name', $channel->channel_name) }}" required autocomplete="channel_name" autofocus placeholder="CHANNEL NAME">
                                    @error('channel_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="channel_logo" class="form-label fw-semibold d-flex align-items-center">HOME TEAM LOGO
                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                        <li class="nav-item d-flex" role="presentation">
                                            <button onclick="Change_input('channel_logo', 'file')" class="nav-link fw-normal active ms-2" id="upload-tab" data-bs-toggle="tab" data-bs-target="#upload-tab-pane" type="button" role="tab" aria-controls="upload-tab-pane" aria-selected="true">Upload Logo</button>
                                            <button onclick="Change_input('channel_logo', 'url')" class="nav-link fw-normal" id="url-tab" data-bs-toggle="tab" data-bs-target="#url-tab-pane" type="button" role="tab" aria-controls="url-tab-pane" aria-selected="true">URL</button>
                                        </li>
                                    </ul>
                                </label>
                                <div>
                                    <input id="channel_logo" type="file" class="form-control @error('channel_logo') is-invalid @enderror" name="channel_logo" value="{{ $channel->channel_logo }}" autocomplete="channel_logo" accept="image/*" placeholder="LOGO URL">
                                    @error('channel_logo')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="p-lg-3">
                                <ul class="nav nav-tabs mb-2" id="myTab" role="tablist">
                                    @foreach($channel->servers as $index => $server)
                                        <li id="server-btns-container" class="nav-item d-flex flex-row" role="presentation">
                                            <button class="server-btns nav-link {{ $index === 0 ? 'active' : '' }} text-nowrap" id="server-{{ $index + 1 }}-tab" data-bs-toggle="tab" data-bs-target="#server-{{ $index + 1 }}" type="button" role="tab" aria-controls="server-{{ $index + 1 }}-tab-pane" aria-selected="{{ $index === 0 ? 'true' : 'false' }}">Server {{ $index + 1 }}</button>
                                        </li>
                                    @endforeach
                                    <button id="add-server-btn" type="button" class="px-3 btn btn-info">+</button>
                                </ul>
                                <div class="tab-content" id="pills-tabContent">
                                    @foreach($channel->servers as $index => $server)
                                        <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="server-{{ $index + 1 }}" role="tabpanel" aria-labelledby="server-{{ $index + 1 }}-tab" tabindex="0">
                                            <div class="row">
                                                <div>
                                                    <input id="server_url" type="url" class="@error('server_url') is-invalid @enderror" name="server_url[]" value="{{ old('server_url.' . $index, $server['url']) }}" autocomplete="server_url" placeholder="URL">
                                                    @error('server_url')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div>
                                                    <input id="server_header" type="text" class="@error('server_header') is-invalid @enderror" name="server_header[]" value="{{ old('server_header.' . $index, $server['headers']) }}" autocomplete="server_header" placeholder="REFERER">
                                                    @error('server_header')
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
                        </div>
                        <div class="row mb-0 col-12 ms-1 p-0">
                            <div class="w-100 p-0 px-3">
                                <button type="submit" class="btn py-2 bg-menu w-100 text-white">{{ __('UPDATE') }}</button>
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
        $('#channel_status').on('change', function() {
            if ($(this).val() === 'Live' || $(this).val() === 'Highlight') {
                $('#away_team_score, #home_team_score, #server_url, #server_header').prop('required', true);
            } else {
                $('#away_team_score, #home_team_score, #server_url, #server_header').prop('required', false);
            }
        });
        function Change_input(id, type) {
            const inputElement = document.getElementById(id);
            if (type === 'file') {
                inputElement.type = 'file';
            } else if (type === 'url') {
                inputElement.type = 'url';
            }
        }
    </script>
@endsection
