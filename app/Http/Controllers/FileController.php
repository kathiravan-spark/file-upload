<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Jobs\FileRead;
use App\Imports\ImportUser;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\Skip;
use File;
use Pion\Laravel\ChunkUpload\Exceptions\UploadFailedException;
use Storage;
use Illuminate\Http\UploadedFile;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Handler\AbstractHandler;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use App\Models\Media;


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
    public function mediaLibrary()
    {
        return view('medialibrary');
    }

    /**
     * Handles the file upload
     *
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws UploadMissingFileException
     * @throws UploadFailedException
     */
    public function upload(Request $request)
    {  
        // create the file receiver
        $receiver = new FileReceiver("file", $request, HandlerFactory::classFromRequest($request));
        // check if the upload is success, throw exception or return response you need
        if ($receiver->isUploaded() === false) {
        throw new UploadMissingFileException();
        }

        // receive the file
        $save = $receiver->receive();

        // check if the upload has finished (in chunk mode it will send smaller files)
        if ($save->isFinished()) {
        // save the file and return any response you need, current example uses `move` function. If you are
        // not using move, you need to manually delete the file by unlink($save->getFile()->getPathname())
        return $this->saveFile($save->getFile(), $request);
        }

        // we are in chunk mode, lets send the current progress
        /** @var AbstractHandler $handler */
        $handler = $save->handler();

        return response()->json([
        "done" => $handler->getPercentageDone(),
        'status' => true
        ]);
    }
    /**
     * Saves the file
     *
     * @param UploadedFile $file
     *
     * @return JsonResponse
     */
    protected function saveFile(UploadedFile $file, Request $request)
    {
        
        $fileName = $this->createFilename($file);
        $mime_original = $file->getMimeType();
        $mime = str_replace('/', '-', $mime_original);
        $folderDATE = $request->dataDATE;
        $folder  = $folderDATE;
        $filePath = "public/upload/medialibrary/{$folder}/";
        $finalPath = storage_path("app/".$filePath);
        $fileSize = $file->getSize();
        // move the file name
        $file->move($finalPath, $fileName);
        $url_base = 'storage/upload/medialibrary/'."/{$folderDATE}/".$fileName;
        $control_var = Media::create([
            'name' => $fileName,
            'mime' => $mime_original,
            'path' => $filePath,
            'url' => $url_base,
            'size' =>$fileSize
          ]);
        return $this->chunkFileUpload($request);
    }
    protected function createFilename(UploadedFile $file)
    {
        $extension = $file->getClientOriginalExtension();
        
        $filename = str_replace(".".$extension, "", $file->getClientOriginalName()); // Filename without extension
     
        //delete timestamp from file name
        $temp_arr = explode('_', $filename);
        if ( isset($temp_arr[0]) ) unset($temp_arr[0]);
        $filename = implode('_', $temp_arr);
       
        //here you can manipulate with file name e.g. HASHED
        return $filename.".".$extension;
    }
    public function chunkFileUpload($request)
    {
        $inputData = Excel::import(new ImportUser(), $request->file)->toArray(new ImportUser(),$request->file);
                dd($inputData);
        $length = count($inputData[0]);
                for ($i = 1; $i < $length; $i++) {
                        $data = [];
                        $data['user_id'] = $inputData[0][$i][0];
                        $data['name'] = $inputData[0][$i][1];
                        $data['email'] = $inputData[0][$i][2];
                         $this->addDataToDb($data);
                }
                return true;
    }
    public function addDataToDb($data)
    {
        $limit = 5;
        foreach (array_chunk($data, $limit) as $chunkedArray) {
            dd($chunkedArray);
                UserDetail::insert([
                            'user_id' => $data['user_id'],
                            'name' => $data['name'],
                            'email' => $data['email'],
                        ]);
                        return true;
        }
    }
}
