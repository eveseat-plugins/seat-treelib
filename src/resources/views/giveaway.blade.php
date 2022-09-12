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
                    Your character id and name will be sent to the server so the skin can be contracted to you at the end of the month.
                </p>

                <form action="{{ route("treelib.enterGiveaway") }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <button type="submit" class="btn btn-success">Enter Giveaway</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif