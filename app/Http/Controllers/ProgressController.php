<?php

namespace App\Http\Controllers;

use App\Models\Progress;
use App\Models\ProgressSubmission;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProgressController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isAdmin() || $user->isManager()) {
            $progress = Progress::with(['uploader', 'submissions.submitter'])->latest()->paginate(10);
        } else {
            // Pegawai hanya melihat progress yang perlu mereka kerjakan
            $progress = Progress::with(['uploader', 'submissions' => function($query) use ($user) {
                $query->where('submitted_by', $user->id);
            }])->latest()->paginate(10);
        }

        return view('progress.index', compact('progress'));
    }

    public function create()
    {
        // Only admin and manager can create progress
        if (!auth()->user()->isAdmin() && !auth()->user()->isManager()) {
            abort(403);
        }

        return view('progress.create');
    }

    public function store(Request $request)
    {
        // Only admin and manager can create progress
        if (!auth()->user()->isAdmin() && !auth()->user()->isManager()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'keterangan' => 'required|string',
            'due_date' => 'required|date|after:today',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|max:10240'
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('progress', 'public');
        }

        $progress = Progress::create([
            'uploaded_by' => auth()->id(),
            'title' => $validated['title'],
            'keterangan' => $validated['keterangan'],
            'due_date' => $validated['due_date'],
            'file_path' => $filePath,
        ]);

        // Notify all employees about new progress
        $employees = User::where('role', 'pegawai')->get();
        foreach ($employees as $employee) {
            Notification::create([
                'user_id' => $employee->id,
                'title' => 'Progress Baru',
                'message' => "Progress baru '{$progress->title}' telah ditambahkan",
                'type' => 'new_progress'
            ]);
        }

        // Notify admin about new progress
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            if ($admin->id !== auth()->id()) {
                Notification::create([
                    'user_id' => $admin->id,
                    'title' => 'Progress Baru',
                    'message' => "Progress baru '{$progress->title}' telah ditambahkan oleh " . auth()->user()->name,
                    'type' => 'new_progress'
                ]);
            }
        }

        return redirect()->route('progress.index')->with('success', 'Progress berhasil dibuat!');
    }

    public function show(Progress $progress)
    {
        $progress->load(['uploader', 'submissions.submitter']);
        return view('progress.show', compact('progress'));
    }

    public function submitProgress(Request $request, Progress $progress)
    {
        $validated = $request->validate([
            'keterangan' => 'required|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|max:10240'
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('submissions', 'public');
        }

        ProgressSubmission::create([
            'progress_id' => $progress->id,
            'submitted_by' => auth()->id(),
            'keterangan' => $validated['keterangan'],
            'file_path' => $filePath,
        ]);

        // Notify manager and admin about submission
        $managers = User::where('role', 'manager')->get();
        $admins = User::where('role', 'admin')->get();
        
        $notificationMessage = auth()->user()->name . " telah mengsubmit progress '{$progress->title}'";
        
        foreach ($managers as $manager) {
            Notification::create([
                'user_id' => $manager->id,
                'title' => 'Progress Submission',
                'message' => $notificationMessage,
                'type' => 'new_progress'
            ]);
        }

        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'title' => 'Progress Submission',
                'message' => $notificationMessage,
                'type' => 'new_progress'
            ]);
        }

        return redirect()->back()->with('success', 'Progress berhasil disubmit!');
    }

    public function downloadFile(Progress $progress)
    {
        if (!$progress->file_path || !Storage::disk('public')->exists($progress->file_path)) {
            abort(404);
        }

        return Storage::disk('public')->download($progress->file_path);
    }

    public function downloadSubmission(ProgressSubmission $submission)
    {
        if (!$submission->file_path || !Storage::disk('public')->exists($submission->file_path)) {
            abort(404);
        }

        return Storage::disk('public')->download($submission->file_path);
    }

    public function updateStatus(Request $request, Progress $progress)
    {
        // Only admin and manager can update status
        if (!auth()->user()->isAdmin() && !auth()->user()->isManager()) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,completed'
        ]);

        $progress->update(['status' => $validated['status']]);

        // Notify all users involved about status change
        $statusText = match($validated['status']) {
            'pending' => 'Menunggu',
            'in_progress' => 'Sedang Dikerjakan',
            'completed' => 'Selesai',
            default => 'Unknown'
        };

        // Notify progress creator if different from current user
        if ($progress->uploaded_by !== auth()->id()) {
            Notification::create([
                'user_id' => $progress->uploaded_by,
                'title' => 'Status Progress Diubah',
                'message' => "Status progress '{$progress->title}' diubah menjadi '{$statusText}' oleh " . auth()->user()->name,
                'type' => 'new_progress'
            ]);
        }

        // Notify employees who submitted this progress
        $submitters = $progress->submissions()->pluck('submitted_by')->unique();
        foreach ($submitters as $submitterId) {
            if ($submitterId !== auth()->id()) {
                Notification::create([
                    'user_id' => $submitterId,
                    'title' => 'Status Progress Diubah',
                    'message' => "Status progress '{$progress->title}' diubah menjadi '{$statusText}' oleh " . auth()->user()->name,
                    'type' => 'new_progress'
                ]);
            }
        }

        return redirect()->back()->with('success', 'Status progress berhasil diubah!');
    }

    public function updateSubmissionStatus(Request $request, ProgressSubmission $submission)
    {
        // Only admin and manager can update submission status
        if (!auth()->user()->isAdmin() && !auth()->user()->isManager()) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:submitted,approved,rejected'
        ]);

        $submission->update(['status' => $validated['status']]);

        $statusText = match($validated['status']) {
            'submitted' => 'Disubmit',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            default => 'Unknown'
        };

        // Notify the submitter
        Notification::create([
            'user_id' => $submission->submitted_by,
            'title' => 'Status Submission Diubah',
            'message' => "Submission Anda untuk progress '{$submission->progress->title}' telah {$statusText} oleh " . auth()->user()->name,
            'type' => 'new_progress'
        ]);

        return redirect()->back()->with('success', 'Status submission berhasil diubah!');
    }

    
}