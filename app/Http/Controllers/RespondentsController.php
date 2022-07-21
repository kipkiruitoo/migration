<?php

namespace App\Http\Controllers;

use App\Respondent;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RespondentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($project)
    {
        $columns = Schema::getColumnListing('respondents');
        $columns =
            \array_diff($columns, ["id", "project", "name", "phone", "phone1", "phone2", "phone3", "email", "created_at", "updated_at", "deleted_at", "type", "locked", "lock_agent", "res_d"]);

        // dd($columns);
        $active = DB::table('respondents')->where('project', $project)->where('status', 'Active')->count();
        $inactive
            = DB::table('respondents')->where('project', $project)->where('status', '<>', 'Active')->count();
        $total
            = DB::table('respondents')->where('project', $project)->count();
        $respondents = Respondent::where('project', $project)->paginate(30);
        $withinterviews
            = DB::table('respondents')->where('project', $project)->whereExists(function ($query) {
                $query->select(DB::raw('*'))
                    ->from('interviews')
                    ->whereColumn('interviews.respondent', 'respondents.id');
            })->count();


        return view('respondents.index', compact('respondents', 'columns', 'active', 'withinterviews', 'inactive', 'total', 'project'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    
      public function deactivateallrespondents(Request $request)
    {
 
        Respondent::where('project', $request->project)->update(['status' => 'Inactive']);


    return redirect()->back();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Respondent  $respondent
     * @return \Illuminate\Http\Response
     */
    public function show(Respondent $respondent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Respondent  $respondent
     * @return \Illuminate\Http\Response
     */
    public function edit(Respondent $respondent)
    {
        //
    }
    public function runquery(Request $request)
    {
        // dd($request);

        $action = $request->action;
        $q = $request->q;

        $project = $request->pr;



        $finalarray = [];

        foreach ($q as $query) {
            $narr = [$query['where'], $query['equals'], $query['q']];
            array_push($finalarray, $narr);
        }


        DB::enableQueryLog();
        $affectedRows =   DB::table('respondents')->where('project',  $project)->where($finalarray)->update(['status' => $action]);

        return redirect()->back()->with(['message' => "Query Completed Successfully " . $affectedRows . " Respondents Affected", 'alert-type' => 'success']);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Respondent  $respondent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Respondent $respondent)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Respondent  $respondent
     * @return \Illuminate\Http\Response
     */
    public function destroy(Respondent $respondent)
    {
        //
    }
}
