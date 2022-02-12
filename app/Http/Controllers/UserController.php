<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\UserResourceCollection;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserResource;
use App\Http\Requests\UserRequest;
use App\Models\Role;
use App\Models\User;
use Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->user()){
            if (sizeof(User::all()) < 1) {
                return response()->json([
                    'error' => 'No user found yet'
                ], Response::HTTP_NOT_FOUND);
            }
            return UserResourceCollection::collection(User::latest()->paginate(10));
        }
        return response()->json([ 'error' => 'Unauthenticated' ], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $user = new User();

        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->password = Hash::make($request->password);
        $user->is_admin = $request->is_admin ? $request->is_admin : false;
        $user->save();

        return response()->json([
            'message' => 'User account and profile created successfully!',
            'data'  => new UserResource($user)
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if($request->user()){
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'error' => 'User not found!!'
                ], Response::HTTP_NOT_FOUND);
            }

            return new UserResource($user);
        }
        return response()->json([ 'error' => 'Unauthenticated' ], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(!$this->gotUser->hasPermission('edit_own_profile') || !$this->gotUser->hasPermission('edit_user')) {
            return response()->json([
                'error'  => 'Restricted Access'
            ], Response::HTTP_FORBIDDEN)->header('Content-Type', 'application/json');
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'error' => 'User not found!'
            ], Response::HTTP_NOT_FOUND);
        }

        $user->update($request->all());

        return response()->json([
            'data' => new UserResource($user)
        ], Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if(!$this->gotUser->hasPermission('delete_user')) {
            return response()->json([
                'error'  => 'Restricted Access'
            ], Response::HTTP_FORBIDDEN)->header('Content-Type', 'application/json');
        }

        $user = User::find($id);
        
        if (!$user) {
            return response()->json([
                'error' => 'User account not found!'
            ], Response::HTTP_NOT_FOUND);
        }

        $user->delete();
        
        return response()->json(
            ['message' => 'User account deleted successfully.'],
            Response::HTTP_PARTIAL_CONTENT
        );
    }
}
