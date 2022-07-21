<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Projects;
use App\Call;
use App\User;
use App\Interview;
use App\Respondent;

class FilesController extends Controller
{
    public function downloadfiles($interview)
    {
        // $project = trim(Projects::find($project)->name);
        $interview = Interview::find($interview);

        ob_start();
        $calls = \App\Call::where('respondent', $interview->respondent)->where('agent', $interview->agent)->whereNotNull('recording_link')->get()->pluck('recording_link');

        if ($calls->isEmpty()) {
            return redirect()->back()->with(['message' => "There were no audios to process , if you think this is an error?,  Please contact your IT department", 'alert-type' => 'error']);
        }
        // $calls = array_slice($calls->toArray(), 0, 5);
        // dd($calls);
        $public_dir = public_path();
        $respondent = Respondent::find($interview->respondent);
        $agent = User::find($interview->agent);
        $zip = new \ZipArchive;
        $zipname = str_replace(' ', '_', 'interview_' . $interview->id . '_agent_' . $agent->name . '_' . '_respondent_' . $respondent->name . '_' . $interview->created_at) . '.zip';
        // fopen('../storage/' . $zipname . '.zip', 'w+');
        if ($zip->open($public_dir . '/' . $zipname, (\ZipArchive::CREATE | \ZipArchive::OVERWRITE)) === TRUE) {
            // Add file to the zip file
            // $zip->addFile('test.txt');
            // $zip->addFile('test.pdf');
            $i = 0;
            foreach ($calls as $link) {
                // # code...
                echo $link;
                $filename = str_replace(' ', '_', 'interview_' . $interview->id . '_agent_' . $agent->name . '_' . '_respondent_' . $respondent->name) . '_' . $i  . '.mp3';
                // $filename = end($filename);
                // dd(filename)
                // echo $filename . ' <br/>';
                $i++;
                file_put_contents($filename, fopen($link, 'r'));

                $zip->addFile($filename);
                // echo "<embed src =\"$filename\" hidden=\"true\" autostart=\"true\"></embed>";

                // unlink($filename);
            }

            // All files are added, so close the zip file.

        } else {
            echo "could not open zip";
        }

        $zip->close();

        $strFile = file_get_contents($public_dir . '/' . $zipname);

        header("Content-type: application/force-download");
        header('Content-Disposition: attachment; filename="' . $zipname . '"');

        header('Content-Length: ' . filesize($public_dir . '/' . $zipname));
        echo $strFile;
        while (ob_get_level()) {
            ob_end_clean();
        }
        readfile($public_dir . '/' . $zipname);
        exit;
    }
}
