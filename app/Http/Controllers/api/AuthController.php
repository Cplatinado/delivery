<?php

namespace App\Http\Controllers\api;

use App\Actions\AuthControll\AuthControll;
use App\Models\User;
use App\Models\UserRecover;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\Login as LoginRequest;
use Spatie\Permission\Models\Role;
use App\Http\Requests\newPassword as  newPassword;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
//        return response()->json($request->all());

        $credentials = $request->only(['email', 'password']);
        $user = User::where('email', $request->email)->first();
        if($user->email_verified == 0){
            return response()->json(['error' => 'Not verified'], 403);
        }
        if($user->last_login != null){
            $authControl = new AuthControll();
            if ($authControl->ValidateLocation($user, $request->getClientIp()) == false || $user->disabled == 1 ) {
                $user->disabled = 1;
                $user->save();
                return response()->json(['error' => 'Unauthorized'], 405);
            }
        }




        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user->last_login = date("Y-m-d");
        $user->last_ip_login = $request->getClientIp();
        $user->save();


        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
//        $user = Auth::user(['name']);
        $user = User::where('id', Auth::user()->id)->first(['firstname','username', 'id', 'cover', 'office','disabled'],);

        $role = Role::where('name', $user->office)->first();

        $permissions = Permission::all();
        $permissionList = [];
        foreach ($permissions as $permission) {

            if ($role->hasPermissionTo($permission->name)) {
                array_push($permissionList, $permission->name);
            } else {
                $permission->can = false;
            }
        }

        $data['user'] = $user;
        $data['permissions'] = $permissionList;


        return response()->json($data);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }

    public function confirmLogin(Request $request)
    {
//        return  response()->json($this->convertStringToDate($request->birthday));

        $confirmLogin = UserRecover::where('token', $request->token)->first();
//        $data['expires'] = ;
//        $data['teste'] = $confirmLogin->expires < date('Y-m-d H:i');

        if($confirmLogin->expires < date('Y-m-d H:i')){
            return response()->json(['error'=> 'token expire'], 401);
        }
        if($confirmLogin->user == 1){
            return response()->json(['error'=> 'token used'], 401);

        }
        $confirmLogin->used = 1;
        $confirmLogin->save();

        $user = User::find($confirmLogin->user_id);
        $date = $this->convertStringToDate($request->birthday);
        $date = date('d/m/Y', strtotime($date));


//        $data['usercpf'] = $user->cpf;
//        $data['cpf'] = $request->cpf;
//        $data['dateuser'] = $user->birthday;
//        $data['date'] = $date;

        if($request->cpf == $user->cpf && $date == $user->birthday){
            $user->disabled = 0;
            $user->last_ip_login = $request->getClientIp();
            $user->save();
            return  response()->json('success');


        }else{
            return  response()->json(['error'=> 'data not alowed'], 401);
//            return  response()->json($data);
        }


        return  response()->json('success');
    }

    public function confirmEmail(Request $request)
    {
        $userRecover = UserRecover::where('token',$request->token)->first();
        $userRecover->used = 1;
        $userRecover->save();
        $user = User::find($userRecover->user_id);
        $user->email_verified = 1;
        $user->save();

        $data['success'] = true;
        $data['office'] = $user->office;

        return response()->json($data);

    }




    private function convertStringToDate(?string $param)
    {
        if (empty($param)) {
            return null;
        }

        list($day, $month, $year) = explode('/', $param);
        return (new \DateTime($year . '-' . $month . '-' . $day))->format('Y-m-d');
    }
}
