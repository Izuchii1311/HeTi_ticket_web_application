<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard(Request $request) {
        if (Auth::check()) {
            $user = Auth::user();
            $employee = Employee::where('user_id', $user->id)->first();

            // Function to get ticket counts by status and month
            $getTicketCountByMonthAndStatus = function($status, $team = null, $user_id = null) {
                $data = [];
                for ($month = 1; $month <= 12; $month++) {
                    $query = Ticket::where('status', $status)
                                    ->whereMonth('created_at', $month)
                                    ->whereYear('created_at', now()->year);

                    if ($team) {
                        $query->where('team', $team);
                    }

                    if ($user_id) {
                        $query->where('user_id', $user_id);
                    }

                    $data[] = $query->count();
                }
                return $data;
            };

            if ($user->role == 'admin' || $user->role == 'agent') {
                $team = $employee->name;
                $openTickets = $getTicketCountByMonthAndStatus('Open', $team);
                $waitingTickets = $getTicketCountByMonthAndStatus('Waiting', $team);
                $processedTickets = $getTicketCountByMonthAndStatus('Processed', $team);
                $pendingTickets = $getTicketCountByMonthAndStatus('Pending', $team);
                $resolvedTickets = $getTicketCountByMonthAndStatus('Resolved', $team);
                $latestTicket = Ticket::where('status', 'Open')->where('team', $team)->orderBy('created_at', 'desc')->first();
            } else {
                $user_id = $user->id;
                $openTickets = $getTicketCountByMonthAndStatus('Open', null, $user_id);
                $waitingTickets = $getTicketCountByMonthAndStatus('Waiting', null, $user_id);
                $processedTickets = $getTicketCountByMonthAndStatus('Processed', null, $user_id);
                $pendingTickets = $getTicketCountByMonthAndStatus('Pending', null, $user_id);
                $resolvedTickets = $getTicketCountByMonthAndStatus('Resolved', null, $user_id);
                $latestTicket = Ticket::where('status', 'Open')->where('user_id', $user_id)->orderBy('created_at', 'desc')->first();
            }

            $openTicket = array_sum($openTickets);
            $waitingTicket = array_sum($waitingTickets);
            $processedTicket = array_sum($processedTickets);
            $pendingTicket = array_sum($pendingTickets);
            $resolvedTicket = array_sum($resolvedTickets);

            // Get overall data
            $getOverallTicketCountByMonthAndStatus = function($status) {
                $data = [];
                for ($month = 1; $month <= 12; $month++) {
                    $data[] = Ticket::where('status', $status)
                                    ->whereMonth('created_at', $month)
                                    ->whereYear('created_at', now()->year)
                                    ->count();
                }
                return $data;
            };

            $overallOpenTickets = $getOverallTicketCountByMonthAndStatus('Open');
            $overallWaitingTickets = $getOverallTicketCountByMonthAndStatus('Waiting');
            $overallProcessedTickets = $getOverallTicketCountByMonthAndStatus('Processed');
            $overallPendingTickets = $getOverallTicketCountByMonthAndStatus('Pending');
            $overallResolvedTickets = $getOverallTicketCountByMonthAndStatus('Resolved');

            // Query for recent tickets with search filter if provided
            $query = Ticket::where('status', '!=', 'Open');

            if ($search = $request->input('search')) {
                $query->where('no_ticket', 'like', '%' . $search . '%');
            }

            $recentTickets = $query->where('team', $employee->name)->orderBy('updated_at', 'desc')->paginate(25);

            $countAllOpenTicket = Ticket::where('status', 'Open')->count();
            $countAllPendingTicket = Ticket::where('status', 'Pending')->count();
            $countAllResolvedTicket = Ticket::where('status', 'Resolved')->count();
            $countAllWaitingTicket = Ticket::where('status', 'Waiting')->count();
            $countAllProcessedTicket = Ticket::where('status', 'Processed')->count();

            return view('dashboard.index', compact(
                'user',
                'employee',
                'openTickets',
                'pendingTickets',
                'waitingTickets',
                'processedTickets',
                'resolvedTickets',
                'openTicket',
                'waitingTicket',
                'processedTicket',
                'pendingTicket',
                'resolvedTicket',
                'latestTicket',
                'recentTickets',
                'overallOpenTickets',
                'overallPendingTickets',
                'overallWaitingTickets',
                'overallProcessedTickets',
                'overallResolvedTickets',
                'countAllOpenTicket',
                'countAllPendingTicket',
                'countAllResolvedTicket',
                'countAllWaitingTicket',
                'countAllProcessedTicket'
            ));
        } else {
            return redirect()->route('login')->withErrors('You must be logged in to view this page.');
        }
    }
}
