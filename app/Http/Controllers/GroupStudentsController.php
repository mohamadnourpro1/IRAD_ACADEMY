<?php

namespace App\Http\Controllers;

use App\Models\GroupStudents;
use Illuminate\Http\Request;

class GroupStudentsController extends Controller
{
  use ApiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\GroupStudents  $groupStudents
     * @return \Illuminate\Http\Response
     */
    public function show(GroupStudents $groupStudents)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\GroupStudents  $groupStudents
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GroupStudents $groupStudents)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GroupStudents  $groupStudents
     * @return \Illuminate\Http\Response
     */
    public function destroy(GroupStudents $groupStudents)
    {
        //
    }
}
