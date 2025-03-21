<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TaskController extends Controller
{
    public function index()
    {
        try {
            $tasks = Task::query()->latest()->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Tasks fetched successfully',
                'data' => $tasks
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching tasks',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string',
                'description' => 'required|string',
                'status' => 'required|in:todo,inProgress,done',
                'priority' => 'required|in:low,medium,high',
                'deadline' => 'required|date'
            ]);

            $task = Task::create($validatedData);

            return response()->json([
                'status' => 'success',
                'message' => 'Task created successfully',
                'data' => $task
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while creating task',
                'error' => $th->getMessage()
            ], 500);
        } catch (ValidationException $ve) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'error' => $ve->errors()
            ], 422);
        }
    }

    public function show($id)
    {
        try {
            $task = Task::findOrFail($id);

            return response()->json([
                'status' => 'success',
                'message' => 'Task fetched successfully',
                'data' => $task
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching task',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $task = Task::findOrFail($id);

            $validatedData = $request->validate([
                'name' => 'required|string',
                'description' => 'required|string',
                'status' => 'required|in:todo,inProgress,done',
                'priority' => 'required|in:low,medium,high',
                'deadline' => 'required|date'
            ]);

            $task->update($validatedData);

            return response()->json([
                'status' => 'success',
                'message' => 'Task updated successfully',
                'data' => $task
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while updating task',
                'error' => $th->getMessage()
            ], 500);
        } catch (ValidationException $ve) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'error' => $ve->errors()
            ], 422);
        }
    }

    public function destroy($id)
    {
        try {
            $task = Task::findOrFail($id);

            $task->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Task deleted successfully'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while deleting task',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
