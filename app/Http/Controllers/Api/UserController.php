<?php
namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string',
            'email'    => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed',
        ]);
        $user = new User([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
        ]);
        $user->save();
        return response([
            'message' => 'Successfully created user!'
        ], 201);
    }
    public function login(Request $request)
    {
        $request->validate([
            'email'       => 'required|string|email',
            'password'    => 'required|string',
            /*  'remember_me' => 'boolean', */
        ]);
        $credentials = $request->only('email', 'password');

        $user = User::firstWhere('email',$credentials['email']);

        if(!$user){
            return response([
                'message' => 'User not found',
            ], 401);
        }

        if (!Auth::attempt($credentials)) {
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }
        $token->save();
        return response([
            'access_token' => $tokenResult->accessToken,
            'token_type'   => 'Bearer',
            'expires_at'   => Carbon::parse(
                $tokenResult->token->expires_at
            )
                ->toDateTimeString(),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response(['message' =>
        'Successfully logged out']);
    }

    public function user(Request $request)
    {
        return response($request->user());
    }


    public function all(Request $request){
        return response(User::all());
    }

    public function single($id){
        $user = User::find($id);
        if($user){
            return response($user);
        }
        return response([
            'message' => 'User not found'
        ],404);
    }


    public function update(Request $request, $id){
        $request->validate([
            'name' => 'required|string',
        ]);

        $item = User::find($id);
        if($item){
            $item->update([
                'name' => $request->name,
            ]);
            return response($item);
        }
        return response(['message' => "User not found"],404);
    }


    public function delete($id){
        $item = User::find($id);
        if($item){
            $item->delete();
            return response(['message' => "User deleted"]);
        }
        return response(['message' => "User not found"],404);
    }
    
}