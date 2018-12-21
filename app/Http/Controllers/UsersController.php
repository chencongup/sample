<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;


use App\Models\User;
use Auth;
use Mail;
class UsersController extends Controller
{
    /**
     * 权限控制
     */
    public function __construct()
    {
        $this->middleware('auth',[
            'only'=>['edit','update','destroy']
        ]);
        $this->middleware('guest', [
            'only' => ['create']
        ]);

    }

    /**
     * 用户列表
     */
    public function index()
    {
        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * 登录界面
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * 查看用户信息
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        $statuses = $user->statuses()->orderby('created_at','desc')->paginate(10);
        return view('users.show', compact('user','statuses'));
    }

    /**
     * 新建用户
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name'=>'required|max:50',
            'email'=>'required|email|unique:users|max:255',
            'password'=>'required'
        ]);
        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>bcrypt($request->password) //bcrypt对数据加密
        ]);
        $this->sendEmailConfirmationTo($user);
        session()->flash('success', '验证邮件已发送到你的注册邮箱上，请注意查收。');
        return redirect('/');

//        Auth::login($user);
//        session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');
//        return redirect()->route('users.show',[$user]);
    }

    /**
     * @param $id
     * @return 修改用户信息页面
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('update',$user);
        return view('users.edit',compact('user'));
    }

    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id,Request $request)
    {
        $this->validate($request,[
            'name'=>'required|max:255',
            'password'=>'required'
        ]);
        //获取表
        $user = User::findOrFail($id);
        $this->authorize('update',$user);
        $user->update([
            'name'=>$request->name,
            'password'=>bcrypt($request->password),
        ]);
        session()->flash('success', '个人资料更新成功！');
        return redirect()->route('users.show',$id);
    }

    /**
     *  删除用户
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        //授权验证，验证规则在userpolicy里面
        $this->authorize('destroy', $user);
        $user->delete();
        session()->flash('success', '成功删除用户！');
        return back();
    }

    /**
     * 发送邮件
     */
    protected function sendEmailConfirmationTo($user)
    {
        $view = 'emails.confirm';
        $data = compact('user');
        //$from = 'aufree@yousails.com';
        //$name = 'Aufree';
        $to = $user->email;
        $subject = "感谢注册 Sample 应用！请确认你的邮箱。";

        Mail::send($view, $data, function ($message) use ($from, $name, $to, $subject) {
            $message->from($from, $name)->to($to)->subject($subject);
        });
    }

    /**
     * @param $token
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirmEmail($token)
    {
        $user = User::where('activation_token', $token)->firstOrFail();

        $user->activated = true;
        $user->activation_token = null;
        $user->save();


        Auth::login($user);
        session()->flash('success', '恭喜你，激活成功！');
        return redirect()->route('users.show', [$user]);
    }


}
