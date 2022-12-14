<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course_details;

class CourseController extends Controller {
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function index() {
        $course = Course_details::select( 'id', 'course_name', 'course_years' )->orderBy( 'course_name', 'asc' )->get();
        return response()->json( [
            'data' => $course,
        ], 202 );
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function create() {

    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */

    public function store( Request $request ) {
        $course = Course_details::create( $request->all() );
        return response()->json( [
            'id' => $course[ 'id' ],
            'message'=>'Course details added successfully'
        ], 201 );
    }

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function show( $id ) {
        $data = Course_details::where( 'id', $id )->first();
        return response()->json( [
            'data' => $data,
        ], 201 );
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function edit( $id ) {
        //
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function update( Request $request, $id ) {
        //
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function destroy( $id ) {
        //
    }
}
