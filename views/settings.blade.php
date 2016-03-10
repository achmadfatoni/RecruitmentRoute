@extends('app')

@section('page-header')
    <h2>Recruitment Settings</h2>

    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs">
            <li>
                <a href="/">
                    <i class="fa fa-home"></i>
                </a>
            </li>
            <li><span>Recruitment</span></li>
            <li><span>Settings</span></li>
        </ol>

        <div class="sidebar-right-toggle"></div>
    </div>
@endsection

@section('content')

    @include('elements.success-message-partial')
    @include('elements.error-message-partial')

    <div class="row">
        <div class="col-xs-12 col-md-12 col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    <h2 class="panel-title">
                        Recruitment Settings
                    </h2>
                </header>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST"
                          action="{{ url('/recruitment-management/settings') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="form-group">
                            <label class="col-md-2 control-label">Recruitment Key</label>

                            <div class="col-md-8">
                                <input type="text" class="form-control" name="recruitment_key"
                                       value="{{ isset($recruitment_key) ? $recruitment_key : (old('recruitment_key') ? old('recruitment_key') : $auth->user->getRouteKey()) }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-2">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </form>
                </div>

                @if(isset($recruitment_key))
                    <header class="panel-heading">
                        <h2 class="panel-title">
                            Link to share to recruit {{$role->friendly_name}}
                        </h2>
                    </header>
                    <div class="panel-body">
                        <span class="amount reg-list-link"><a id="recruitment_link"
                                                              href='{{ url('/join/' . $recruitment_key) }}'>{{ url('/join/' . $recruitment_key) }}</a></span>
                    </div>
                @endif

            </section>
        </div>
    </div>
@endsection
