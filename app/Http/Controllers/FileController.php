<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Jobs\FileRead;
use App\Imports\ImportUser;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\Skip;
use File;


class FileController extends Controller
{
    /**
     * @param User
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function importView(Request $request){
        return view('importFile');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf,xls,csv|max:2048',
        ]);
        $data = $request->file;
        $job = new FileRead($data);
                dispatch($job);
        $fileName = 'file'.time().'.'.$request->file->extension();
        $request->file->move(public_path('uploads'), $fileName);
        $this->user->insert(['file-name'=>$fileName]);
        

        return redirect()->back()->with('success','You have successfully upload file.');
    }

}
