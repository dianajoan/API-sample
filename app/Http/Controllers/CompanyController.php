<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\CompanyResourceCollection;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\CompanyResource;
use App\Http\Requests\CompanyRequest;
use App\Models\Company;
use Auth;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->user()){
            if (sizeof(Company::all(['id'])) < 1) {
                return response()->json([
                    'error' => 'No company found yet'
                ], Response::HTTP_NOT_FOUND);
            }
            return CompanyResourceCollection::collection(Company::latest()->paginate(10));
        }
        return response()->json([ 'error' => 'Unauthenticated' ], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CompanyRequest $request)
    {
        $company = new Company();

        if( $request->hasFile('logo') ) {
            $user_image = $request->file('logo');
            $filename = time() . '.' . $user_image->getClientOriginalName();
            $user_image->move( 'storage/public/', $filename );
            $company->logo = $filename; // $request->file('logo')->getClientOriginalName();
        }

        $company->name      = $request->name;
        $company->email     = $request->email;
        $company->website   = $request->website;
        $company->save();

        return response()->json([
            'message' => 'Company account saved successfully!',
            'data'  => new CompanyResource($company)
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
            $company = Company::find($id);

            if (!$company) {
                return response()->json([
                    'error' => 'Company not found!!'
                ], Response::HTTP_NOT_FOUND);
            }

            return new CompanyResource($company);
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
        $company = Company::find($id);

        if (!$company) {
            return response()->json([
                'error' => 'Company not found!'
            ], Response::HTTP_NOT_FOUND);
        }

        $company->update($request->all());

        return response()->json([
            'data' => new CompanyResource($company)
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
        $company = Company::find($id);
        
        if (!$company) {
            return response()->json([
                'error' => 'Company account not found!'
            ], Response::HTTP_NOT_FOUND);
        }

        $company->delete();
        
        return response()->json(
            ['message' => 'Company account deleted successfully.'],
            Response::HTTP_PARTIAL_CONTENT
        );
    }
}