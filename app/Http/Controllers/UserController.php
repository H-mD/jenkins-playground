<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends ResponseController
{
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'username' => 'required',
                'password' => 'required',
            ],
            [
                'username.required' => 'Username can\'t be empty',
                'password.required' => 'Password can\'t be empty'
            ]);
            
            if ($validator->fails()) {
                return $this->sendError($validator->errors(), 422);
            }
            
            $credentials = $request->only('username', 'password');
            
            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $userdata['token'] = $user->createToken('authToken')->plainTextToken;
                $userdata['role'] = $user->role;
                
                return $this->sendResponse('Login successful', $userdata);
            } else {
                return $this->sendError('Invalid Username or Password', 401);
            }
        }
        catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'username' => 'required|unique:users,username',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8',
                'confirm_password' => 'required|same:password',
            ], 
            [
                'username.required' => 'Username can\'t be empty',
                'username.unique' => 'Username is already registered',
                'email.required' => 'Email can\'t be empty',
                'email.email' => 'Email is not valid',
                'email.unique' => 'Email is already registered',
                'password.required' => 'Password can\'t be empty',
                'password.min' => 'Password must be at least 8 characters',
                'confirm_password.required' => 'Confirm password can\'t be empty',
                'confirm_password.same' => 'Confirm password must match password',
            ]);
            
            if ($validator->fails()){
                return $this->sendError($validator->errors()->first());
            }
            
            $userdata = User::create([
                'username' => $request->username,
                'email' => $request-> email,
                'password' => Hash::make($request->password),
                'role' => 'user',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            
            return $this->sendResponse('Register user successful', $userdata);
        }
        catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = Auth::user();
            $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
            return $this->sendResponse('Logout successful');
        }
        catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function getUser(Request $request)
    {
        try {
            $user = Auth::user();
            $userdata['id'] = $user->id;
            $userdata['username'] = $user->username;
            $userdata['email'] = $user->email;
            $userdata['role'] = $user->role;
            return $this->sendResponse('Get user successful', $userdata);
        }
        catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function updateUser(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'username' => 'required|unique:users,username',
                'email' => 'required|email|unique:users,email',
            ],
            [
                'username.required' => 'Username can\'t be empty',
                'username.unique' => 'Username is already used',
                'email.required' => 'Email can\'t be empty',
                'email.email' => 'Email is not valid',
                'email.unique' => 'Email is already used',
            ]);

            if ($validator->fails()){
                return $this->sendError($validator->errors()->first());
            }

            $user = Auth::user();
            $user->update([
                'username' => $request->username,
                'email' => $request-> email,
            ]);

            $userdata['id'] = $user->id;
            $userdata['username'] = $request->username; 
            $userdata['email'] = $request->email; 
            $userdata['created_at'] = $user->created_at; 
            $userdata['updated_at'] = $user->updated_at; 

            return $this->sendResponse('Update user successful', $userdata);
        }
        catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function deleteUser(Request $request)
    {
        try {
            $user = Auth::user();
            $id = $user->id;
            $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();     
            User::findOrFail($id)->delete();
            return $this->sendResponse('Delete user successful');
        }
        catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
