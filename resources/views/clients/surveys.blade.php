@extends('voyager::master')
@section('head')

@endsection
@section('content')
<div class="container">
    <div class="well">
        <h1>Welcome</h1>
        <p>Please Pick a Survey  to Continue</p>
    </div>
</div>

<div class="container">
    <div class="row">
        <table class="table" id="projects">
            <thead>
                <tr>
                   
                    <th>Name</th>
             
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($surveys as $survey)
                <tr>
                    {{-- <td>{{$project->id}}</td> --}}
                    <td>{{$survey->name}}</td>
                  
                    <td>
                      <a href="{{route('analytics', [$survey->id, $survey->slug])}}" target="__blank"><button class="btn btn-dark">
                                Analytics</button></a>
                    </td>

                </tr>

                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection