<?php

namespace App\Http\Controllers;

use App\Http\Resources\groupCollection as ResourcesGroupCollection;
use App\Http\Resources\groupResource;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PHPUnit\TextUI\XmlConfiguration\GroupCollection;

class GroupController extends Controller
{
  use ApiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $groups = Group::with(['course','students','teachers']);
        if($request->pagination!=null){
            $groups = $groups->paginate($request->pagination);
        }
        else{
            $groups = $groups->paginate(10);
        }
        return response(new ResourcesGroupCollection($groups));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string',
        'course_id' => 'nullable|exists:courses,id',
    ]);

    if ($validator->fails()) {
        return $this->apiresponse(null, 'validation error', $validator->errors(), 400);
    }

    $group = Group::create([
        'name' => $request->name,
        'course_id' => $request->course_id,
    ]);
//add teachers
    if ($group && $request->teachers) {
        $teachers = $request->teachers;

        // تأكد أنها مصفوفة IDs
        if (!is_array($teachers)) {
            $teachers = [$teachers];
        }

        // إضافة المعلمين إلى المجموعة
        $group->teachers()->syncWithoutDetaching($teachers);
    }
//add students
    if ($group && $request->students) {
        $students = $request->students;

        // تأكد أنها مصفوفة IDs
        if (!is_array($students)) {
            $students = [$students];
        }

        // إضافة المعلمين إلى المجموعة
        $group->students()->syncWithoutDetaching($students);
    }

    return $this->apiresponse($group, 'group created successfully', 201);
}


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $group = Group::with(['course','students','teachers'])->find($id);
        if($group){
          return $this->apiresponse(new groupResource($group),'group found',201);
        }
        return $this->apiresponse(null,'group not found',404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
public function update(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string',
        'course_id' => 'nullable|exists:courses,id',
    ]);

    if ($validator->fails()) {
        return $this->apiresponse(null, 'validation error', $validator->errors(), 400);
    }

    $group = Group::find($id);

    if (!$group) {
        return $this->apiresponse(null, 'Group not found', null, 404);
    }

    // تحديث بيانات المجموعة
    $group->update([
        'name' => $request->name,
        'course_id' => $request->course_id,
    ]);

    // تحديث المعلمين بدون تكرار
    if ($request->teachers) {
        $teachers = is_array($request->teachers) ? $request->teachers : [$request->teachers];
        $group->teachers()->syncWithoutDetaching($teachers);
    }

    // تحديث الطلاب بدون تكرار
    if ($request->students) {
        $students = is_array($request->students) ? $request->students : [$request->students];
        $group->students()->syncWithoutDetaching($students);
    }

    // إعادة النتيجة مع العلاقات
    return $this->apiresponse(
        $group->load(['teachers', 'students']),
        'group updated successfully',
        200
    );
}


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
{
    $group = Group::find($id);

    if (!$group) {
        return $this->apiresponse(null, 'Group not found', null, 404);
    }

    // حذف الربط مع المعلمين والطلاب
    $group->teachers()->detach();
    $group->students()->detach();

    // حذف المجموعة
    $group->delete();

    return $this->apiresponse(null, 'Group deleted successfully', 200);
}

}
