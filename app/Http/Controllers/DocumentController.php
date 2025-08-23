<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::with('uploader')->latest()->paginate(10);
        return view('documents.index', compact('documents'));
    }

    public function create()
    {
        return view('documents.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis' => 'required|string|max:255',
            'judul' => 'required|string|max:255',
            'keterangan' => 'required|string',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png|max:10240'
        ]);

        $filePath = $request->file('file')->store('documents', 'public');

        $document = Document::create([
            'uploaded_by' => auth()->id(),
            'jenis' => $validated['jenis'],
            'judul' => $validated['judul'],
            'keterangan' => $validated['keterangan'],
            'file_path' => $filePath,
        ]);

        // Notify admin and manager about new document
        $admins = User::where('role', 'admin')->get();
        $managers = User::where('role', 'manager')->get();
        
        $notificationMessage = auth()->user()->name . " telah mengupload dokumen baru '{$document->judul}'";
        
        foreach ($admins as $admin) {
            if ($admin->id !== auth()->id()) {
                Notification::create([
                    'user_id' => $admin->id,
                    'title' => 'Dokumen Baru',
                    'message' => $notificationMessage,
                    'type' => 'new_document'
                ]);
            }
        }

        foreach ($managers as $manager) {
            if ($manager->id !== auth()->id()) {
                Notification::create([
                    'user_id' => $manager->id,
                    'title' => 'Dokumen Baru',
                    'message' => $notificationMessage,
                    'type' => 'new_document'
                ]);
            }
        }

        return redirect()->route('documents.index')->with('success', 'Dokumen berhasil diupload!');
    }

    public function show(Document $document)
    {
        return view('documents.show', compact('document'));
    }

    public function download(Document $document)
    {
        if (!Storage::disk('public')->exists($document->file_path)) {
            abort(404);
        }

        return Storage::disk('public')->download($document->file_path, $document->filename);
    }

    public function destroy(Document $document)
    {
        // Only admin can delete documents
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        // Delete file from storage
        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return redirect()->route('documents.index')->with('success', 'Dokumen berhasil dihapus!');
    }
}