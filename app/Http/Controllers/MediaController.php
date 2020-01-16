<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Projects;
use App\Models\Team;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MediaController extends Controller
{
    public function media(Request $request) {
        $this->validate($request, [
            'input_img' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('input_img')) {
            $image = $request->file('input_img');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $name);

            $fileExtension = $image->getClientOriginalExtension();
            if ($fileExtension == 'docx' || $fileExtension == 'pdf' || $fileExtension == 'doc'){
                $mediaModel = new Media();
                $mediaModel->file_name = $name;
                $mediaModel->media_type = 2;
                $mediaModel->save();
            }
            elseif ($fileExtension == 'jpg' || $fileExtension == 'jpeg' || $fileExtension == 'png'){
                $mediaModel = new Media();
                $mediaModel->file_name = $name;
                $mediaModel->media_type = 1;
                $mediaModel->save();
            }
            else{
                return response()->json(['status'=>5000, 'msg'=> 'file format not supported'], 200);
            }

            return response()->json(['status'=>2000, 'data'=>$mediaModel], 200);
        }
    }
}
