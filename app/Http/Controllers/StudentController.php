<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\DataBase\Query\Builder;

class StudentController extends Controller {
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function index( Request $request ) {

        Builder::macro('whereLike', function($columns, $search) {
            $this->where(function($query) use ($columns, $search) {
              foreach(\Arr::wrap($columns) as $column) {
                $query->orWhere($column,'LIKE', "{$search}%");
              }
            });
           
            return $this;
          });

        

        $search_key = $request->name;
        $course = $request->course;

        $list = Student::whereLike(['first_name','last_name','email'], $search_key)->orderBy('first_name', 'asc')->get();
        // $result = array_chunk($list,$page_size)[$current_page-1];

        return response()->json( [
            'data'=>$list,
            'image_url'=>url('/images/'),
        ], 200 );

    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */

    public function store( Request $request ) {
        $check_student = Student::whereCourseAndYearAndRoll_number( $request[ 'course' ], $request[ 'year' ], $request[ 'roll_number' ] )->first();
        $data = $request->all();
        if ( isset( $check_student ) ) {
            return response()->json( [
                'message' => 'Roll number already exist in same year',
            ], 400 );
        } else {

            unset( $data[ 'image' ] );

            //image upload
            $image_file = $request->file( 'image' );
            if ( $image_file ) {
                $name = $request[ 'first_name' ].$request[ 'last_name' ].'.png';
                $path = $request->file( 'image' )->store( 'public/images' );
                $image_file-> move( public_path( 'images' ), $name );
                $data[ 'image' ] = $name;
            } else
            $data[ 'image' ] = '';

            $student = new Student( $data );

            $student->save();

            return response()->json( [
                'id' => $student->id,
                'message'=>'Student details added'
            ], 201 );

        }
    }

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function show( $id ) {

      $student =  Student::where('id', $id )->first();
      if(isset($student)){
         $student['image_url']=url('/images/');
         return response()->json( [
            'data' => $student,
            'message'=>'Student Detaiils found'
        ], 202 );
        }
        else
       {
        return response()->json( [
            'data' => $student,
            'message'=>"No Data found"
        ], 204 );
       }

        


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
        $image_file = $request->file( 'image' );
        $data = $request->all();
        $check_student = Student::whereCourseAndYearAndRoll_number( $request[ 'course' ], $request[ 'year' ], $request[ 'roll_number' ] )->first();
        $data = $request->all();
        if ( isset( $check_student ) ) {
            if($check_student['id'] != $id)
             return response()->json( [
            'message' => 'Roll number already exist in same year',
            ], 400 );
        } 

            if($image_file) {
                $name = $request[ 'first_name' ].$request[ 'last_name' ].'.png';
                $path = $request->file( 'image' )->store( 'public/images' );
                $image_file-> move( public_path( 'images' ), $name );
                $data[ 'image' ] = $name;
            }elseif($data['image']==='previous')
                $data['image'] =Student::select('image')->where('id', $id )->first()['image'];
            else
               $data['image'] =' ';

              unset( $data[ '_method' ] );
              $update = Student::where('id',$id)->update($data);
            
            return response()->json( [
              'data' => $update,
              'message'=>$image_file?"imagefound":"notfound"
            ], 200 );
        

        
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function destroy( $id ) {
        $data = Student::where('id',$id)->delete();

        //
        return response()->json( [
            'data' => $data,
          ], 200 );
    }
}
