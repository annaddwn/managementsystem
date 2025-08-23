<?php

namespace App\Http\Controllers;

use App\Models\Progress;
use App\Models\Document;
use App\Models\Notification;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $stats = [
            'total_progress' => Progress::count(),
            'my_progress' => Progress::where('uploaded_by', $user->id)->count(),
            'total_documents' => Document::count(),
            'my_documents' => Document::where('uploaded_by', $user->id)->count(),
            'unread_notifications' => $user->notifications()->where('is_read', false)->count(),
        ];

        $recentProgress = Progress::with('uploader')
            ->latest()
            ->take(5)
            ->get();

        $recentDocuments = Document::with('uploader')
            ->latest()
            ->take(5)
            ->get();

        $notifications = $user->notifications()
            ->where('is_read', false)
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.index', compact('stats', 'recentProgress', 'recentDocuments', 'notifications'));
    }

    public function getNotifications()
    {
        $user = auth()->user();
        $notifications = $user->notifications()
            ->latest()
            ->take(20)
            ->get();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $user->notifications()->where('is_read', false)->count()
        ]);
    }

    public function markNotificationRead(Request $request)
    {
        $notification = auth()->user()->notifications()->find($request->id);
        if ($notification) {
            $notification->update(['is_read' => true]);
        }

        return response()->json(['success' => true]);
    }

    public function markAllNotificationsRead()
    {
        auth()->user()->notifications()->where('is_read', false)->update(['is_read' => true]);
        return response()->json(['success' => true]);
    }
}