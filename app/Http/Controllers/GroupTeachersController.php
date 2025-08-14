<?php

namespace App\Http\Controllers;

use App\Models\GroupTeachers;
use Illuminate\Http\Request;

class GroupTeachersController extends Controller
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
     * @param  \App\Models\GroupTeachers  $groupTeachers
     * @return \Illuminate\Http\Response
     */
    public function show(GroupTeachers $groupTeachers)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\GroupTeachers  $groupTeachers
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GroupTeachers $groupTeachers)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GroupTeachers  $groupTeachers
     * @return \Illuminate\Http\Response
     */
    public function destroy(GroupTeachers $groupTeachers)
    {
        //
    }
}
