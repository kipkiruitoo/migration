@extends('voyager::master')
@section('head')
<style>
    
</style>
@endsection
@section('content')
{{-- <h1>This Page is under development. Do not use it yet</h1> --}}
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
    <div class="container">
        <h2 class="mb-5 text-white">Brief Stats</h2>
        <div class="header-body">
            <div class="row">
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Total Number of Respondents</h5>
                                    <span class="h2 font-weight-bold mb-0">{{$total}}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                        <i class="fas fa-chart-bar"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span class="text-success mr-2"><i class="fa fa-arrow-up"></i></span>
                                <span class="text-nowrap">All Respondents assigned to this project</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Active Respondents</h5>
                                    <span class="h2 font-weight-bold mb-0">{{$active}}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                        <i class="fas fa-chart-pie"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span class="text-success mr-2"><i class="fa fa-arrow-down"></i> {{(round($active/$total, 4))*100}}%</span>
                                <span class="text-nowrap">Of Total Number of Respondents</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">InActive Respondents</h5>
                                    <span class="h2 font-weight-bold mb-0">{{$inactive}}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-yellow text-white rounded-circle shadow">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span class="text-warning mr-2"><i class="fas fa-arrow-down"></i>{{(round($inactive/$total, 4))*100}}%</span>
                                <span class="text-nowrap">Of Total Number of Respondents</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">With Interviews</h5>
                                    <span class="h2 font-weight-bold mb-0">{{$withinterviews}}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                        <i class="fas fa-percent"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> {{(round($withinterviews/$total, 4))*100}}%</span>
                                <span class="text-nowrap">Of Total Number of Respondents</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <a href="{{route('getexcel')}}" class="btn btn-warning">Download Excel Format</a>
            <button type="button" data-toggle="modal" data-target="#uploadModal" class="btn btn-dark btn-add-new"><i
                    class="voyager-list-add"></i><span> Import from Excel</span></button>
                    
           <form action="{{route('deactivaterespondents')}}" method="POST" >
           @csrf
               <input type="hidden" value="{{$project}}" name="project">
              <button type="submit" class="btn btn-danger">Deactivate All Respondents</button>
           </form>
        </div>
    </div>
    <hr>
    <div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Choose Excel file to upload</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{route('uploadrespondents')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" value="{{$project}}" name="project">
                            <div class="form-group">
                                <input class="form-control-file" type="file" name="upfile" id="upfile">
                            </div>
        
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Upload</button>
        
                        </div>
                    </form>
                </div>
            </div>
        </div>
<div class="container">
<form action="{{route('respondents.query')}}" method="post">
@csrf
<small>You can manage respondent quotas by activating or deactivating respondents below </small>
<input type="hidden" name="pr" value="{{$project}}">
    <div class="row">
    
    
        <div class="col col-md-3">
            <div class="form-group">
            <label for="action">Action</label>
            <select name="action" class="form-control" id="action">
                <option value="Active">Activate</option>
                <option value="InActive">DeActivate</option>
            </select>
        </div>
        </div>
        <table id="dynamicTable">
         
                <tr>
                    <td>
<div class="col col-md-4">
    <div class="form-group">
        <label for="q[0][where]">Where</label>
        <select name="q[0][where]" class="form-control" id="q[0][where]">
            @foreach($columns as $column)
            <option value="{{$column}}">{{$column}}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="col col-md-4">
    <div class="form-group">
        <label for="equals"> </label>
        <select name="q[0][equals]" class="form-control" id="q[0][equals]">
            <option value="="> Equals</option>
            <option value="<>"> Not Equals </option>
            <option value="LIKE"> Like </option>
        </select>
    </div>
</div>
<div class="col col-md-4">
    <div class="form-group">
        <label for="q"> Query</label>
        <input type="text" name="q[0][q]" id="q[0][q]" class="form-control">
    </div>
</div>
                    </td>

                </tr>
                
            </tbody>
        </table>
     
   
</div>
<hr>
<div class="row">
    <button type="button" name="add" id="add" class="btn btn-dark">+ row</button></td>
</div>
<div class="row">
<div class="col col-md-4">
        <button class="btn btn-dark" type="submit">Run Query </button>
</div>
    
</div>
 </form>
<hr>
<div class="row">
<div class="col col-md-12">
{{$respondents->links()}}
</div>

<table class="table" >
<thead>
<tr>
    <th>#</th>
    <th>Name</th>
    <th>Phone Number</th>
    <th>Phone Number 2</th>
    <th>Phone Number 3</th>
    <th>Sex</th>
    <!-- <th>lsm</th> -->
    <th>occupation</th>
    <th>Status</th>
    <th>Actions</th>
</tr>
</thead>
<tbody>
@foreach($respondents as $respondent)
<tr>
<td>{{$respondent->id}}</td>
<td>{{$respondent->name}}</td>
<td>{{$respondent->phone}}</td>
<td>{{$respondent->phone1}}</td>
<td>{{$respondent->phone2}}</td>
<td>{{$respondent->sex}}</td>
<td>{{$respondent->occupation}}</td>
<td>{{$respondent->status}}</td>
<td>
<a href="{{route('voyager.respondents.edit', $respondent->id)}}"><button class="btn btn-success">Edit</button></a>
</td>
</tr>
@endforeach
</tbody>
</table>
</div>
</div>
<script src="https://unpkg.com/jquery@3.3.1/dist/jquery.min.js"></script>

<script type="text/javascript">




    var i = 0;

       

    $("#add").click(function(){

   

        ++i;

        

        $("#dynamicTable").append('<tr><td><div class="col col-md-4"><div class="form-group"><label for="q['+i+'][where]">Where</label><select name="q['+i+'][where]" class="form-control" id="action">@foreach($columns as $column)<option value="{{$column}}">{{$column}}</option>@endforeach</select></div></div><div class="col col-md-4"><div class="form-group">                    <label for="q['+i+'][equals]"> </label><select name="q['+i+'][equals]" class="form-control" id="q['+i+'][equals]"><option value="="> Equals</option><option value="<>"> Not Equals </option><option value="LIKE"> Like </option></select></div></div><div class="col col-md-4"><div class="form-group"><label for="q['+i+'][q]"> Query</label><input type="text" name="q['+i+'][q]" id="q['+i+'][q]" class="form-control"></div></div></td><td><button type="button" class="btn btn-danger remove-tr">Remove</button><td></tr>');

    });

   

    $(document).on('click', '.remove-tr', function(){  

         $(this).parents('tr').remove();

    });  

   

</script>

@endsection