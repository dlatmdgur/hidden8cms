@extends('layouts.mainlayout')


@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Logs</h2>
            </div>
            <div class="pull-right">
                @can('logs-create')
                    <a class="btn btn-success" href="{{ route('logs.create') }}"> Create New Log</a>
                @endcan
            </div>
        </div>
    </div>


    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif


    <table class="table table-bordered">
        <tr>
            <th>No</th>
            <th>Name</th>
            <th>Details</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($sevenPokerLogs as $log)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $log->menu }}</td>
                <td>{{ $log->action }}</td>
                <td>
                    <form action="{{ route('logs.destroy',$log->id) }}" method="POST">
                        <a class="btn btn-info" href="{{ route('logs.show',$log->id) }}">Show</a>
                        @can('logs-edit')
                            <a class="btn btn-primary" href="{{ route('logs.edit',$log->id) }}">Edit</a>
                        @endcan


                        @csrf
                        @method('DELETE')
                        @can('logs-delete')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        @endcan
                    </form>
                </td>
            </tr>
        @endforeach
    </table>


    {!! $logs->links() !!}


@endsection
