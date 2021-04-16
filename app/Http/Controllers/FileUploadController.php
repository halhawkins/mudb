<?php
   
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FileUploadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fileUpload()
    {
        return view('fileUpload');
    }
  
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fileUploadPost(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:jpg,png,gif',
        ]);
  
        $fileName = uniqid('img_').'.'.$request->file->extension();  
   
        $request->file->move(public_path('uploads'), $fileName);
        
        $a = array("filename"=>$fileName,"user"=>Auth::user()->email);
        User::where("email","=",$a['user'])->update(["avatar"=>url('/')."/uploads/".$fileName]);
        $request->session()->regenerate();
   
        return back()
            ->with('success','You have successfully upload file.')
            ->with($fileName);
   
    }
}