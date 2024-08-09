<?php
namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function sendReply(Request $request) {
        // Validasi input
        $validated = $request->validate([
            'ticket_id' => 'required|numeric',
            'text' => 'required|string|max:1000',
            'attachments.*' => 'nullable|mimes:jpeg,png,jpg,pdf|max:20480',
        ]);

        // Simpan balasan ke database
        $reply = new Comment();
        $reply->message = $validated['text'];
        $reply->user_id = auth()->id();
        $reply->ticket_id = $validated['ticket_id'];

        // Simpan file jika ada
        if ($request->hasFile('attachments')) {
            $attachments = $request->file('attachments');
            $attachmentPaths = [];

            foreach ($attachments as $attachment) {
                // Cek apakah file ada
                if ($attachment->isValid()) {
                    // Simpan file ke direktori public
                    $path = $attachment->store('comments/attachments', 'public');
                    $attachmentPaths[] = $path;
                }
            }

            // Simpan path file dalam format JSON
            $reply->attachments = json_encode($attachmentPaths);
        }

        $reply->save();

        return redirect()->back();
    }
}
