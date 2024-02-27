<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Task;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Login API to return API token
    public function loginAPI(Request $request)
    {
        // $request->validate([
        //     'email' => 'required|email',
        //     'password' => 'required',
        // ]);

        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['error' =>' Invalid login Details'], 200);
        }
    
        $user = Auth::user();
        $token = $user->createToken('AuthToken')->plainTextToken;
    
        return response()->json(['token' => $token], 200);
    }

    // More 

    ///////////////////////////////////////
    //
    // Task Model functions start
    //
    ///////////////////////////////////////
    
    // List Tasks API
    function tasks(){

        // Classname::method_name();
        $tasks = Task::all();

        if($tasks->count() > 0){
            
            $data = ['status' => 200,
            'tasks' => $tasks];
        }else{
            
            $data = ['status' => 404,
            'error' => 'No Tasks found'];
        }

        return response()->json($data, 200);
    }

    // Create task API
    public function createTask(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'due_date' => 'required|date',
            'status' => 'in:pending,in_progress,completed',
        ]);

        $task = Task::create($request->all());

        if (!$task) {
            return response()->json(['error' => 'Task creation failed'], 500);
        }

        return response()->json(['task' => $task], 201);
    }

    // Update Task API
    public function updateTask(Request $request, $taskId)
    {
        $request->validate([
            'title' => 'required',
            'due_date' => 'required|date',
            'status' => 'in:pending,in_progress,completed',
        ]);

        $task = Task::find($taskId);

        if (!$task) {
            return response()->json(['error' => 'Task not found'], 404);
        }
        
        $task->update($request->all());

        return response()->json(['task' => $task], 200);
    }

    // Delete Task API
    public function deleteTask($taskId)
    {
        $task = Task::find($taskId);

        if (!$task) {
            return response()->json(['error' => 'Task not found'], 404);
        }

        $task->delete();

        return response()->json(['message' => 'Task deleted successfully'], 200);
    }

    // View specific task API
    public function viewTask($taskId)
    {
        $task = Task::find($taskId);

        if (!$task) {
            return response()->json(['error' => 'Task not found'], 404);
        }

        return response()->json(['task' => $task], 200);
    }


    ////////////////////////////////////////////////////////
    //
    // Task assignment APIs
    //
    ////////////////////////////////////////////////////////

    // Assign user to a task
    public function assignUsersToTask(Request $request, $taskId)
    {
        try {
            $request->validate([
                'user_ids' => 'required|array',
                'user_ids.*' => 'exists:users,id',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        $task = Task::find($taskId);
        if (!$task) {
            return response()->json(['error' => 'Task not found'], 404);
        }

        $task->users()->attach($request->input('user_ids'));

        return response()->json(['message' => 'Users assigned to task successfully'], 200);
    }

    // UnAssign user from a task
    public function unassignUserFromTask(Request $request, $taskId, $userId)
    {
        $task = Task::find($taskId);
        if (!$task) {
            return response()->json(['error' => 'Task not found'], 404);
        }

        $task->users()->detach($userId);

        return response()->json(['message' => 'User unassigned from task successfully'], 200);
    }

    // Update status of the task (As A LoggedIn User)
    public function updateStatus(Request $request, $taskId)
    {
        try {
            // Validate the request parameters
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
                'status' => 'required|in:pending,in_progress,completed',
            ]);
    
            // Authenticate the user using the provided credentials
            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json(['error' => 'Invalid credentials.'], 403);
            }

            $user = $request->user();

            // Retrieve tasks assigned to the user
            $tasks = $user->tasks()->where('tasks.id', $taskId)->get();

            if ($tasks->isEmpty()) {
                return response()->json(['error' => 'Task not found for this user'], 404);
            }

            // Find the task by ID
            $task = Task::findOrFail($taskId);
    
            // Update the task status
            $task->status = $request->status;
            $task->save();
    
            return response()->json(['message' => 'Task status updated successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }

    // Update status of the task (Not As A User)
    public function updateTaskStatus(Request $request, $taskId)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $task = Task::find($taskId);
        if (!$task) {
            return response()->json(['error' => 'Task not found'], 404);
        }

        $task->status = $request->input('status');
        $task->save();

        return response()->json(['message' => 'Task status updated successfully'], 200);
    }

   // List tasks assigned to a particular user
    public function tasksAssignedToUser($userId)
    {
        // Retrieve the tasks assigned to the specified user
        $tasks = Task::whereHas('users', function ($query) use ($userId) {
            $query->where('users.id', $userId); // Specify the table alias to avoid ambiguity
        })->get();

        return response()->json(['tasks' => $tasks], 200);
    }

    // List tasks assigned of currently logged-in user
    public function tasksAssignedToCurrentUser(Request $request)
    {
        // Authenticate the user using the provided token
        // $user = $request->user();

        try {
            // Validate the request parameters
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
        
        $user = $request->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        // Retrieve the ID of the authenticated user
        $userId = $user->id;

        // Retrieve the tasks assigned to the authenticated user
        $tasks = Task::whereHas('users', function ($query) use ($userId) {
            $query->where('users.id', $userId);
        })->get();

        return response()->json(['tasks' => $tasks], 200);
    }

    // Filter By Status
    public function tasksByStatus(Request $request, $status)
    {
        try {
            // Retrieve tasks filtered by status
            $tasks = Task::where('status', $status)->get();
            
            if ($tasks->isEmpty()) {
                return response()->json(['error' => "No tasks found with the status '{$status}'."], 404);
            }

            return response()->json(['tasks' => $tasks], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Filter By Date
    public function tasksByDate(Request $request, $date)
    {
        try {
            // Validate the date parameter
            // $request->validate([
            //     'date' => 'required|date',
            // ]);

            // Retrieve tasks filtered by date
            $tasks = Task::whereDate('due_date', $date)->get();
           
            if ($tasks->isEmpty()) {
                return response()->json(['error' => "No tasks found with the date '{$date}'."], 404);
            }

            return response()->json(['tasks' => $tasks], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Filter By Username
    public function tasksByUserName(Request $request, $userName)
    {
        try {
            // Retrieve the user by their name
            $user = User::where('name', $userName)->first();

            // If no user is found, throw an exception
            if (!$user) {
                return response()->json(['error' => "No user found with the name '{$userName}'."], 404);
            }

            // Retrieve tasks assigned to the user
            $tasks = $user->tasks;
            
            if ($tasks->isEmpty()) {
                return response()->json(['error' => "No tasks found for user '{$userName}'."], 404);
            }

            return response()->json(['tasks' => $tasks], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


}
