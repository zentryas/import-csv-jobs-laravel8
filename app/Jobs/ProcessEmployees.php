<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Employee;
use Illuminate\Bus\Batchable;

class ProcessEmployees implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $employeeData;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($employeeData)
    {
        $this->employeeData = $employeeData;
        // dd($this->employeeData);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->employeeData as $employeeData) {
            $employee = new Employee();
            $employee->nama             = $employeeData['Nama'];
            $employee->email            = $employeeData['Email'];
            $employee->no_telepon       = $employeeData['No Telepon'];
            $employee->website          = $employeeData['Website'];
            $employee->save();
        }
    }
}
