<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\UserDetail;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ImportUser;


class FileRead 
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {

                $data = $this->data;
                $inputData = Excel::import(new ImportUser(), $data)->toArray(new ImportUser(), $data);
                $totalDataCount = count($inputData);
                $length = count($inputData[0]);
                $chunkSize = 3;
                $output = array_chunk($inputData[0], $chunkSize);
                for ($i = 1; $i < $length; $i++) {
                        $data = [];
                        $data['user_id'] = $output[0][$i][0];
                        $data['name'] = $output[0][$i][1];
                        $data['email'] = $output[0][$i][2];
                        
                         $this->addDataToDb($data);
                }
                return true;

        }catch (Illuminate\Contracts\Filesystem\FileNotFoundException $exception) {
                die("The file doesn't exist");
        }
        
    }
    public function addDataToDb($data)
    {
        UserDetail::insert([
                    'user_id' => $data['user_id'],
                    'name' => $data['name'],
                    'email' => $data['email'],
                ]);
                return true;
    }
}
