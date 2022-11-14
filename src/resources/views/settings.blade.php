@extends('web::layouts.grids.12')

@section('title', trans('treelib::treelib.treelib_settings'))
@section('page_header', trans('treelib::treelib.treelib_settings'))

@section('full')

    <!-- Instructions -->
    <div class="row w-100">
        <div class="col">
            <div class="card-column">

                @include("treelib::giveaway")

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-header">
                            {{ trans("treelib::treelib.settings_giveaway_settings") }}
                        </h5>
                        <div class="card-text my-3 mx-3">
                            <form action="{{ route("treelib.optOutGiveaway") }}" method="POST">
                                @if($user_giveaway_optout)
                                    <input type="hidden" name="state" value="0">
                                    @csrf
                                    <p>
                                        {{ trans("treelib::treelib.settings_giveaways_disabled_text") }}
                                    </p>
                                    <button type="submit" class="btn btn-primary">{{ trans("treelib::giveaway.optin_giveaway_button") }}</button>
                                @else
                                    <input type="hidden" name="state" value="1">
                                    @csrf
                                    <p>
                                        {{ trans("treelib::treelib.settings_giveaways_enabled_text") }}
                                    </p>
                                    <button type="submit" class="btn btn-danger confirmform" data-seat-action="{{ trans("treelib::giveaway.optout_giveaway_confirm") }}">{{ trans("treelib::giveaway.optout_giveaway_button") }}</button>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@stop