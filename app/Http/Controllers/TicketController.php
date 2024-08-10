<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ticket;
use App\Models\Comment;
use App\Helpers\DateHelper;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\StatusHistory;
use App\Models\TemplateComment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TicketController extends Controller
{
    // Tikets
    public function index() {
        $tickets = Ticket::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->paginate(10);

        $tickets = $tickets->map(function ($ticket) {
            $ticket->created_at_indo = $this->formatDateInIndonesian($ticket->created_at);
            return $ticket;
        });

        return view('tiket.index', compact('tickets'));
    }

    // Create Tiket
    public function create() {
        $employees = Employee::whereHas('user', function ($query) {
            $query->whereIn('role', ['admin', 'agent']);
        })
        ->get();

        return view('tiket.form', compact("employees"));
    }

    // Store Ticket
    public function store(Request $request) {
        // Validasi data
        $validator = Validator::make($request->all(), [
            'subject' => 'required|string|max:50',
            // 'name' => 'required|string|max:50',
            'type' => 'required|string',
            'team' => 'required|string',
            'priority' => 'required|string',
            'description' => 'nullable|string',
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Menyimpan lampiran
        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $attachmentName = time() . '-' . $file->getClientOriginalName();
                $file->storeAs('public/images/attachment', $attachmentName);

                $attachments[] = $attachmentName;
            }
        }

        $user = Auth::user();
        $validatedData = $validator->validated();

        // Data Tambahan
        $lastTicket = Ticket::orderBy('id', 'desc')->first();
        $lastTicketId = $lastTicket ? $lastTicket->id : 0;
        $validatedData['user_id'] = $user->id;
        $validatedData['attachments'] = json_encode($attachments);
        $validatedData['no_ticket'] = "TK" . date('Ymd') . str_pad($user->id, 4, '0', STR_PAD_LEFT) . $lastTicketId + 1;

        $ticket = Ticket::create($validatedData);

        StatusHistory::create([
            'ticket_id' => $ticket->id,
            'text'      => "Tiket berhasil dibuat oleh " . $user->name
        ]);

        return redirect()->route('tickets')->with('success', 'Tiket baru berhasil dibuat!');
    }

    // Show Detail Ticket
    public function show($id) {
        $ticket = Ticket::findOrFail($id);
        $attachments = json_decode($ticket->attachments, true);

        $comments = Comment::where('ticket_id', $id)->with('user')->get();

        // Mengambil semua riwayat status untuk tiket ini
        $statusHistories = StatusHistory::where('ticket_id', $id)->orderBy('created_at', 'asc')->get();
        $statusHistory = $statusHistories->map(function ($statusHistory) {
            $statusHistory->created_at_indo = $this->formatDateInIndonesian($statusHistory->created_at);
            return $statusHistory;
        });

        $user = User::findOrFail($ticket->user_id);
        $employee = Employee::where('name', $ticket->team)->first();
        $employeeEmail = User::where('id', $employee->user_id)->first();
        $templates = TemplateComment::all();

        $imageAuthor = User::where('id', $ticket->user_id)->first();

        // Menggunakan nama variabel yang konsisten di tampilan
        return view('tiket.ticket', [
            'statusHistory' => $statusHistory,
            'user' => $user,
            'employee' => $employee,
            'employeeEmail' => $employeeEmail,
            'ticket' => $ticket,
            'attachments' => $attachments,
            'comments' => $comments,
            'templates' => $templates,
            'imageAuthor' => $imageAuthor
        ]);
    }

    // Update Ticket Status
    public function updateTicketStatus(Request $request, $id) {
        // Validasi data
        $validator = Validator::make($request->all(), [
            'status' => 'nullable|string',
            // 'priority' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validatedData = $validator->validated();

        $ticket = Ticket::find($id);

        $ticket->update(array_filter([
            'status' => $validatedData['status'] ?? $ticket->status,
            // 'priority' => $validatedData['priority'] ?? $ticket->priority,
            'updated_at' => now()
        ]));

        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->first();

        // Catat perubahan status
        StatusHistory::create([
            'ticket_id' => $ticket->id,
            'text' => "Status tiket diubah oleh " . $employee->name . " menjadi " . $validatedData['status']
        ]);

        return redirect()->back()->with('success', 'Data berhasil diperbarui!');
    }

    // Delete Ticket
    public function destroy($id) {

        // Find the ticket by ID
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();

        return redirect()->route('tickets')->with('success', "Data berhasil dihapus.");
    }

    // Pickup Ticket
    public function pickupTicket($id) {
        $ticket = Ticket::where('id', $id)->first();
        // dd($ticket);

        $ticket = Ticket::where('id', $id)->first();
        $update['status'] = "Processed";
        $ticket->update($update);

        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->first();

        StatusHistory::create([
            'ticket_id' => $ticket->id,
            'text'      => "Status tiket diubah oleh " . $employee->name . " menjadi " . $update['status']
        ]);

        return redirect()->route('dashboard')->with('success_pickup', 'Berhasil pickup ticket ' . $ticket->subject . '!');
    }

    // Explore Ticket
    public function exploreIndex(Request $request) {
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->first();
        $query = Ticket::query();

        // Menambahkan filter berdasarkan parameter dari request
        if ($request->filled('subject')) {
            $query->where('subject', 'like', '%' . $request->input('subject') . '%');
        }

        if ($request->filled('no_ticket')) {
            $noTicket = $request->input('no_ticket');
            $cleanedNoTicket = preg_replace('/\D/', '', $noTicket);

            $query->where('no_ticket', 'like', '%' . $cleanedNoTicket . '%');
        }

        if ($request->filled('team')) {
            $query->where('team', 'like', '%' . $request->input('team') . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($user->role !== 'admin') {
            $query->where('team', $employee->name);
        }

        $tickets = $query->paginate(10);

        foreach ($tickets as $ticket) {
            $ticket->user = User::findOrFail($ticket->user_id);
            $ticket->employee = Employee::where('user_id', $ticket->user->id)->first();
        }

        $employees = Employee::whereHas('user', function ($query) {
            $query->whereIn('role', ['admin', 'agent']);
        })
        ->where('is_active', true)
        ->get();

        return view('tiket.eksplore', compact('tickets', 'employees'));
    }

    // Update Status Explore Ticket
    public function updateTicket(Request $request) {
        // Validasi input
        $validated = $request->validate([
            'id' => 'required|numeric|exists:tickets,id',
            'priority' => 'sometimes|in:LOW,MEDIUM,HIGH',
            'status' => 'sometimes|in:1,2,3,4,5'
        ]);

        $ticket = Ticket::find($validated['id']);
        $originalStatus = $ticket->status; // Simpan status yang lama

        // Update prioritas tiket jika ada
        if (isset($validated['priority'])) {
            $ticket->priority = $validated['priority'];
        }

        if (isset($validated['status'])) {
            $statusMap = [
                '1' => 'Open',
                '2' => 'Pending',
                '3' => 'Resolved',
                '4' => 'Waiting',
                '5' => 'Processed'
            ];
            $newStatus = $statusMap[$validated['status']];
            $ticket->status = $newStatus;

            $user = Auth::user();
            $employee = Employee::where('user_id', $user->id)->first();

            StatusHistory::create([
                'ticket_id' => $ticket->id,
                'text'      => "Status tiket diubah oleh " . $employee->name . " menjadi " . $newStatus
            ]);
        }

        $ticket->save();

        return redirect()->route('explore-ticket-index');
    }

    // Filter custom date
    private function formatDateInIndonesian($date) {
        return DateHelper::dayNameInIndonesian($date) . ', ' . $date->format('d-M-Y');
    }
}
