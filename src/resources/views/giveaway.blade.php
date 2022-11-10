@if($giveaway_active)
    <div class="card">
        <div class="card-body">
            <h5 class="card-header">
                {{ trans("treelib::giveaway.giveaway_card_title") }}
            </h5>
            <div class="card-text my-3 mx-3">
                <p>
                    {{ trans("treelib::giveaway.giveaway_card_text") }}
                </p>

                <div class="d-flex flex-row">
                    <form action="{{ route("treelib.enterGiveaway") }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success">{{ trans("treelib::giveaway.enter_giveaway_button") }}</button>
                    </form>

                    <form action="{{ route("treelib.optOutGiveaway") }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger mx-2 confirmform" data-seat-action="{{ trans("treelib::giveaway.optout_giveaway_confirm") }}">{{ trans("treelib::giveaway.optout_giveaway_button") }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif