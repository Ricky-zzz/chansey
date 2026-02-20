<?php

namespace App\Http\Controllers\HeadNurse;

use App\Http\Controllers\Controller;
use App\Models\Memo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MemoController extends Controller
{
    public function index()
    {
        $memos = Memo::with(['targetRoles', 'targetStations'])
            ->where('created_by_user_id', Auth::id())
            ->latest()
            ->paginate(15);

        return view('nurse.headnurse.memos.index', [
            'title' => 'My Memos',
            'memos' => $memos,
        ]);
    }

    public function create()
    {
        $headNurse = Auth::user()->nurse;

        return view('nurse.headnurse.memos.create', [
            'title' => 'Create New Memo',
            'headNurse' => $headNurse,
        ]);
    }

    public function store(Request $request)
    {
        $headNurse = Auth::user()->nurse;

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'attachment' => 'nullable|file|max:5120|mimes:pdf,jpg,jpeg,png,doc,docx',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('memos/attachments', 'public');
        }

        $memo = Memo::create([
            'created_by_user_id' => Auth::id(),
            'title' => $validated['title'],
            'content' => $validated['content'],
            'attachment_path' => $attachmentPath,
        ]);

        // Head nurse targets Staff in their station only
        $memo->syncTargetRoles(['Staff']);
        $memo->targetStations()->sync([$headNurse->station_id]);

        return redirect()->route('nurse.headnurse.memos.index')
            ->with('success', 'Memo created successfully!');
    }

    public function show(Memo $memo)
    {
        // Verify ownership
        if ($memo->created_by_user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        return view('nurse.headnurse.memos.view', [
            'title' => 'View Memo',
            'memo' => $memo,
        ]);
    }

    public function edit(Memo $memo)
    {
        // Verify ownership
        if ($memo->created_by_user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        $headNurse = Auth::user()->nurse;

        return view('nurse.headnurse.memos.edit', [
            'title' => 'Edit Memo',
            'memo' => $memo,
            'headNurse' => $headNurse,
        ]);
    }

    public function update(Request $request, Memo $memo)
    {
        // Verify ownership
        if ($memo->created_by_user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'attachment' => 'nullable|file|max:5120|mimes:pdf,jpg,jpeg,png,doc,docx',
        ]);

        $attachmentPath = $memo->attachment_path;

        if ($request->hasFile('attachment')) {
            // Delete old attachment if exists
            if ($attachmentPath) {
                Storage::disk('public')->delete($attachmentPath);
            }
            $attachmentPath = $request->file('attachment')->store('memos/attachments', 'public');
        }

        $memo->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'attachment_path' => $attachmentPath,
        ]);

        return redirect()->route('nurse.headnurse.memos.index')
            ->with('success', 'Memo updated successfully!');
    }

    public function destroy(Memo $memo)
    {
        // Verify ownership
        if ($memo->created_by_user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        // Delete attachment if exists
        if ($memo->attachment_path) {
            Storage::disk('public')->delete($memo->attachment_path);
        }

        $memo->delete();

        return redirect()->route('nurse.headnurse.memos.index')
            ->with('success', 'Memo deleted successfully!');
    }
}
