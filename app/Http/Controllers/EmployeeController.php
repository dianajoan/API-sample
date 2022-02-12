<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
    public function store(Request $request)
    {
        $employee = new Employee();

        $employee->first_name     = $request->first_name;
        $employee->last_name     = $request->last_name;
        $employee->company_id     = $request->company_id;
        $employee->email    = $request->email;
        $employee->phone    = $request->phone;
        $employee->save();

        return response()->json([
            'message' => 'Employee account and profile created successfully!',
            'data'  => new EmployeeResource($employee)
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
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
    public function update(Request $request, Employee $employee)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json([
                'error' => 'Employee not found!'
            ], Response::HTTP_NOT_FOUND);
        }

        $employee->update($request->all());

        return response()->json([
            'data' => new EmployeeResource($employee)
        ], Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
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
}
