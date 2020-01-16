<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\CardFile;
use App\Models\Projects;
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
    // Projects
    //============================
    public function projects()
    {
        $projects = Projects::where('user_id', Auth::user()->id)->get()->toArray();
        $rv = array(
            'page' => 'projects',
            'projects' => $projects,
        );
        return view('module.projects')->with($rv);
    }
    public function projectSingle($id)
    {
        $project = Projects::where('id',$id)->get()->first();
        if ($project == null){
            abort('404');
        }
        $project = $project->toArray();
        $card = Card::where('project_id', $id)->get()->toArray();
//        $card = Card::with('images')->where('project_id', $id)->get()->toArray();


        $rv = array(
            'page' => 'projectsSingle',
            'project' => $project,
            'card' => $card,

        );
        return view('module.projectSingle')->with($rv);
    }
    public function projectSingleCard($id)
    {
        $card = Card::where('id',$id)->get()->first();
        if ($card == null){
            abort('404');
        }
        $card = $card->toArray();
        $cardDetail = CardFile::where('card_id', $id)->get()->toArray();

        $rv = array(
            'page' => 'projectsSingle',
            'card' => $card,
            'cardDetail' => $cardDetail,

        );
        return view('module.projectCard')->with($rv);
    }

    public function projectCards(Request $request)
    {
        try{
            $input = $request->input();
            $validator = Validator::make($input, [
                'project_id' => 'required',
                'title' => 'required',
            ]);
            if ($validator->fails()){
                $input['type'] = 'add';
                return redirect()->back()->withErrors($validator->errors())->withInput($input);
            }

            $cardModel = new Card();
            $cardModel->project_id = $input['project_id'];
            $cardModel->title = $input['title'];
            $cardModel->description = $input['description'];
            $cardModel->created_at = Carbon::now();
            $cardModel->save();
            $cardId = $cardModel->id;
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
            }



            return redirect()->back();

        }catch (\Exception $e){
            dd($e->getMessage());
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
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
                'title' => $input['edit_title'],
                'description' => $input['edit_description'],
            ]);

            return redirect()->back();

        }catch (\Exception $e){
            dd($e->getMessage());
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
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
    // Team
    //============================
    public function team()
    {
        $team = Team::where('user_id', Auth::user()->id)->get()->toArray();
        $rv = array(
            'page' => 'team',
            'team' => $team,
        );
        return view('module.team')->with($rv);
    }
    public function teamDetail($id)
    {
        $team = Team::where('id',$id)->get()->first();
        if($team == null){
            abort('404');
        }
        $team = $team->toArray();
        $users = User::where('id', '!=', auth()->id())->get()->toArray();
        $teamMembers = TeamMembers::where('team_id', $id)->get()->toArray();
        $teamRel = [];
        foreach ($teamMembers as $member){
            $memberId = $member['user_id'];
            array_push($teamRel, $memberId);
        }
        $members = User::whereIn('id', $teamRel)->get();
        $rv = array(
            'page' => 'team detail',
            'users' => $users,
            'team_id' => $id,
            'members' => $members,
            'team' => $team,
        );
        return view('module.teamDetail')->with($rv);
    }

    public function teamCreate(Request $request)
    {
        try{
            $input = $request->input();
            $validator = Validator::make($input, [
                'title' => 'required|unique:team',
            ]);
            if ($validator->fails()){
                $input['type'] = 'create';
                return redirect()->back()->withErrors($validator->errors())->withInput($input);
            }

            $projectModel = new Team();
            $projectModel->title = $input['title'];
            $projectModel->user_id = auth()->user()->id;
            $projectModel->save();

            return redirect()->back();

        }catch (\Exception $e){
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
    public function teamEdit(Request $request)
    {
        try{
            $input = $request->input();
            $validator = Validator::make($input, [
                'edit_title' => 'required|unique:team,title',
            ]);
            if ($validator->fails()){
                $input['type'] = 'edit';
                return redirect()->back()->withErrors($validator->errors())->withInput($input);
            }

            $projectModel = new Team();
            $projectModel->where('id', $input['id'])->update([
                'title' => $input['edit_title']
            ]);

            return redirect()->back();

        }catch (\Exception $e){
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
    public function teamDelete(Request $request)
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

            $projectModel = new Team();
            $projectModel->where('id', $input['delete_id'])->delete();

            return redirect()->back();

        }catch (\Exception $e){
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function teamMemberAdd(Request $request)
    {
        try{
            $input = $request->input();
            $validator = Validator::make($input, [
                'team_id' => 'required',
                'users' => 'required|array',
            ]);
            if ($validator->fails()){
                return redirect()->back()->withErrors($validator->errors())->withInput($input);
            }

            foreach ($input['users'] as $id){
                $userRel = array(
                    'team_id' => $input['team_id'],
                    'user_id' => $id,
                );
                $check = TeamMembers::where($userRel)->get()->first();
                if($check == null){
                    TeamMembers::insert([$userRel]);
                }
            }

            return redirect()->back();

        }catch (\Exception $e){
            dd($e->getMessage());
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
    public function teamMemberDelete(Request $request)
    {
        try{
            $input = $request->input();
            $validator = Validator::make($input, [
                'delete_id' => 'required',
            ]);
            if ($validator->fails()){
                return redirect()->back()->withErrors($validator->errors())->withInput($input);
            }

            $memberModel = new TeamMembers();
            $memberModel->where('user_id', $input['delete_id'])->delete();

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
