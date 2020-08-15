<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Task;


class TaskController extends Controller
{
    private $sucess_status = 200;

    // --------------- [ Create Task ] ------------------
    public function createTask(Request $request) {
        $user           =           Auth::user();
        $validator      =           Validator::make($request->all(),
            [
                "task_title"        =>      "required",
                "description"       =>      "required",
            ]
        );

        if($validator->fails()) {
            return response()->json(["validation_errors" => $validator->errors()]);
        }

        $task_array         =       array(
            "task_title"        =>      $request->task_title,
            "description"       =>      $request->description,
            "status"            =>      $request->status,
            "user_id"           =>      $user->id
        );

        $task_id            =       $request->task_id;

        if($task_id != "") {
            $task_status    =       Task::where("id", $task_id)->update($task_array);

            if($task_status == 1) {
                return response()->json(["status" => $this->sucess_status, "success" => true, "message" => "Todo updated successfully", "data" => $task_array]);
            }

            else {
                return response()->json(["status" => $this->sucess_status, "success" => true, "message" => "Todo not updated"]);
            }

        }

        $task               =       Task::create($task_array);

        if(!is_null($task)) {
            return response()->json(["status" => $this->sucess_status, "success" => true, "data" => $task]);
        }

        else {
            return response()->json(["status" => "failed", "success" => false, "message" => "Whoops! task not created."]);
        }
    }

    // ---------------- [ Task Listing ] -----------------
    public function tasks() {
        $tasks          =           array();
        $user           =           Auth::user();
        $tasks          =           Task::where("user_id", $user->id)->get();
        if(count($tasks) > 0) {
            return response()->json(["status" => $this->sucess_status, "success" => true, "count" => count($tasks), "data" => $tasks]);
        }

        else {
            return response()->json(["status" => "failed", "success" => false, "message" => "Whoops! no todo found"]);
        }
    }

    // ------------------ [ Task Detail ] -------------------
    public function task($task_id) {
        if($task_id == 'undefined' || $task_id == "") {
            return response()->json(["status" => "failed", "success" => false, "message" => "Alert! enter the task id"]);
        }

        $task       =           Task::find($task_id);

        if(!is_null($task)) {
            return response()->json(["status" => $this->sucess_status, "success" => true, "data" => $task]);
        }

        else {
            return response()->json(["status" => "failed", "success" => false, "message" => "Whoops! no todo found"]);
        }
    }

    // ----------------- [ Delete Task ] -------------------
    public function deleteTask($task_id) {
        if($task_id == 'undefined' || $task_id == "") {
            return response()->json(["status" => "failed", "success" => false, "message" => "Alert! enter the task id"]);
        }

        $task       =           Task::find($task_id);

        if(!is_null($task)) {

            $delete_status  =   Task::where("id", $task_id)->delete();

            if($delete_status == 1) {

                return response()->json(["status" => $this->sucess_status, "success" => true, "message" => "Success! todo deleted"]);
            }

            else {
                return response()->json(["status" => "failed", "success" => false, "message" => "Alert! todo not deleted"]);
            }
        }

        else {
            return response()->json(["status" => "failed", "success" => false, "message" => "Alert! todo not found"]);
        }
    }
}
