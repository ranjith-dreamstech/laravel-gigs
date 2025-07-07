@extends('installer::app')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <p>Enter Database Details</p>
            <div>
                <a class="btn btn-outline-primary" href="{{ route('setup.requirements') }}">&laquo; Back</a>
            </div>
        </div>
        <form id="database_migrate_form" autocomplete="off">
            <div class="card-body">
                <div class="mb-3">
                    <label for="host">Host <span class="text-danger">*</span></label>
                    <input type="text" name="host" id="host" class="form-control"
                        value="{{ old('host') ?: '127.0.0.1' }}" placeholder="Enter Database Host">
                </div>
                <div class="mb-3">
                    <label for="port">Port <span class="text-danger">*</span></label>
                    <input type="text" name="port" id="port" class="form-control"
                        value="{{ old('port') ?: '3306' }}" placeholder="Enter Database Port. Default Is 3306">
                </div>
                <div class="mb-3">
                    <label for="database">Database Name <span class="text-danger">*</span></label>
                    <input type="text" name="database" id="database" value="{{ old('database') }}" class="form-control"
                        placeholder="Enter Database Name Here">
                    <div class="my-3 d-none" id="reset_database_switcher">
                        <input class="form-check-input"
                            type="checkbox"
                            role="switch"
                            id="reset_database"
                            name="reset_database"
                            {{ old('reset_database') ? 'checked aria-checked=true' : 'aria-checked=false' }}>
                        <label for="reset_database" class="text-danger"><b><small>Database not empty. Are you sure
                                    want to clean this
                                    database?</small></b> </label>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="user">Database User <span class="text-danger">*</span></label>
                    <input autocomplete="off" type="text" name="user" id="user" value="{{ old('user') }}"
                        class="form-control" placeholder="Enter Database User Here">
                </div>
                <div class="mb-3">
                    <label for="password">Database User Password @if (isset($isLocalHost) && !$isLocalHost)
                            <span class="text-danger">*</span>
                        @endif
                    </label>
                    <input autocomplete="new-password" type="password" name="password" id="password"
                        value="{{ old('password') }}" class="form-control" placeholder="Enter Database Password Here">
                </div>
                <div class="mb-3 d-none">
                    <b class="text-success">If you prefer a fresh installation without any dummy data, simply toggle the
                        "Fresh Install" switch.</b>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-end ">

                <button type="submit" id="submit_btn" class="btn btn-lg btn-primary">Setup Database</button>
            </div>
        </form>
       
    </div>
@endsection

@push('styles')
    <link href="{{ asset('frontend/css/bootstrap-toggle.min.css') }}" rel="stylesheet">
    <style>
        .form-switch {
            padding-left: 0px !important;
        }

        .form-check {
            padding-left: 0px !important;
        }

        .toggle.btn.btn-lg {
            width: 212px;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('backend/assets/js/bootstrap-toggle.jquery.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/installer/database.js') }}"></script>
@endpush
