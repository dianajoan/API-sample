<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\EmployeeResourceCollection;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\EmployeeResource;
use App\Http\Requests\EmployeeRequest;
use App\Models\Employee;
use Auth;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->user()){
            if (sizeof(Employee::all()) < 1) {
                return response()->json([
                    'error' => 'No employee found yet'
                ], Response::HTTP_NOT_FOUND);
            }
            return EmployeeResourceCollection::collection(Employee::latest()->paginate(10));
        }
        return response()->json([ 'error' => 'Unauthenticated' ], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmployeeRequest $request)
    {
        if($request->user()) {
            $employee = new Employee();

            $employee->first_name   = $request->first_name;
            $employee->last_name    = $request->last_name;
            $employee->company_id   = $request->company_id;
            $employee->email    = $request->email;
            $employee->phone    = $request->phone;

            if($request->user()) {
                $employee->user_id = $request->user()->id;
            }

            $employee->save();

            return response()->json([
                'message' => 'Employee account and profile created successfully!',
                'data'  => new EmployeeResource($employee)
            ], Response::HTTP_CREATED);
        }
        return response()->json([
            'error'     => 'unauthenticated. Please login first'
        ], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if($request->user()){
            $employee = Employee::find($id);

            if (!$employee) {
                return response()->json([
                    'error' => 'Employee not found!!'
                ], Response::HTTP_NOT_FOUND);
            }

            return new EmployeeResource($employee);
        }
        return response()->json([ 'error' => 'Unauthenticated' ], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if($request->user()) {
            $employee = Employee::find($id);

            if (!$employee) {
                return response()->json([
                    'error' => 'Employee not found!'
                ], Response::HTTP_NOT_FOUND);
            }


            $employee->first_name = $request->first_name;
            $employee->last_name = $request->last_name;
            $employee->company_id = $request->company_id;
            $employee->email = $request->email;
            $employee->phone = $request->phone;
            $employee->phone = $request->user_id;
            $employee->save();

            return response()->json([
                'data' => new EmployeeResource($employee)
            ], Response::HTTP_OK);
        }
        return response()->json([
            'error'     => 'unauthenticated. Please login first'
        ], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if($request->user()){
            $employee = Employee::find($id);
            
            if (!$employee) {
                return response()->json([
                    'error' => 'Employee account not found!'
                ], Response::HTTP_NOT_FOUND);
            }

            $employee->delete();
            
            return response()->json(
                ['message' => 'Employee account deleted successfully.'],
                Response::HTTP_PARTIAL_CONTENT
            );
        }
        return response()->json([
            'error'     => 'unauthenticated. Please login first'
        ], Response::HTTP_UNAUTHORIZED);
    }
}
