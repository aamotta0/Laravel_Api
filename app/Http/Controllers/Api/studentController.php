<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class studentController extends Controller
{
    // get all students
    public function index() {
        $students = Student::all();

        $data = [
            'students' => $students,
            'status' => 200,
        ];
        return response()->json($data, 200);
    }

    // create a new student
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:student',
            'phone' => 'required|digits:10',
            'language' => 'required|in:Spanish,English, French',
        ]);

        // validate the request data 
        if($validator->fails()) {
            $data = [
                'message' => 'Error the data is incorrect',
                'errors' => $validator->errors(),
                'status' => 400,
            ];
            return response()->json($data, 400);
        }

        // create a new student
        $student = Student::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'language' => $request->language,
        ]);

        // check if the student was created
        if(!$student) {
            $data = [
                'message' => 'Error creating the student',
                'status' => 500,
            ];
            return response()->json($data, 500);
        }

        // return the response
        $data = [
            'student' => $student,
            'status' => 201,
        ];
        return response()->json($data, 201);
    }

    // get a single student
    public function show($id) {
        $student = Student::find($id);
        // check if the student exists
        if(!$student) {
            $data = [
                'message' => 'Student not found',
                'status' => 404,
            ];
            return response()->json($data, 404);
        }
        // return the response
        $data = [
            'student' => $student,
            'status' => 200,
        ];
        return response()->json($data, 200);
    }

    // delete a student
    public function destroy($id) {
        $student = Student::find($id);

        // check if the student exists
        if(!$student) {
            $data = [
                'message' => 'Student not found',
                'status' => 404,
            ];
            return response()->json($data, 404);
        }

        // delete the student
        $student->delete();
        
        $data = [
            'message' => 'Student deleted successfully',
            'status' => 200,
        ];

        return response()->json($data, 200);
    }
    
    // update a student
    public function update(Request $request, $id) {
        $student = Student::find($id);
        
        // check if the student exists
        if(!$student) {
            $data = [
                'message' => 'Student not found',
                'status' => 404,
            ];
            return response()->json($data, 404);
        }

        // validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email',
            'phone' => 'required|digits:10',
            'language' => 'required|in:Spanish,English, French',
        ]);

        // validate the request data
        if($validator->fails()) {
            $data = [
                'message' => 'Error the data is incorrect',
                'errors' => $validator->errors(),
                'status' => 400,
            ];
            return response()->json($data, 400);
        }

        // update the student
        $student->name = $request->name;
        $student->email = $request->email;
        $student->phone = $request->phone;
        $student->language = $request->language;

        // save the student
        $student->save();

        $data = [
            'message' => 'Student updated successfully',
            'student' => $student,
            'status' => 200,
        ];
        return response()->json($data, 200);
    }

    // updatePartial a student
    public function updatePartial(Request $request, $id) {
        $student = Student::find($id);

        // check if the student exists
        if(!$student) {
            $data = [
                'message' => 'Student not found',
                'status' => 404,
            ];
            return response()->json($data, 404);
        }

        // validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'max:255',
            'email' => 'email',
            'phone' => 'digits:10',
            'language' => 'in:Spanish, English, French',
        ]);

       // confirm if the request data is correct
       if($validator->fails()) {
            $data = [
                'message' => 'Error the data is incorrect',
                'errors' => $validator->errors(),
                'status' => 400,
            ];
            return response()->json($data, 400);
        }

        // update the student
        if($request->name) {
            $student->name = $request->name;
        }
        if($request->email) {
            $student->email = $request->email;
        }
        if($request->phone) {
            $student->phone = $request->phone;
        }
        if($request->language) {
            $student->language = $request->language;
        }

        // save the student
        $student->save();

        $data = [
            'message' => 'Student updated successfully',
            'student' => $student,
            'status' => 200,
        ];
        
        return response()->json($data, 200);
    }
}

