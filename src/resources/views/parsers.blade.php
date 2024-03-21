@extends('web::layouts.grids.12')

@section('title', "Parser Debugger")
@section('page_header', "Parser Debugger")

@section('full')

    <!-- Instructions -->
    <div class="row w-100">
        <div class="col">
            <div class="card-column">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-header">
                            Parser Tester
                        </h5>
                        <div class="card-text my-3 mx-3">
                            <p>
                                If you think the parser parsed your items wrong, please enter them and press <i>Generate Report</i>.
                                Go to <a href="https://github.com/recursivetree/seat-treelib/issues">GitHub</a> and open an issue.
                                Try to describe what's wrong, including:
                            </p>
                            <ul>
                                <li>
                                    The data from the report (copy&paste).
                                    Please format it properly by surrounding it with three backticks (```):
                                    <br>
                                    <code>
                                        ```<br>
                                        {
                                        "warning": true,
                                        "_debug_parser": null,
                                        "_debug_text": "a",
                                        "items": []
                                        }<br>
                                        ```
                                    </code>
                                    <br>
                                    You can check this by looking at the preview before submitting.
                                </li>
                                <li>What's wrong with the result</li>
                                <li>From where you got the data. (e.g copy pasted from the EVE inventory window)</li>
                            </ul>
                            <p>
                                I'll try to fix the issue.
                            </p>
                            <form action="{{ route("treelib.debugParsers") }}" method="POST" target="_blank">
                                @csrf
                                <div class="form-group">
                                    <label for="text">Text:</label>
                                    <textarea class="form-control" rows="10" name="text" id="text"></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary">Generate Report</button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@stop