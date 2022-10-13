@if($giveaway_active)
    <div class="card">
        <div class="card-body">
            <h5 class="card-header">
                EVE Partner Giveaway
            </h5>
            <div class="card-text my-3 mx-3">
                <p>
                    The developer of this seat-module is part of the EVE Partner Program. This means monthly skin giveaways!
                    To enter, press the button below.
                    Your character id and name will be sent to an external server server so the skin can be contracted to you at the end of the month.
                </p>

                <div class="d-flex flex-row">
                    <form action="{{ route("treelib.enterGiveaway") }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success">Enter Giveaway</button>
                    </form>

                    <form action="{{ route("treelib.optOutGiveaway") }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger mx-2 confirmform" data-seat-action="opt out of all giveaways?">Opt-Out</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif