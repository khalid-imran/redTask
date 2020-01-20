<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\CardDesc;
use App\Models\CardFile;
use App\Models\Projects;
use App\Models\Task;
use App\Models\Team;
use App\Models\TeamMembers;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class FrontController extends Controller
{
    //============================
    // Public Home Page
    //============================

    public function Index()
    {
        $rv = array(
            'page' => 'Index',
        );
        return view('module.index')->with($rv);
    }

    //============================
    // Authentication
    //============================
    public function signUp()
    {
        $rv = array(
            'page' => 'sign up',
        );
        return view('module.signUp')->with($rv);
    }

    public function signUpAction(Request $request)
    {
      try{
          $input = $request->input();
          $validator = Validator::make($input, [
              'first_name' => 'required|min:3,max:10|string',
              'last_name' => 'required|min:3,max:10|string',
              'username' => 'required|unique:users|min:5,max:10|string',
              'email' => 'required|unique:users|max:50|email',
              'password' => 'required|confirmed|min:8,max:20'
          ]);
          if ($validator->fails()){
              return redirect()->back()->withErrors($validator->errors())->withInput($input);
          }

          $userModel = new User();
          $userModel->first_name = $input['first_name'];
          $userModel->last_name = $input['last_name'];
          $userModel->username = $input['username'];
          $userModel->email = $input['email'];
          $userModel->password = bcrypt($input['password']);
          $userModel->activation_code = md5(uniqid());
          $userModel->created_at = Carbon::now();
          $userModel->save();
          if ($userModel->id > 0){

          }
          return redirect()->back()->withErrors(['success' => 'Your registration has been successfully done and activation mail has been sent to your email. Please check and activate your account.']);


      }catch (\Exception $e){
          return redirect()->back()->withErrors($e->getMessage());
      }
    }
    public function login()
    {
        $rv = array(
            'page' => 'login',
        );
        return view('module.login')->with($rv);
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('index');
    }
    public function loginAction(Request $request)
    {
        try{

            $input = $request->input();
            $validator = Validator::make($input, [
                'email' => 'required|exists:users,email',
                'password' => 'required|min:8,max:20'
            ]);
            if($validator->fails()){
                return redirect()->back()->withErrors($validator->errors())->withInput($input);
            }

            unset($input['_token']);
            if(Auth::attempt($input)){

                return redirect()->route('index.dashboard');

            } else {
                return redirect()->back()->withErrors(['email' => ['Invalid Credential']])->withInput($input);
            }


        } catch (\Exception $e){
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    //============================
    // Dashboard
    //============================

    public function dashboard()
    {
        $rv = array(
            'page' => 'dashboard',

        );
        return view('module.dashboard')->with($rv);
    }

    //============================
    // Projects Card
    //============================
    /*Project Card create*/
    public function projectCards(Request $request)
    {
        try{
            $input = $request->input();
            $validator = Validator::make($input, [
                'project_id' => 'required',
                'title' => 'required',
            ]);
            if ($validator->fails()){
                return redirect()->back()->withErrors($validator->errors())->withInput($input);
            }

            $cardModel = new Card();
            $cardModel->project_id = $input['project_id'];
            $cardModel->title = $input['title'];
            $cardModel->created_at = Carbon::now();
            $cardModel->save();
            /*  $cardId = $cardModel->id;
              if($cardId > 0){
                  $mediaIds = isset($input['media_id']) ? $input['media_id'] : '';
                  if($mediaIds != ''){
                      $mediaIds = explode(',', $mediaIds);
                      $mediaArr = [];
                      foreach ($mediaIds as $mediaId){
                          if($mediaId != '' && $mediaId > 0){
                              $mediaArr[] = array(
                                  'card_id' => $cardId,
                                  'media_id' => $mediaId,
                                  'created_at' => Carbon::now()
                              );
                          }
                      }
                      if(count($mediaArr) > 0){
                          CardFile::insert($mediaArr);
                      }
                  }
              }*/

            return redirect()->back();

        }catch (\Exception $e){

            return redirect()->back()->withErrors($e->getMessage());
        }
    }
    /*Project Card edit*/
    public function projectCardsEdit(Request $request)
    {
        try{
            $input = $request->input();
            $validator = Validator::make($input, [
                'card_id' => 'required',
                'edit_title' => 'required',
            ]);
            if ($validator->fails()){
                $input['type'] = 'edit';
                return redirect()->back()->withErrors($validator->errors())->withInput($input);
            }

            $cardModel = new Card();
            $cardModel->where('id', $input['card_id'])->update([
                'title' => $input['edit_title']
            ]);

            return redirect()->back();

        }catch (\Exception $e){
            dd($e->getMessage());
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
    /*Project Card delete*/
    public function projectCardsDelete(Request $request)
    {
        try{
            $input = $request->input();
            $validator = Validator::make($input, [
                'delete_id' => 'required',
            ]);
            if ($validator->fails()){
                return redirect()->back()->withErrors($validator->errors())->withInput($input);
            }

            $projectModel = new Card();
            $projectModel->where('id', $input['delete_id'])->delete();

            return redirect()->back();

        }catch (\Exception $e){
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
    /*Go to single card*/
    public function projectSingleCard($id)
    {
        $card = Card::with('desc','task', 'image')->where('id',$id)->get()->first();
        if ($card == null){
            abort('404');
        }
        $card = $card->toArray();
        $rv = array(
            'page' => 'projectCardSingle',
            'card' => $card
        );
        return view('module.projectCard')->with($rv);
    }

    //============================
    // Card Desc
    //============================
    /*Card Desc create*/
    public function cardDescAdd(Request $request)
    {
        try{
            $input = $request->input();
            $validator = Validator::make($input, [
                'card_id' => 'required',
                'desc' => 'required',
            ]);
            if ($validator->fails()){
                return redirect()->back()->withErrors($validator->errors())->withInput($input);
            }

            $cardModel = new CardDesc();
            $cardModel->card_id = $input['card_id'];
            $cardModel->description = $input['desc'];
            $cardModel->save();

            return redirect()->back()->with('success', 'Added successful ');

        }catch (\Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    /*Card Desc update*/
    public function cardDescEdit(Request $request)
    {
        try{
            $input = $request->input();
            $validator = Validator::make($input, [
                'desc' => 'required',
                'id' => 'required',
            ]);
            if ($validator->fails()){
                return redirect()->back()->withErrors($validator->errors())->withInput($input);
            }

            $cardModel = CardDesc::find($input['id']);
            $cardModel->description = $input['desc'];
            $cardModel->save();

//            CardDesc::where('id', $input['card_id'])->update([
//                'description' => $input['desc']
//            ]);

            return redirect()->back()->with('success', 'Edited successful ');

        }catch (\Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    /*Card Desc delete*/
    public function cardDescDelete(Request $request)
    {
        try{
            $input = $request->input();
            $validator = Validator::make($input, [
                'id' => 'required',
            ]);
            if ($validator->fails()){
                return redirect()->back()->withErrors($validator->errors())->withInput($input);
            }

            CardDesc::destroy($input['id']);

            return redirect()->back()->with('success', 'Deleted successful');
        }catch (\Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    //============================
    // Card TodoList
    //============================
    /*tasks create action*/
    public function cardTaskAdd(Request $request){
        try {
            $input = $request->input();
            $validator = Validator::make($input, [
                'name' => 'required|string',
                'card_id' => 'required',
            ]);

            if($validator->fails()){
                return redirect()->back()->withErrors($validator->errors())->withInput($input);
            }

            $taskModel = new Task();
            $taskModel->name =$input['name'];
            $taskModel->card_id =$input['card_id'];
            $taskModel->status =$input['status'];
            $taskModel->created_at = Carbon::now();
            $taskModel->save();

            return redirect()->back()->with('success', 'Task add successful');

        }catch (\Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    /*tasks status update*/
    public function taskStatusUpdate(Request $request){
        try {
            $input = $request->input();
            $validator = Validator::make($input, [
                'id' => 'required|string',
                'status' => 'required',
            ]);

            if($validator->fails()){
                return redirect()->back()->withErrors($validator->errors())->withInput($input);
            }
            $taskModel = Task::find($input['id']);
            $taskModel->status = $input['status'];
            $taskModel->save();
            return response()->json([
                'status' => 200,
                'msg' => 'success',
            ]);

        }catch (\Exception $e){
            return response()->json([
                'status' => 500,
                'msg' => 'error',
                'data' =>  $e->getMessage()
            ]);
        }
    }
    /*tasks Update*/
    public function taskStatusEdit(Request $request){
        try {
            $input = $request->input();
            $validator = Validator::make($input, [
                'id' => 'required',
                'name' => 'required',
            ]);

            if($validator->fails()){
                return redirect()->back()->withErrors($validator->errors())->withInput($input);
            }

            $taskModel = Task::find($input['id']);
            $taskModel->name = $input['name'];
            $taskModel->save();
            return response()->json([
                'status' => 200,
                'msg' => 'success',
            ]);

        }catch (\Exception $e){
            return response()->json([
                'status' => 500,
                'msg' => 'error',
                'data' =>  $e->getMessage()
            ]);
        }
    }
    /*tasks delete*/
    public function cardTaskDelete(Request $request){
        try {
            $input = $request->input();
            $validator = Validator::make($input, [
                'id' => 'required',
            ]);

            if($validator->fails()){
                return redirect()->back()->withErrors($validator->errors())->withInput($input);
            }
            Task::destroy($input['id']);

            return redirect()->back()->with('success', 'Deleted successful');
        }catch (\Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    //============================
    // Projects
    //============================
    /*Go to Project*/
    public function projects()
    {
        $projects = Projects::where('user_id', Auth::user()->id)->get()->toArray();
        $rv = array(
            'page' => 'projects',
            'projects' => $projects,
        );
        return view('module.projects')->with($rv);
    }

    /*Go to Project single*/
    public function projectSingle($id)
    {
        $project = Projects::where('id',$id)->get()->first();
        if ($project == null){
            abort('404');
        }
        $project = $project->toArray();
        $card = Card::where('project_id', $id)->get()->toArray();

        $rv = array(
            'page' => 'projectsSingle',
            'project' => $project,
            'card' => $card,

        );

        return view('module.projectSingle')->with($rv);
    }


    public function projectsCreate(Request $request)
    {
        try{
            $input = $request->input();
            $validator = Validator::make($input, [
                'project_name' => 'required|unique:projects,title',
            ]);
            if ($validator->fails()){
                $input['type'] = 'add';
                return redirect()->back()->withErrors($validator->errors())->withInput($input);
            }

            $projectModel = new Projects();
            $projectModel->title = $input['project_name'];
            $projectModel->user_id = auth()->user()->id;
            $projectModel->save();

            return redirect()->back();

        }catch (\Exception $e){
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
    public function projectsEdit(Request $request)
    {
        try{
            $input = $request->input();
            $validator = Validator::make($input, [
                'edit_name' => 'required|unique:projects,title',
            ]);
            if ($validator->fails()){
                $input['type'] = 'edit';
                return redirect()->back()->withErrors($validator->errors())->withInput($input);
            }

            $projectModel = new Projects();
            $projectModel->where('id', $input['id'])->update([
                'title' => $input['edit_name']
            ]);

            return redirect()->back();

        }catch (\Exception $e){
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
    public function projectsDelete(Request $request)
    {
        try{
            $input = $request->input();
            $validator = Validator::make($input, [
                'delete_id' => 'required',
            ]);
            if ($validator->fails()){
                $input['type'] = 'remove';
                return redirect()->back()->withErrors($validator->errors())->withInput($input);
            }

            $projectModel = new Projects();
            $projectModel->where('id', $input['delete_id'])->delete();

            return redirect()->back();

        }catch (\Exception $e){
            return redirect()->back()->withErrors($e->getMessage());
        }
    }


    //============================
    // profile
    //============================
    public function profile()
    {
        $user = User::where('id', Auth::user()->id)->get()->toArray();
        $rv = array(
            'page' => 'profile',
            'profile' => $user,
        );
        return view('module.profile')->with($rv);
    }

    public function profileUpdate(Request $request)
    {
        try{
            $input = $request->input();
            $validator = Validator::make($input, [
                'first_name' => 'required|min:3,max:10|string',
                'last_name' => 'required|min:3,max:10|string',
                'username' => 'required|unique:users,username,'. Auth::user()->id.'|min:5,max:10|string',
            ]);
            if ($validator->fails()){
                return redirect()->back()->withErrors($validator->errors())->withInput($input);
            }

            $userModel = new User();
            $userInfo = $userModel->where('id',  Auth::user()->id)->get()->first();
            isset($input['first_name']) ? $userInfo->first_name = $input['first_name'] : '';
            isset($input['last_name']) ? $userInfo->last_name = $input['last_name'] : '';
            isset($input['username']) ? $userInfo->username = $input['username'] : '';
            isset($input['avatar']) ? $userInfo->avatar = $input['avatar'] : '';
            $userInfo->save();

            return redirect()->back();

        }catch (\Exception $e){
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
    public function passUpdate(Request $request)
    {

        try{
            $input = $request->input();
            $validator = Validator::make($request->input(), [
                'old_password' => [
                    'required', function ($attribute, $value, $fail) {
                        if (!Hash::check($value, Auth::user()->password)) {
                            $fail('Old Password didn\'t match');
                        }
                    },
                ],
                'password' => 'confirmed|min:8,max:20|'

            ]);

            if($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }

            $userModel = new User();
            $userModel->where('id',  Auth::user()->id)->update([
                'password' => bcrypt($input['password']),
            ]);

            return redirect()->back();

        }catch (\Exception $e){
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
    public function emailUpdate(Request $request)
    {

        try{
            $input = $request->input();
            $validator = Validator::make($request->input(), [

                'email' => 'required|unique:users,email,'. Auth::user()->id.'|max:50',

            ]);

            if($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }

            $userModel = new User();
            $userModel->where('id',  Auth::user()->id)->update([
                'email' => ($input['email']),
            ]);

            return redirect()->back();

        }catch (\Exception $e){
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

}
