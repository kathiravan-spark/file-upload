<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Jobs\FileRead;
use App\Imports\ImportUser;
use Maatwebsite\Excel\Facades\Excel;
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

    public function index()
    {
        return view('welcome');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf,xls,csv|max:2048',
        ]);
    
        $fileName = time().'.'.$request->file->extension();
     
        $request->file->move(public_path('uploads'), $fileName);
   
        $this->user->insert(['file-name'=>$fileName]);

        $job = new FileRead($fileName);
                dispatch($job);
               
        return back()
            ->with('success','You have successfully upload file.')
            ->with('file', $fileName);
   
    }

    public function importView(Request $request){
        return view('importFile');
    }

    public function import(Request $request){
        Excel::import(new ImportUser, $request->file('file')->store('files'));
        $request->validate([
            'file' => 'required|mimes:pdf,xls,csv|max:2048',
        ]);
        $fileName = time().'.'.$request->file->extension();
        $request->file->move(public_path('uploads'), $fileName);
        $this->user->insert(['file-name'=>$fileName]);
      
        $job = new FileRead($fileName);
                dispatch($job);

        return redirect()->back()->with('success','You have successfully upload file.');
    }

}
