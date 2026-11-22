<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TaskController extends Controller
{
    /**
     * Display a listing of the authenticated user's tasks with stats, search, and filtering.
     */
    public function index(Request $request): View|JsonResponse
    {
        $user = Auth::user();

        // Aggregate statistics for all user tasks
        $allTasksQuery = $user->tasks();
        $totalTasks = (clone $allTasksQuery)->count();
        $completedTasks = (clone $allTasksQuery)->where('is_completed', true)->count();
        $pendingTasks = $totalTasks - $completedTasks;
        $completionRate = $totalTasks > 0 ? (int) round(($completedTasks / $totalTasks) * 100) : 0;

        $stats = [
            'total' => $totalTasks,
            'completed' => $completedTasks,
            'pending' => $pendingTasks,
            'completion_rate' => $completionRate,
        ];

        // Query tasks with search, status filter, and latest sorting
        $search = $request->input('search');
        $status = $request->input('status', 'all');

        $tasks = $user->tasks()
            ->search($search)
            ->status($status)
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        if ($request->wantsJson()) {
            return response()->json([
                'stats' => $stats,
                'tasks' => $tasks,
            ]);
        }

        return view('dashboard', compact('user', 'tasks', 'stats', 'search', 'status'));
    }

    /**
     * Store a newly created task.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'nullable|string|max:2000',
        ]);

        $task = $request->user()->tasks()->create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'is_completed' => false,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Task created successfully.',
                'task' => $task,
            ], 201);
        }

        return back()->with('success', 'Task "' . $task->title . '" created successfully.');
    }

    /**
     * Update the specified task.
     */
    public function update(Request $request, Task $task): RedirectResponse|JsonResponse
    {
        // Authorize that the task belongs to the authenticated user
        if ($task->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'nullable|string|max:2000',
            'is_completed' => 'nullable|boolean',
        ]);

        $task->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Task updated successfully.',
                'task' => $task,
            ]);
        }

        return back()->with('success', 'Task updated successfully.');
    }

    /**
     * Remove the specified task.
     */
    public function destroy(Request $request, Task $task): RedirectResponse|JsonResponse
    {
        // Authorize that the task belongs to the authenticated user
        if ($task->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $title = $task->title;
        $task->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Task deleted successfully.',
            ]);
        }

        return back()->with('success', 'Task "' . $title . '" deleted successfully.');
    }

    /**
     * Toggle the completion status of the specified task.
     */
    public function toggleStatus(Request $request, Task $task): RedirectResponse|JsonResponse
    {
        // Authorize that the task belongs to the authenticated user
        if ($task->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $task->is_completed = ! $task->is_completed;
        $task->save();

        $statusText = $task->is_completed ? 'completed' : 'pending';

        if ($request->wantsJson()) {
            return response()->json([
                'message' => "Task marked as {$statusText}.",
                'task' => $task,
            ]);
        }

        return back()->with('success', "Task marked as {$statusText}.");
    }
}
