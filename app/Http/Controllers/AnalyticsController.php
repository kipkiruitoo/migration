<?php

namespace App\Http\Controllers;

use  AidynMakhataev\LaravelSurveyJs\app\Models\Survey;
use Illuminate\Http\Request;
use  AidynMakhataev\LaravelSurveyJs\app\Models\SurveyResult;

class AnalyticsController extends Controller
{
    //

    public function index($id, $slug)
    {
        $survey = Survey::where('id', $id)->where('slug', $slug)->first();

        $name = $survey->name;
        $survey_id = $survey->id;
        $survey = Survey::where('id', $id)->where('slug', $slug)->pluck('json');


        // var_dump($results);
        $survey = $survey->toArray();
        // $results = $results->toArray();
        // var_dump($results);
        return view('analytics.index', compact('survey',  'name', 'survey_id'));
    }

    public function another_client($id, $slug)
    {
        $survey = Survey::where('id', $id)->where('slug', $slug)->first();

        $name = $survey->name;
        $survey_id = $survey->id;
        $survey = Survey::where('id', $id)->where('slug', $slug)->pluck('json');


        // var_dump($results);
        $survey = $survey->toArray();
        // $results = $results->toArray();
        // var_dump($results);
        return view('analytics.another_client', compact('survey',  'name', 'survey_id'));
    }


    public function results($survey)
    {
        $results = SurveyResult::where('survey_id', $survey)->get()->pluck('json');
        $results = $results->toArray();

        return response()->json($results);
    }
}
