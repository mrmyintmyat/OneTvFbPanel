@php
    use Carbon\Carbon;
@endphp
@extends('layouts.home')
@section('style')
    <style>
        #collapseSetting a:nth-child(1) {
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
        <div class="card text-start mt-lg-2 rounded-0 px-lg-0 px-lg-2 mb-3" style="background: #ffffff00;">
            <div class="card-body pe-0">
                <div class="d-flex mt-3 w-100">
                    <form action="/update-setting/{{ $id }}"
                        class="w-100 row d-flex justify-content-around px-lg-2 g-3" method="post">
                        @csrf @method('PUT')

                        <div class="row p-lg-3">
                            <div>
                                <h4>Update Info</h4>
                            </div>
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @foreach ($updateInfos as $key => $value)
                                <div class="col-lg-6 mb-3">
                                    <label for="{{ $key }}"
                                        class="form-label">{{ ucfirst(str_replace('_', ' ', $key)) }}</label>
                                    <div>
                                        <input id="{{ $key }}" type="text" class="form-control"
                                            name="updateInfos[{{ $key }}]"
                                            value="{{ old('updateInfos.' . $key, $value) }}" required
                                            autocomplete="{{ $key }}" autofocus>
                                    </div>
                                </div>
                            @endforeach
                            <div class="col-12 mt-3">
                                <div class="w-100 p-0">
                                    <button type="submit"
                                        class="btn py-2 bg-menu w-100 text-white fw-semibold">UPDATE</button>
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
