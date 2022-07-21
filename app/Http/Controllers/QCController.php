<?php

namespace App\Http\Controllers;

use App\Interview;
use App\Projects;
use App\QcResult;

use  AidynMakhataev\LaravelSurveyJs\app\Models\Survey;
use App\Respondent;
use App\User;
use Carbon\Carbon;
use Auth;
use Charts;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class QCController extends Controller
{
    public function index()
    {

        $projects = DB::table('projects')->join('qc_projects', 'qc_projects.project_id', '=', 'projects.id')->where('qc_projects.user_id', Auth::user()->id)->get();
        return view('qc.index', compact('projects'));
    }
    public function interviews(Request $request)
    {

        $date = $request->date;
        $survey = $request->survey;
        // $project = Survey::find($survey)->project()->name;
        // echo $date;

        $interviews =  Interview::where([['qcd', 0], ['survey', $survey]])->whereDate('created_at', $date)->get();

        return view('qc.interviews', compact('interviews'));
    }
    public function surveys($id)
    {
        $project = Projects::findOrFail($id)->first();
        $surveys = Survey::where('project', $id)->get();

        return view('qc.surveys', compact('surveys', 'id'))->withProject($project);
        // return view('qc.surveys');
    }
    public function results($survey, $interview)
    {
        $agentid = Interview::where('id', $interview)->first()->agent;
        $respondentid =  Interview::where('id', $interview)->first()->respondent;

        $agent = User::find($agentid);

        // echo $agent;
        $surv = Survey::find($survey);

        $respondent  = Respondent::find($respondentid);

        $interview = Interview::find($interview);


        // echo $respondent;
        $survey = Survey::where('id', 28)->firstOrFail();
        $survey = $survey->toArray();


        // echo $survey . $interview;
        // echo $agentid;
        // echo $respondentid;
        return view('qc.results', compact('respondent', 'agent', 'interview', 'survey', 'surv'));
    }
    public function saveqcresults(Request $request)
    {
        $qcresult = new QcResult();
        $qcresult->json = json_encode($request->json, true);
        $qcresult->interview = $request->interview;
        $qcresult->qc =  $request->qc;
        $qcresult->project = $request->project;

        // echo $qcresult->project;
        // dd($request->json);
        $json = $request->json;
        // dd($json['approval']);
        if ($qcresult->save()) {
            $interview = Interview::find($request->interview);
            $interview->qcd = 1;
            $interview->approved = $json['approval'];
            $interview->save();
            return response()->json(['message' => 'Success', 'data' => $request]);
        } else {
            return response()->json(['message' => 'failed', 'data' => $request]);
        }
    }
    public function selectproject()
    {
        $day =
            Carbon::now();

        $date = $day->toDateString();
        $user = Auth::user();

        if ($user->role_id == 5) {
            $projects = DB::table('projects')->where('end_date', '>', $date)->join('project_supervisor', 'project_supervisor.project_id', '=', 'projects.id')->where('project_supervisor.user_id', $user->id)->get();
        }else{
            $projects = DB::table('projects')->join('qc_projects', 'qc_projects.project_id', '=', 'projects.id')->where('qc_projects.user_id', Auth::user()->id)->get();
        }

       
        return view('qc.select',  compact('projects'));
    }
    public function reports(Request $request)
    {



        $qcresults = QcResult::where('project', $request->project)->get();

        $project = str_replace(' ', '_', Projects::find($request->project)->name);

        // dd($project);
        // echo $request->project;

        // $qcresults = json_decode($qcresults->pluck('json'), true);
        return view('qc.report', compact('qcresults', 'project'));
    }
}
