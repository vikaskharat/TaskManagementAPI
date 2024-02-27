<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthController::class, 'loginAPI']);

// Routes for Tasks APIs
Route::get('tasks', [AuthController::class, 'tasks']);                      // To list all tasks
/* USAGE : 
    
*/
Route::post('/tasks', [AuthController::class, 'createTask']);               // To create new task
Route::put('/tasks/{taskId}', [AuthController::class, 'updateTask']);       // To update existing task
Route::delete('/tasks/{taskId}', [AuthController::class, 'deleteTask']);    // To delete existing task
Route::get('/tasks/{taskId}', [AuthController::class, 'viewTask']);         // To display specific task

// Task Assignment
Route::post('/tasks/{taskId}/assign', [AuthController::class, 'assignUsersToTask']);

// Task Unassignment
Route::delete('/tasks/{taskId}/unassign/{userId}', [AuthController::class, 'unassignUserFromTask']);

// Task Status Update as a loggedIn User
Route::put('/utask_update/{taskID}/status', [AuthController::class, 'updateStatus']);

// Task Status Update
Route::put('/task_update/{taskId}/status', [AuthController::class, 'updateTaskStatus']);

Route::get('/users/{userId}/tasks', [AuthController::class, 'tasksAssignedToUser']);

///////////////////////////
/// Routes for Filters ///
//////////////////////////

// Filter by status
Route::get('/get_tasks/status/{status}', [AuthController::class, 'tasksByStatus']);
// Filter by date
Route::get('/get_tasks/date/{date}', [AuthController::class, 'tasksByDate']);
// Filter by username
Route::get('/get_tasks/user/{username}', [AuthController::class, 'tasksByUserName']);


// Get tasks assigned to user after login. Route for logged in User
Route::get('/tasksu/tasksofuser', [AuthController::class, 'tasksAssignedToCurrentUser']);

