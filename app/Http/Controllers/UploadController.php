<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessEmployees;
use Exception;
use Log;
use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use App\Models\JobBatch;

class UploadController extends Controller
{
    public function index()
    {
        return view('upload');
    }

    public function uploadFile(Request $request)
    {
        try {
            // dd($request->all());
            if ($request->has('csvFile')) {
                
                $fileName     = $request->csvFile->getClientOriginalName();
                $fileWithPath = public_path('uploads') . '/' . $fileName;

                if (!file_exists($fileWithPath)) {
                    $request->csvFile->move(public_path('uploads'), $fileName);
                }

                $header        = null;
                $dataFromcsv   = array();
                $records       = array_map('str_getcsv', file($fileWithPath));

                // dd($records);
                foreach ($records as $record) {
                    if (!$header) {
                        $header = $record;
                    } else {
                        $dataFromcsv[] = $record;
                    }
                }

                // dd($header, $dataFromcsv);

                $dataFromcsv    = array_chunk($dataFromcsv, 1000);
                $batch          = Bus::batch([])->dispatch();
                // dd($dataFromcsv);
                set_time_limit(-1);
                foreach ($dataFromcsv as $index => $dataCsv) {
                    foreach ($dataCsv as $data) {
                        // dd($header, $data) sama;
                        $employeeData[$index][] = array_combine($header, $data);
                    }
                    $batch->add(new ProcessEmployees($employeeData[$index]));
                    // ProcessEmployees::dispatch($employeeData[$index]);
                }
                // dd($employeeData) data array per 1000;
                session()->put('lastBatchId', $batch->id);
                return redirect('/progress?id=' . $batch->id);
            }
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }
    }

    public function progress()
    {
        $jobBatch       = JobBatch::all()->sortDesc();

        return view('progress', compact('jobBatch'));
    }

    public function progressData(Request $request)
    {
        try {
            $batchId    = $request->id ?? session()->get('lastBatchId');
            if (JobBatch::where('id', $batchId)->count()) {
                $response = JobBatch::where('id', $batchId)->first();
                return response()->json($response);
            }
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }
    }
}
