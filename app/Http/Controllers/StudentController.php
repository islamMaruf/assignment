<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRequest;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $name = request('name');
        $department = request('department');
        $gpa = request('gpa');

        $students =  Student::with('department', 'result');

        if ($name) {
            $students = $students->where('name', 'like', "$name%");
        }
        if ($department) {
            $students  = $students->whereHas('department', function ($query) use ($department) {
                $query->where('name',  $department);
            });
        }
        if ($gpa) {
            $students  = $students->whereHas('result', function ($query) use ($gpa) {
                $query->where('gpa', $gpa);
            });
        }

        $students = $students->latest()->paginate(10);

        return $this->sendResponse($students, 'Student List');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StudentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StudentRequest $request)
    {
        $student = Student::create([
            'name' => $request->name,
            'department_id' => $request->department,
            'batch_name' => $request->batch_name
        ]);
        return $this->sendResponse($student, 'Student Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  StudentRequest $request
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StudentRequest $request, $id)
    {
        $student = Student::findOrFail($id);
        $student->update([
            'name' => $request->name,
            'department_id' => $request->department,
            'batch_name' => $request->batch_name
        ]);

        return $this->sendResponse($student, 'Student Information has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        $student->delete();
        return $this->sendResponse([$student], 'Student has been Deleted');
    }
}
