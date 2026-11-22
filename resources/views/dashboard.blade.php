@extends('layout.app')

@section('title', 'Dashboard - TaskFlow')

@section('content')
<div class="space-y-8 pb-12">

    <!-- Top Hero Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                Welcome back, {{ $user->name }}! 👋
            </h1>
            <p class="text-slate-500 text-sm mt-1">
                Organize your day, track progress, and complete your tasks with ease.
            </p>
        </div>
        <div class="flex items-center gap-3">
            <button type="button"
                    onclick="openCreateModal()"
                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 active:scale-[0.98] shadow-md shadow-indigo-600/25 transition-all duration-150">
                <svg class="w-5 h-5 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>New Task</span>
            </button>
        </div>
    </div>

    <!-- Task Statistics Overview -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        <!-- Total Tasks -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Tasks</span>
                <p class="text-3xl font-extrabold text-slate-900 mt-1">{{ $stats['total'] }}</p>
                <span class="text-xs text-slate-400 mt-1 inline-block">All active tasks</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
        </div>

        <!-- Pending Tasks -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <span class="text-xs font-semibold text-amber-600 uppercase tracking-wider">Pending</span>
                <p class="text-3xl font-extrabold text-amber-600 mt-1">{{ $stats['pending'] }}</p>
                <span class="text-xs text-slate-400 mt-1 inline-block">Needs your attention</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <!-- Completed Tasks -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <span class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Completed</span>
                <p class="text-3xl font-extrabold text-emerald-600 mt-1">{{ $stats['completed'] }}</p>
                <span class="text-xs text-slate-400 mt-1 inline-block">Successfully finished</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <!-- Completion Rate -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-blue-600 uppercase tracking-wider">Completion Rate</span>
                <span class="text-xs font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded-full">{{ $stats['completion_rate'] }}%</span>
            </div>
            <div class="mt-3">
                <div class="flex items-baseline justify-between mb-2">
                    <span class="text-2xl font-black text-slate-900">{{ $stats['completion_rate'] }}%</span>
                    <span class="text-xs text-slate-400 font-medium">{{ $stats['completed'] }}/{{ $stats['total'] }} done</span>
                </div>
                <!-- Progress bar -->
                <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-2.5 rounded-full transition-all duration-500"
                         style="width: {{ $stats['completion_rate'] }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Task Add Form -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-sm">
        <form action="{{ route('tasks.store') }}" method="POST" id="quick-create-form" class="space-y-3">
            @csrf
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                    </div>
                    <input type="text"
                           name="title"
                           id="quick-task-title"
                           required
                           maxlength="150"
                           placeholder="What do you need to do? (e.g. Review quarter budget)"
                           class="w-full pl-10 pr-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                </div>
                <div class="flex items-center gap-2">
                    <button type="button"
                            onclick="toggleQuickDesc()"
                            id="toggle-desc-btn"
                            class="px-3.5 py-3 text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors shrink-0"
                            title="Add optional description">
                        + Description
                    </button>
                    <button type="submit"
                            id="quick-submit-btn"
                            class="px-6 py-3 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 active:scale-[0.98] shadow-sm shadow-indigo-600/30 transition-all shrink-0">
                        Add Task
                    </button>
                </div>
            </div>
            <!-- Collapsible description field -->
            <div id="quick-desc-wrapper" class="hidden pt-1">
                <textarea name="description"
                          rows="2"
                          maxlength="2000"
                          placeholder="Add more details or notes about this task (optional)..."
                          class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"></textarea>
            </div>
        </form>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
        <!-- Status Filter Tabs -->
        <div class="flex items-center gap-1.5 p-1 bg-slate-100 rounded-xl self-start md:self-auto overflow-x-auto max-w-full">
            <a href="{{ route('dashboard', array_merge(request()->query(), ['status' => 'all'])) }}"
               class="px-4 py-2 rounded-lg text-xs sm:text-sm font-semibold transition-all duration-150 flex items-center gap-2 shrink-0 {{ ($status === 'all' || !$status) ? 'bg-white text-indigo-700 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                <span>All Tasks</span>
                <span class="px-2 py-0.5 rounded-full text-[11px] {{ ($status === 'all' || !$status) ? 'bg-indigo-50 text-indigo-700' : 'bg-slate-200 text-slate-600' }}">
                    {{ $stats['total'] }}
                </span>
            </a>

            <a href="{{ route('dashboard', array_merge(request()->query(), ['status' => 'pending'])) }}"
               class="px-4 py-2 rounded-lg text-xs sm:text-sm font-semibold transition-all duration-150 flex items-center gap-2 shrink-0 {{ $status === 'pending' ? 'bg-white text-amber-700 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                <span>Pending</span>
                <span class="px-2 py-0.5 rounded-full text-[11px] {{ $status === 'pending' ? 'bg-amber-100 text-amber-700' : 'bg-slate-200 text-slate-600' }}">
                    {{ $stats['pending'] }}
                </span>
            </a>

            <a href="{{ route('dashboard', array_merge(request()->query(), ['status' => 'completed'])) }}"
               class="px-4 py-2 rounded-lg text-xs sm:text-sm font-semibold transition-all duration-150 flex items-center gap-2 shrink-0 {{ $status === 'completed' ? 'bg-white text-emerald-700 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                <span>Completed</span>
                <span class="px-2 py-0.5 rounded-full text-[11px] {{ $status === 'completed' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-600' }}">
                    {{ $stats['completed'] }}
                </span>
            </a>
        </div>

        <!-- Search Box -->
        <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-2 flex-1 md:max-w-md">
            @if ($status && $status !== 'all')
                <input type="hidden" name="status" value="{{ $status }}">
            @endif

            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text"
                       name="search"
                       value="{{ $search }}"
                       placeholder="Search tasks by title or details..."
                       class="w-full pl-9 pr-9 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                @if ($search)
                    <a href="{{ route('dashboard', ['status' => $status]) }}"
                       class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600"
                       title="Clear search">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                @endif
            </div>

            <button type="submit"
                    class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-sm transition-colors">
                Search
            </button>
        </form>
    </div>

    <!-- Active filter alert notice if filtered or searched -->
    @if ($search || ($status && $status !== 'all'))
        <div class="flex items-center justify-between px-4 py-2.5 bg-indigo-50/60 border border-indigo-100 rounded-xl text-xs text-indigo-900">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                <span>
                    Filtered by:
                    @if ($status && $status !== 'all') <strong class="capitalize">{{ $status }}</strong> @endif
                    @if ($search) with query "<strong>{{ $search }}</strong>" @endif
                    ({{ $tasks->total() }} results)
                </span>
            </div>
            <a href="{{ route('dashboard') }}" class="font-semibold text-indigo-700 hover:underline">
                Clear all filters
            </a>
        </div>
    @endif

    <!-- Task Items List -->
    <div class="space-y-3">
        @forelse ($tasks as $task)
            <div class="group bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 {{ $task->is_completed ? 'bg-slate-50/80 border-slate-200/60' : '' }}">
                <!-- Left: Toggle & Info -->
                <div class="flex items-start gap-3.5 flex-1 min-w-0">
                    <!-- Toggle Status Form -->
                    <form action="{{ route('tasks.toggle', $task) }}" method="POST" class="mt-0.5 shrink-0">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                                title="{{ $task->is_completed ? 'Mark as Pending' : 'Mark as Completed' }}"
                                class="w-6 h-6 rounded-lg border-2 flex items-center justify-center transition-all duration-150 {{ $task->is_completed ? 'bg-emerald-500 border-emerald-500 text-white shadow-sm shadow-emerald-500/25' : 'border-slate-300 hover:border-indigo-500 hover:bg-indigo-50/50' }}">
                            @if ($task->is_completed)
                                <svg class="w-4 h-4 stroke-[3]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                            @endif
                        </button>
                    </form>

                    <!-- Title, Description & Badges -->
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <h3 class="text-base font-semibold leading-snug break-words {{ $task->is_completed ? 'line-through text-slate-400' : 'text-slate-900' }}">
                                {{ $task->title }}
                            </h3>

                            <!-- Status Badge -->
                            @if ($task->is_completed)
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Completed
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200/60">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    Pending
                                </span>
                            @endif
                        </div>

                        @if ($task->description)
                            <p class="text-sm mt-1.5 leading-relaxed break-words {{ $task->is_completed ? 'text-slate-400' : 'text-slate-600' }}">
                                {{ $task->description }}
                            </p>
                        @endif

                        <div class="flex items-center gap-4 text-xs text-slate-400 mt-2">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Created {{ $task->created_at->diffForHumans() }}
                            </span>
                            @if ($task->updated_at != $task->created_at)
                                <span>&bull;</span>
                                <span>Updated {{ $task->updated_at->diffForHumans() }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right: Action Buttons -->
                <div class="flex items-center gap-1.5 self-end sm:self-center shrink-0 border-t sm:border-t-0 pt-2 sm:pt-0 w-full sm:w-auto justify-end">
                    <!-- Edit Button -->
                    <button type="button"
                            onclick="openEditModal({{ json_encode($task) }})"
                            title="Edit Task"
                            class="p-2 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                        </svg>
                    </button>

                    <!-- Delete Button -->
                    <button type="button"
                            onclick="openDeleteModal({{ $task->id }}, '{{ addslashes($task->title) }}')"
                            title="Delete Task"
                            class="p-2 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            </div>
        @empty
            <!-- Empty State -->
            <div class="bg-white p-8 sm:p-12 rounded-3xl border border-dashed border-slate-300 text-center flex flex-col items-center justify-center">
                @if ($search || ($status && $status !== 'all'))
                    <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-500 flex items-center justify-center mb-4">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">No matching tasks found</h3>
                    <p class="text-sm text-slate-500 max-w-sm mt-1 mb-5">
                        No tasks matched your current search and filter criteria. Try clearing filters or searching for something else.
                    </p>
                    <a href="{{ route('dashboard') }}" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold rounded-xl shadow-sm transition-colors">
                        Reset Filters
                    </a>
                @else
                    <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-4">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">You don't have any tasks yet</h3>
                    <p class="text-sm text-slate-500 max-w-sm mt-1 mb-5">
                        Stay focused and organized! Create your first task to get started tracking your accomplishments.
                    </p>
                    <button type="button"
                            onclick="openCreateModal()"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-md shadow-indigo-600/30 transition-all">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <span>Create Your First Task</span>
                    </button>
                @endif
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if ($tasks->hasPages())
        <div class="mt-6">
            {{ $tasks->links() }}
        </div>
    @endif
</div>

<!-- ================= CREATE TASK MODAL ================= -->
<div id="create-modal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/50 backdrop-blur-sm transition-opacity duration-200">
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl border border-slate-100 transform transition-all duration-200">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-slate-900">Create New Task</h2>
                </div>
                <button type="button" onclick="closeCreateModal()" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Form -->
            <form action="{{ route('tasks.store') }}" method="POST" id="modal-create-form" class="mt-4 space-y-4">
                @csrf
                <div>
                    <label for="create-title" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Task Title <span class="text-rose-500">*</span>
                    </label>
                    <input type="text"
                           name="title"
                           id="create-title"
                           required
                           maxlength="150"
                           placeholder="e.g., Finalize project documentation"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none transition-all">
                </div>

                <div>
                    <label for="create-description" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Description (Optional)
                    </label>
                    <textarea name="description"
                              id="create-description"
                              rows="3"
                              maxlength="2000"
                              placeholder="Provide additional context or requirements..."
                              class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none transition-all"></textarea>
                </div>

                <!-- Modal Actions -->
                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button"
                            onclick="closeCreateModal()"
                            class="px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                            id="create-task-submit"
                            class="px-5 py-2 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-md shadow-indigo-600/30 transition-all flex items-center gap-2">
                        <span>Create Task</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ================= EDIT TASK MODAL ================= -->
<div id="edit-modal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/50 backdrop-blur-sm transition-opacity duration-200">
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl border border-slate-100 transform transition-all duration-200">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-slate-900">Edit Task</h2>
                </div>
                <button type="button" onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Form -->
            <form action="" method="POST" id="edit-task-form" class="mt-4 space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label for="edit-title" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Task Title <span class="text-rose-500">*</span>
                    </label>
                    <input type="text"
                           name="title"
                           id="edit-title"
                           required
                           maxlength="150"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none transition-all">
                </div>

                <div>
                    <label for="edit-description" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Description
                    </label>
                    <textarea name="description"
                              id="edit-description"
                              rows="3"
                              maxlength="2000"
                              class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none transition-all"></textarea>
                </div>

                <div class="flex items-center gap-2 pt-1">
                    <input type="hidden" name="is_completed" value="0">
                    <input type="checkbox"
                           name="is_completed"
                           id="edit-is-completed"
                           value="1"
                           class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300">
                    <label for="edit-is-completed" class="text-sm font-medium text-slate-700 select-none">
                        Mark task as completed
                    </label>
                </div>

                <!-- Modal Actions -->
                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button"
                            onclick="closeEditModal()"
                            class="px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                            id="edit-task-submit"
                            class="px-5 py-2 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-md shadow-indigo-600/30 transition-all flex items-center gap-2">
                        <span>Save Changes</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ================= DELETE CONFIRMATION MODAL ================= -->
<div id="delete-modal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/50 backdrop-blur-sm transition-opacity duration-200">
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl border border-slate-100 transform transition-all duration-200">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Delete Task</h3>
                    <p class="text-sm text-slate-500 mt-1 leading-relaxed">
                        Are you sure you want to delete <span id="delete-task-title-display" class="font-semibold text-slate-800">this task</span>? This action cannot be reversed.
                    </p>
                </div>
            </div>

            <form action="" method="POST" id="delete-task-form" class="mt-6 flex items-center justify-end gap-3">
                @csrf
                @method('DELETE')
                <button type="button"
                        onclick="closeDeleteModal()"
                        class="px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition-colors">
                    Cancel
                </button>
                <button type="submit"
                        id="delete-task-submit"
                        class="px-5 py-2 text-sm font-semibold text-white bg-rose-600 hover:bg-rose-700 rounded-xl shadow-md shadow-rose-600/30 transition-all">
                    Delete Task
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Quick Add description toggler
    function toggleQuickDesc() {
        const descWrapper = document.getElementById('quick-desc-wrapper');
        const toggleBtn = document.getElementById('toggle-desc-btn');
        if (descWrapper.classList.contains('hidden')) {
            descWrapper.classList.remove('hidden');
            toggleBtn.textContent = '- Description';
            descWrapper.querySelector('textarea').focus();
        } else {
            descWrapper.classList.add('hidden');
            toggleBtn.textContent = '+ Description';
        }
    }

    // Create Modal handlers
    function openCreateModal() {
        const modal = document.getElementById('create-modal');
        modal.classList.remove('hidden');
        setTimeout(() => document.getElementById('create-title').focus(), 50);
    }

    function closeCreateModal() {
        document.getElementById('create-modal').classList.add('hidden');
        document.getElementById('modal-create-form').reset();
    }

    // Edit Modal handlers
    function openEditModal(task) {
        const modal = document.getElementById('edit-modal');
        const form = document.getElementById('edit-task-form');

        form.action = `/tasks/${task.id}`;
        document.getElementById('edit-title').value = task.title;
        document.getElementById('edit-description').value = task.description || '';
        document.getElementById('edit-is-completed').checked = Boolean(task.is_completed);

        modal.classList.remove('hidden');
        setTimeout(() => document.getElementById('edit-title').focus(), 50);
    }

    function closeEditModal() {
        document.getElementById('edit-modal').classList.add('hidden');
    }

    // Delete Modal handlers
    function openDeleteModal(taskId, taskTitle) {
        const modal = document.getElementById('delete-modal');
        const form = document.getElementById('delete-task-form');

        form.action = `/tasks/${taskId}`;
        document.getElementById('delete-task-title-display').textContent = `"${taskTitle}"`;

        modal.classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('delete-modal').classList.add('hidden');
    }

    // Close modals on Escape key or backdrop click
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeCreateModal();
            closeEditModal();
            closeDeleteModal();
        }
    });

    ['create-modal', 'edit-modal', 'delete-modal'].forEach((id) => {
        const modal = document.getElementById(id);
        if (modal) {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                }
            });
        }
    });

    // Loading states on forms
    ['quick-create-form', 'modal-create-form', 'edit-task-form', 'delete-task-form'].forEach((formId) => {
        const form = document.getElementById(formId);
        if (form) {
            form.addEventListener('submit', function() {
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-70', 'cursor-not-allowed');
                }
            });
        }
    });
</script>
@endpush
@endsection
