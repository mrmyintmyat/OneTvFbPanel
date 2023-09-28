@extends('layouts.home')
@section('style')
    <style>
        .hover_menu_tag a:nth-child(3) {
            border-left: 3px solid #ff0505 !important;
            background: rgba(255, 255, 255, 0.251);
        }

        #SvgjsG1016 * {
            height: 100px;
        }
    </style>
@endsection
@section('page')
<div class="row matchs_container g-2 my-2 px-4">
    @foreach ($matches as $match)
            <a href="/highlights/{{ $match->id }}/edit" class="col-lg-4 col-sm-6 col-12 col-desktop text-decoration-none text-dark">
        <div class="shadow-sm p-0 border bg_ani">
            <div class="league_text fw-semibold d-flex w-100 justify-content-between">
                <div class="border border-top-0 py-1 bg-white">
                    <div class="p-2 py-0 text-nowrap d-flex">
                        <img class="me-1" style="width: 20px;" src="{{ $match->league_logo }}" alt=""
                            sizes="" srcset="">
                        <span class="d-inline-block text-truncate" style="max-width: 230px;">
                            {{ $match->league_name }}
                        </span>
                    </div>
                </div>
                <span class="py-1 px-2">
                    {{ date('Y-m-d', $match->match_time / 1000) }}
                </span>

            </div>

            <div class="p-4">
                <div class="team-pair d-flex justify-content-around">
                    <div style="width: 4rem;" class="home d-flex flex-column align-items-center">
                        <img class="w-100" src="{{ $match->home_team_logo }}" alt="{{ $match->home_team_name }} Logo">
                        <span class="text-center fw-semibold text-nowrap team_name mt-1 d-inline-block text-truncate" style="max-width: 150px;">
                            {{ $match->home_team_name }}
                        </span>
                    </div>
                    <div class="date-time d-flex flex-column text-center justify-content-center text-center fw-semibold">
                        <div class="fs-3 fw-semibold">
                            <span>{{ $match->home_team_score }}</span>
                            <span>-</span>
                            <span>{{ $match->away_team_score }}</span>
                        </div>
                    </div>

                    <div style="width: 4rem;" class="away d-flex flex-column align-items-center">
                        <img class="w-100" src="{{ $match->away_team_logo }}" alt="{{ $match->away_team_name }} Logo">
                        <span class="text-center fw-semibold text-nowrap team_name mt-1 d-inline-block text-truncate" style="max-width: 150px;">
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
