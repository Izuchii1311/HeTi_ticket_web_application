<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TemplateComment;

class TemplateCommentController extends Controller
{
    public function index() {
        $templates = TemplateComment::all();
        return view('templates.template_comment.index', compact('templates'));
    }

    public function store(Request $request) {
        // Validate request
        $data = $request->only(['tag', 'description']);
    
        // If an ID is provided, update the existing record; otherwise, create a new one
        TemplateComment::updateOrCreate(
            ['tag' => $data['tag']], // Use 'tag' to find the record
            ['description' => $data['description']] // Fields to update or create
        );
    
        // Return response
        return response()->json(['message' => 'Comment created or updated successfully!']);
    }
    

    public function destroy($id) {
        $template = TemplateComment::findOrFail($id);
        $template->delete();

        return response()->json(['message' => 'Template deleted successfully.']);
    }
}
