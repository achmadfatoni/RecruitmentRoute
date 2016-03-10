@extends('app')

@section('page-header')

    <h2>Leaderboard </h2>

    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs">
            <li>
                <a href="index.html">
                    <i class="fa fa-home"></i>
                </a>
            </li>
            <li><span>Leaderboard</span></li>
        </ol>

        <div class="sidebar-right-toggle"></div>
    </div>
@endsection

@section('content')
    <section class="panel">
        <header class="panel-heading">
            <div class="panel-actions">
                <a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
                <a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a>
            </div>

            <h2 class="panel-title">Leaderboard</h2>
        </header>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="{{isset($table_class) ? $table_class : 'table table-bordered table-striped table-condensed mb-none'}}">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Recruitment</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($data as $itm)
                        <tr>
                            <td>
                                <a href="/recruitment-management/list-recruitments/{{ $itm->id }}">{{ $itm->name }}</a>
                            </td>
                            <td>{{ $itm->email }}</td>
                            <td>{{ $itm->total }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection