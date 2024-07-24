@php
    use Carbon\Carbon;
    use Illuminate\Support\Facades\Session;
@endphp
@extends('layouts.home')
@section('style')
    <style>
        .hover_menu_tag a:nth-child(2) {
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
    <div class="row matchs_container g-2 my-2 px-4">
        <a href="/matches/create" class="col-lg-3 col-md-4 col-sm-6 col-12 col-desktop text-dark text-decoration-none" style="min-height: 8rem;">
            <div class="shadow-sm p-0 border bg_ani rounded-4 bg-white h-100">
                <div class="league_text fw-semibold d-flex w-100 justify-content-center align-items-center h-100">
                    <i class="fa-solid fa-plus fs-3"></i>
                </div>
            </div>
        </a>
        @foreach ($matches as $match)
            <a href="/highlights/{{ $match->id }}/edit"
                class="col-lg-3 col-md-4 col-sm-6 col-12 col-desktop text-dark text-decoration-none">
                <div class="shadow-sm p-0 border bg_ani rounded-4 bg-white h-100">
                    <div class="league_text fw-semibold d-flex w-100 justify-content-between">
                        <div class="border border-top-0 py-1 bg-white" style="border-start-start-radius: 1rem;">
                            <div class="p-2 py-0 text-nowrap d-flex">
                                <img class="me-1" style="width: 40px;" src="{{ $match->league_logo }}" alt=""
                                    sizes="" srcset="">
                                <span class="d-inline-block text-truncate" style="max-width: 230px;">
                                    {{ $match->league_name }}
                                </span>
                            </div>
                        </div>
                        @php
                            $match_time = Carbon::createFromTimestamp($match->match_time, Session::get('timezone'));
                        @endphp
                        <span class="py-1 px-2">
                            {{ $match_time->format('Y-m-d') }}
                        </span>

                    </div>

                    <div class="p-4">
                        <div class="team-pair d-flex justify-content-around">
                            <div class="home d-flex flex-column align-items-center col-4">
                                <img style="width: 40px;" class="" src="{{ $match->home_team_logo }}"
                                    alt="{{ $match->home_team_name }} Logo">
                                <span
                                    class="text-center fw-semibold text-nowrap team_name mt-1 d-inline-block text-truncate"
                                    style="max-width: 150px;">
                                    {{ $match->home_team_name }}
                                </span>
                            </div>
                            <div
                                class="date-time d-flex flex-column text-center justify-content-center text-center fw-semibold">
                                <div class="fs-5 fw-semibold">
                                    <span>{{ $match->home_team_score }}</span>
                                    <span>-</span>
                                    <span>{{ $match->away_team_score }}</span>
                                </div>
                            </div>

                            <div class="away d-flex flex-column align-items-center col-4">
                                <img style="width: 40px;" class="" src="{{ $match->away_team_logo }}"
                                    alt="{{ $match->away_team_name }} Logo">
                                <span
                                    class="text-center fw-semibold text-nowrap team_name mt-1 d-inline-block text-truncate"
                                    style="max-width: 150px;">
                                    {{ $match->away_team_name }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
        <div class="col-11">
            {{ $matches->links('layouts.bootstrap-5') }}
        </div>
    </div>
@endsection
