@extends('voyager::master')
@section('head')

@endsection
@section('content')
<div class="container">
    <div class="well">
        <h1>Welcome</h1>
        <p>Please Pick a project Below to Continue</p>
    </div>
</div>

<div class="container">
    <div class="row">
        <table class="table" id="projects">
            <thead>
                <tr>
                    {{-- <th>Id</th> --}}
                    <th>Name</th>
                    <th>Project Manager</th>
                    <th>Began</th>
                    <th>Ends</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($projects as $project)
                <tr>
                    {{-- <td>{{$project->id}}</td> --}}
                    <td>{{$project->name}}</td>
                    <td>{{ \App\User::where('id', $project->pm)->first()->name}}</td>
                    <td>{{$project->start_date}}</td>
                    <td>{{$project->end_date}}</td>
                    <td>
                        <a href="{{route('client.surveys', $project->id)}}"><button
                                class="btn btn-dark">Continue</button></a>
                    </td>

                </tr>

                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection