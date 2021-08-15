<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResultRequest;
use App\Models\Result;
use Illuminate\Http\Request;

class ResultController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $results =  Result::with('student', 'student.department');
        $gpa = request('gpa');
        $studentName = request('name');
        $department = request('department');

        if (request('gpa')) {
            $results = $results->where('gpa', 'like', "$gpa%");
        }
        $results = $results->whereHas('student', function ($query) use ($studentName, $department) {
            if (request('name')) {
                $query->where('name', 'like', "$studentName%")->whereHas('department', function ($q) use ($department) {
                    if (request('department')) {
                        $q->where('name', 'like', "$department%");
                    }
                });
            } else {
                $query->whereHas('department', function ($q) use ($department) {
                    if (request('department')) {
                        $q->where('name', 'like', "$department%");
                    }
                });
            }
        })->latest()->paginate();

        return $this->sendResponse($results, 'Result List');
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
     * @param  ResultRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ResultRequest $request)
    {
        $result = Result::updateOrCreate([
            'student_id' => $request->student
        ], [
            'student_id' => $request->student,
            'gpa' => $request->gpa,
            'date' => $request->date
        ]);
        return $this->sendResponse($result, 'Result Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Result  $result
     * @return \Illuminate\Http\Response
     */
    public function show(Result $result)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Result  $result
     * @return \Illuminate\Http\Response
     */
    public function edit(Result $result)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ResultRequest  $request
     * @param  \App\Models\Result  $result
     * @return \Illuminate\Http\Response
     */
    public function update(ResultRequest $request, Result $result)
    {

        $result = $result->update(
            [
                'student_id' => $request->student,
                'gpa' => $request->gpa,
                'date' => $request->date
            ]
        );
        return $this->sendResponse($result, 'Result Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Result  $result
     * @return \Illuminate\Http\Response
     */
    public function destroy(Result $result)
    {
        $result = $result->delete();
        return $this->sendResponse([$result], 'Result has been Deleted');
    }
}
