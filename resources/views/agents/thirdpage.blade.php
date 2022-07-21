@extends('voyager::master')
@section('head')
<script src="https://unpkg.com/jquery@3.3.1/dist/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
    .call {
        background: #293138;
    }

    .call-animation {
        background: #fff;
        width: 140px;
        height: 140px;
        position: relative;
        margin: 0 auto;
        border-radius: 100%;
        border: solid 5px #fff;
        animation: play 2s ease infinite;
        -webkit-backface-visibility: hidden;
        -moz-backface-visibility: hidden;
        -ms-backface-visibility: hidden;
        backface-visibility: hidden;

    }

    img {
        width: 130px;
        height: 130px;
        border-radius: 100%;
        position: absolute;
        left: 0px;
        top: 0px;
    }

    @keyframes play {

        0% {
            transform: scale(1);
        }

        15% {
            box-shadow: 0 0 0 5px rgba(255, 255, 255, 0.4);
        }

        25% {
            box-shadow: 0 0 0 10px rgba(255, 255, 255, 0.4), 0 0 0 20px rgba(255, 255, 255, 0.2);
        }

        25% {
            box-shadow: 0 0 0 15px rgba(255, 255, 255, 0.4), 0 0 0 30px rgba(255, 255, 255, 0.2);
        }

    }

    .rescheduleform {
        padding: 20px;
    }
</style>
@endsection
@section('headbar')
<div id="hellobar-bar" class="regular animated infinite pulse">
    <div class="hb-content-wrapper">
        <div class="hb-text-wrapper">
            <div class="hb-headline-text">
                <p><span> <i class="fa fa-headset"></i>
                        <h4 id="barmessage">Initiating Call</h4>
                    </span></p>
            </div>
        </div>
    </div>
</div>
@endsection
@section('content')
@php
if($respondent[0]->project != 60){

// $feedbacks = (object) $feedbacks;
// dd($feedbacks);
}

// lock respondents here for 1 hour



@endphp

@if (session()->has('message'))
<script>
    toastr["warning"]('Respondents with unfinished interviews appearing first');
</script>
@endif

<div class="container">
    <div class="row">
        <div class="col col-md-6">
            <form action="{{route('agentthirdpage')}}" method="POST">
                <input type="hidden" name="project" value="{{$project}}">
                @csrf
                <input type="hidden" name="survey" value="{{$survey}}">
                <button class="btn btn-dark" type="submit"> <span><i class="fa fa-refresh"
                            aria-hidden="true"></i></span> Get New Respondent</button>

            </form>
        </div>
        <div class="col col-md-4 offset-md-4">
            @if (!session()->has('searched'))
            <button type="button" data-toggle="modal" data-target="#searchrespondent" class="btn btn-success"> <span><i
                        class="fa fa-search" aria-hidden="true"></i></span> Search for a Respondent</button>
            <small>Searching only works with the primary number</small>
            @else

            {{-- <button type="button" onclick="window.history.back()" class="btn btn-dark"><span><i class="fa fa-arrow-circle-left"></i></span>  Back</button> --}}
            <small>search is currently unavailable</small>
            @endif
        </div>


        @if (session()->has('scheduled'))
        <button class="btn btn-warning" data-toggle="modal" data-target="#reschedule">Reschedule Call</button>
        @endif
        @if (session()->has('newrecruit'))
        <button class="btn btn-warning" data-toggle="modal" data-target="#reschedule">Reschedule Call</button>
        @endif
        @if (session()->has('message'))
        <div class="alert alert-success" role="alert">
            <p style="color: black">You have an unfinished interview with this respondent but not scheduled</p>
        </div>
        @endif
        @if (session()->has('newrecruit'))
        <div style="width: 500px" class="alert alert-warning" role="alert">
            <p style="color: white">This Respondent was recently recruited in the field.</p>
        </div>
        @endif
        
    </div>
    <hr>
    
     <div class="alert alert-danger" role="alert">
            <p style="color: black">Dear Agent, to address data privacy concerns, respondent phone numbers have been masked from the system. Calling Respondents can be done by clicking on the Call Respondent button below the Start survey button. Kindly change your phone number to the extension assigned in your profile settings. </p>
     </div>
    <hr>
    <div class="row">
        {{-- @if ($respondent[0]->project == 61)
            <button class="btn btn-success" onclick="window.open(`{{route('dialer')}}`,'Dialer','width=600,height=400')">Open
        Dialer</button></a>

        <p style="color: red">Please use 3cx if working from the Office</p>
        @endif --}}

    </div>
    <br>
    {{-- {{App\Projects::find($respondent[0]->project)->name}} --}}
    <div class="row">
        <div class="col-md-6">
            <form action="{{route('survey-manager.run', $survey)}}" method="POST">
                <div class="form-group">
                    @if (session()->has('scheduled'))
                    <div class="alert alert-success" role="alert">
                        <p style="color: black">You had scheduled this respondent to this time</p>

                    </div>
                    @endif
                    <div class="well">
                        <label for="respondent">Selected Respondent</label>
                        <h5 id="resname">{{$respondent[0]->name}}</h5>
                        <hr />
                        <h6>County:<span id="rescounty"> {{$respondent[0]->county}}</span></h6>
                        <hr />
                        <h6>Occupation:<span id="rescounty"> {{$respondent[0]->occupation}}</span></h6>
                        <hr />
                        <h6>Town:<span id="rescounty"> {{$respondent[0]->town}}</span></h6>
                        <hr />
                        <h6>Setting:<span id="rescounty"> {{$respondent[0]->lsm}}</span></h6>
                        <hr />
                        <hr />
                        <h5>Phone Numbers </h5>
                        <div class="form-group">
                            <label for="resphone">First Phone Number: </label>
                            <input id="resphone" type="radio" name="phonenumber" required
                                value="{{$respondent[0]->phone}}"><span> Phone 1</span>
                        </div>
                        @if ($respondent[0]->phone1)
                        <div class="form-group">
                            <label for="phone1">Second Phone Number: </label>
                            <input id="phone1" type="radio" name="phonenumber" required
                                value="{{$respondent[0]->phone1}}"><span>Phone 2</span>
                        </div>
                        @endif
                        @if ($respondent[0]->phone2)
                        <div class="form-group">
                            <label for="phone1">Third Phone Number: </label>
                            <input id="phone1" type="radio" name="phonenumber" required
                                value="{{$respondent[0]->phone2}}"><span> Phone 3</span>
                        </div>
                        @endif

                        <hr />
 
                        @php
                        if (App\Interview::where('respondent', $respondent[0]->id)->latest()->first()) {
                        $lastinterviewdate = App\Interview::where('respondent',
                        $respondent[0]->id)->latest()->first()->date;
                        }else {
                        $lastinterviewdate = "Seems No interview yet";
                        }
                        @endphp

                        <h6>Last Interview : <span id="lin">{{ $lastinterviewdate }}</span></h6>
                        {{-- <button class="btn btn-dark btn-small">Manage Phone Numbers</button> --}}



                    </div>


                </div>


                @csrf
                <input type="hidden" name="respondent" class="respondent" value="{{$respondent[0]->id}}">
                <input type="hidden" name="callsession" required id="callsession" />
                <button type="submit" id="startsurvey" class="btn btn-dark"> Start
                    Survey <span><i class="fa fa-play-circle"></i></span></button>
                    
                    
            </form>
            
            <button onclick="callRes()" class="btn btn-success btn-small">Call
            Respondent <span><i class="fa fa-phone"></i></span></button>
            {{-- <form action="{{route('makecall')}}" method="POST"> --}}
            {{-- <input type="hidden" name="respondent" value="{{$respondent[0]->id}}"> --}}
            {{-- @csrf --}}
            {{-- @if ($respondent[0]->project != 61) --}}
            {{-- <button class="btn btn-dark" type="button" data-toggle="modal" data-target="#calling" onclick="make_call()" > <span><i class="fa fa-phone"> </i> </span>Make Call</button> --}}
            {{-- @endif --}}

            {{-- </form> --}}
        </div>
        <div class="col-md-6">
            <div class="well">
                <h5>Last Feedback:</h5>
                <button class="btn btn-dark btn-small" data-toggle="modal" data-target="#feedback"> <span><i
                            class="fa fa-plus"></i></i></span> Add
                    Feedback</button>
                {{-- <button class="btn btn-dark btn-small" data-toggle="modal" data-target="#schedule"> <span><i class="fa fa-calendar "></i></i></span>
                    Schedule Call</button> --}}
                <div class="new-table">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Comment</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($feedback as $feedback)
                            <tr>
                                <td>
                                    {{ $feedback->created_at}}
                                </td>
                                <td>
                                    {{ $feedback->feedback}}
                                </td>
                                <td>
                                    {{ $feedback->other }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>
    <div class="row">


    </div>

</div>
<!-- feedback modal -->
<!-- Modal -->
<div class="modal fade" id="feedback" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Feedback for this Respondent:
                    {{$respondent[0]->name}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="feedbackform" method="POST">
                @csrf
                <div class="modal-body">

                    <div class="form-group">
                        <label for="fback">Choose Feedback</label>
                        <select class=" form-control" required name="feedback" id="fback">
                            @foreach ($feedbacks as $item)
                            <option value="{{$item->name}}">
                                {{$item->name}}
                            </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="project" value="{{ $project }}">
                    </div>
                    <div class="form-group">
                        <label for="other">Additional Feedback</label>
                        <textarea class="form-control" id="other" cols="10" rows="10" name="other"></textarea>
                    </div>
                    <input type="hidden" id="respondent" name="respondent" value="{{$respondent[0]->id}}">
                    <input type="hidden" name="survey" value={{$survey}}>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button onclick="addfeedback()" class="btn btn-dark"><span><i class="fa fa-save"></i> Save</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="searchrespondent" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Search for a Respondent </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('agentthirdpage')}}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">

                        <input type="hidden" name="project" value="{{$project}}">
                        <input type="hidden" name="survey" value="{{$survey}}">
                        <input type="hidden" name="thirdpage" id="thirdpage">
                        <!-- <input type="text" class="form-control"> -->
                    </div>
                    <div class="form-group">
                        <label for="phone">Search by Phone Number</label>
                        <input type="tel" id="phone" placeholder="07xxxxxxx" class="form-control" name="phone">
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-dark " name="search" value="Search  for Respondent" />
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="reschedule" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Reschedule This Interview for:
                    {{$respondent[0]->name}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="rescheduleform" id="reschedule" method="POST" action="{{route('reschedule')}}">
                @csrf

                <div class="form-group">
                    <label for="date">Select Date</label>
                    <input type="date" required id="date" name="date" class="form-control">
                    <small>This respondent will start appearing a few minutes before the time selected</small>

                    <div class="form-group">
                        <label for="time">Select time to Continue</label>
                        <input type="time" required id="time" name="time" class="form-control">
                        <small>This respondent will start appearing a few minutes before the time selected</small>
                    </div>
                    <input type="hidden" name="respondent" name="respondent" value="{{$respondent[0]->id}}">

                    @php
                    // dd($survey['id']);
                    @endphp
                    {{-- <input type="hidden" name="survey" value="{{$survey['id']}}"> --}}
                    <div class="form-group">
                        <button type="submit" class="btn btn-success">Reschedule</button>
                    </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="schedule" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Schedule Call for this Respondent:
                    {{$respondent[0]->name}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="scheduleform" method="POST">
                @csrf
                <div class="modal-body">

                    <div class="form-group">
                        <label for="schedule">Schedule Call</label>

                        <input class="form-control" type="datetime-local" name="time" id="schedule">

                        <small>the respondent will reappear again at the scheduled time</small>
                    </div>
                    <input type="hidden" name="project" value="{{ $project }}">
                </div>
                <input type="hidden" id="respondent" name="respondent" value="{{$respondent[0]->id}}">
                <input type="hidden" name="survey" value={{$survey}}>


        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button onclick="schedule()" class="btn btn-dark"><span><i class="fa fa-save"></i> Schedule</span> </button>
        </div>
        </form>
    </div>
</div>
</div>
{{-- Calling Card --}}

{{-- Calling Card --}}
<script src="{{asset('js\app.js')}}"></script>
<script>
    window.onload = function () {
        var count = localStorage.getItem("count");

    }
    var timesRun = 0;
    interval = window.setInterval(function () {
        timesRun += 1;
        if (timesRun === 3) {
            clearInterval(interval);
        }
        refreshuser({{$respondent[0]->id}});
    }, 2500);

</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.0/axios.js"></script>
<script type="text/javascript">
    // For adding the token to axios header (add this only one time).
    var token = document.head.querySelector('meta[name="csrf-token"]');
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;

    function addfeedback() {
        var feedback = document.getElementById("fback").value
        var respondent = document.getElementById("respondent").value
        var additionalfeedback = document.getElementById("other").value
        var feedbackform = document.getElementById("feedbackform")
        feedbackform.addEventListener("submit", function (event) {
            event.preventDefault();
        })
        console.log(feedback)

        // send contact form data.
        axios.post('/add-feedback', {
            feedback: feedback,
            respondent: respondent,
            other: additionalfeedback
        }).then((response) => {
            console.log(other)
            $('.modal').modal('hide');
            var timesRun = 0;
            interval = window.setInterval(function () {
                timesRun += 1;
                if (timesRun === 3) {
                    clearInterval(interval);
                }
                refreshuser({{$respondent[0]->id}});
            }, 1000);
        }).catch((error) => {
            console.log(error.response.data)
        });
    }

</script>
<script>
    $(document).ready(function () {
        $('.js-example-basic-single').select2();
        $('#fbacktb').dataTable();
        // getnewuser({{ $project }}, {{$survey}})
    });


    function getnewuser(project, survey) {
        var feedback;
        var tbhtml = document.querySelector('.new-table');
        var thead;
        var tbody;
        var tlast;
        axios.get('/get-user/' + project + '/' + survey).then((response) => {
            console.log(response)
            document.querySelector('#resname').innerHTML = response.data[0][0].name;
            document.querySelector('#resphone').innerHTML = response.data[0][0].phone;
            document.querySelector('#rescounty').innerHTML = response.data[0][0].county;
            document.querySelector(".respondent").value = response.data[0][0].id;
            document.querySelector("#respondent").value = response.data[0][0].id;
            if (response.data[2][0] === undefined || response.data[2][0].length < 1) {
                document.querySelector("#lin").innerHTML = "Seems No interview yet";

            } else {
                document.querySelector("#lin").innerHTML = response.data[2][0].created_at
            }


            feedback = response.data[1];




            thead =
                "<table id='fbacktb' class='table'><thead><tr><th>Date</th>  <th>Type</th><th>Comment</th></tr></thead><tbody>";

            feedback.forEach(element => {
                // console.log(typeof element.other)
                // if (element.other ) {

                // }
                date = new Date(element.created_at)
                tbody += "<tr><td>" + date.toDateString() + "</td><td>" + element.feedback + "</td>" +
                    element.other + "</tr>";
            });


            tlast = "</tbody></table>";

            tbhtml.innerHTML = thead + tbody + tlast;


            console.log(feedback);


            // $('#fbacktb').DataTable({
            //     "data": feedback,
            //     columns: [
            //         {"data": "feedback"},
            //         {"data": "created_at"}
            //     ]
            // });
        }).catch((error) => {
            console.log(error)
        });
    }

    function refreshuser(id) {
        var feedback;
        var tbhtml = document.querySelector('.new-table');
        axios.get('/refresh-user/' + id).then((response) => {
            console.log(response);
            document.querySelector('#resname').innerHTML = response.data[0].name;
            document.querySelector('#resphone').innerHTML = response.data[0].phone;
            document.querySelector('#rescounty').innerHTML = ' ' + response.data[0].county;
            document.querySelector(".respondent").value = response.data[0].id;
            document.querySelector("#respondent").value = response.data[0].id;
            if (response.data[2][0] === undefined || response.data[2][0].length < 1) {
                document.querySelector("#lin").innerHTML = "Seems No interview yet";

            } else {
                let date = new Date(response.data[2][0].created_at)
                document.querySelector("#lin").innerHTML = date.toDateString()
            }


            feedback = response.data[1];

            let tbody = '';


            let thead =
                "<table id='fbacktb' class='table'><thead><tr><th>Date</th>  <th>Type</th><th>Comment</th></tr></thead><tbody>";

            feedback.forEach(element => {
                console.log( typeof element.other)
                date = new Date(element.created_at)
                tbody += "<tr><td>" + date.toDateString() + "</td><td>" + element.feedback + "</td>" +
                    "<td>" + element.other + "</td>" + "</tr>";
            });


            let tlast = "</tbody></table>";

            tbhtml.innerHTML = thead + tbody + tlast;


            console.log(feedback);
        }).catch((error) => {
            console.log(error)
        });
    }

    function make_call() {
        // console.log("i have been called");
            var respondent = {{$respondent[0]->id}};
            // console.log(respondent);
            var sessionInput = document.querySelector("#callsession");
            // let hellobar = document.querySelector('#hellobar-bar');
            var url = '{{route('makecall')}}';
            // hellobar.style.display = "table";
            // var barmessage = document.querySelector('#barmessage');
            var startsurvey = document.querySelector("#startsurvey");

        axios.post(url, {respondent: respondent}).then(response=>{
            console.log(response);
            let sessionId = response.data.sessionId;
            console.log(sessionId);
            toastr["success"]('Call successfully initiated, you should receive a call on your cell soon')
            sessionInput.value = sessionId;
            startsurvey.disabled = false;
            Echo.channel('call_status' + sessionId).listen('ChangeCallStatus', (e)=>{
                // alert(e)
                // let statusmessage = document.querySelector("#callstatus");
                // let animationdiv = document.querySelector(".call-animation");
                console.log(e)

                // statusmessage.innerHTML = e.callstatus
                if(e.callstatus === "NotAnswered"){
                    // statusmessage.style.cssText += 'color: red;';
                    // animationdiv.style.background = "red";
                    // hellobar.style.background = "#55040F";
                    toastr["error"]("Phone Was not Answered . Please try again")
                    // barmessage.innerHTML = "Phone not Answered . Please try again";
                    //  animationdiv.style.border = "solid 5px red";
                    // statusmessage.innerHTML = "Phone not Answered"
                }else if(e.callstatus === "Dialing"){
                    // statusmessage.style.cssText += 'color: yellow;';
                    // animationdiv.style.border = "solid 5px #57a9c1";
                    // hellobar.style.background = "#61045A";
                    toastr["info"]("Dialing the Respondent");
                    // barmessage.innerHTML = "Dialing the Respondent";
                    // animationdiv.style.background = "#57a9c1";
                    // statusmessage.innerHTML = "Dialing the Respondent"
                }else if (e.callstatus === "Bridged") {
                    // statusmessage.style.cssText += 'color: green;';
                    // animationdiv.style.border = "solid 5px green";
                    toastr["success"]("Connected with  the Respondent");
                //    hellobar.style.background = "#030B37";
                //     barmessage.innerHTML = "Connected with the Respondent";
                //     animationdiv.style.background = "green";
                //     statusmessage.innerHTML = "Connected with the Respondent"
                }else if (e.callstatus === "Completed") {
                    // statusmessage.style.cssText += 'color: white;';
                    // animationdiv.style.border = "solid 5px green";
                    //  hellobar.style.background = "#033018";
                    toastr["success"]("Phone Call Completed");
                    // barmessage.innerHTML = "Call Completed";
                    // hellobar.style.display = "None";
                    // animationdiv.style.background = "green";
                    // statusmessage.innerHTML = "Connected with the Respondent"
                }
            })
        })
    }
    
    
    function callRes(){
       let phonenumber = "{{$respondent[0]->phone}}"
       let extension = '{!!Auth::user()->phone!!}';
       if(extension.length > 4){
          toastr["error"]("Unable to initiate Call, Kindly update your extension in your profile")
       }
       else{
       axios.get(`/calls.php?exten=IAX2/${extension}&number=890${phonenumber}`).then((res)=>{
           console.log(res);
           toastr["success"]("Call Initiated Successfully, you should receive a call on your Zoiper Extension  ")
       }).catch((err) => {
            console.error(err);
       })
       }
    }


</script>
@endsection