<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Jobs\ClassifyTicketJob; // Assuming you have a job for AI classification

class TicketController extends Controller
{
    /**
     * Display a listing of the tickets with filtering, searching, and pagination.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Ticket::query();

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->has('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('subject', 'like', "%{$searchTerm}%")
                  ->orWhere('body', 'like', "%{$searchTerm}%");
            });
        }
        
        $tickets = $query->orderBy('id', 'desc')->paginate(10);

        return response()->json($tickets);
    }

    /**
     * Store a newly created ticket in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $ticket = new Ticket();
        $ticket->id = (string) Str::ulid();
        $ticket->fill($validatedData);
        $ticket->status = 'open';
        $ticket->save();

        return response()->json([
            'message' => 'Ticket created successfully!',
            'ticket' => $ticket
        ], 201);
    }

    /**
     * Display the specified ticket.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $id)
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return response()->json(['message' => 'Ticket not found.'], 404);
        }

        return response()->json(['ticket' => $ticket]);
    }

    /**
     * Update a ticket's status, category, and notes.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, string $id)
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return response()->json(['message' => 'Ticket not found.'], 404);
        }

        $validatedData = $request->validate([
            'status' => 'nullable|in:open,in_progress,resolved,closed',
            'category' => 'nullable|string|max:255',
            'note' => 'nullable|string',
        ]);

        $ticket->update($validatedData);

        return response()->json([
            'message' => 'Ticket updated successfully!',
            'ticket' => $ticket
        ]);
    }

    /**
     * Remove the specified ticket from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $id)
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return response()->json(['message' => 'Ticket not found.'], 404);
        }

        $ticket->delete();

        return response()->json(['message' => 'Ticket deleted successfully!']);
    }

    /**
     * Dispatches a queued AI classification job for a ticket.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function classify(string $id)
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return response()->json(['message' => 'Ticket not found.'], 404);
        }

        $enabled = env('OPENAI_CLASSIFY_ENABLED', false);
        // dd($enabled);
        if (!$enabled) {
            $categories = ['Bug', 'Feature Request', 'General Enquiry', 'Support'];
            $randomCategory = $categories[array_rand($categories)];
            $ticket->category = $randomCategory;
            $ticket->explanation = "This is a dummy explanation for {$randomCategory}.";
            $ticket->confidence = rand(50, 100) / 100; // random confidence between 0.5 and 1.0
            $ticket->save();

            \Log::info('Dummy classification applied.', ['ticket_id' => $ticket->id]);
            return;
        }else{
            ClassifyTicketJob::dispatch($ticket);
        }

        return response()->json([
            'message' => 'AI classification job has been dispatched.',
            'ticket_id' => $ticket->id,
        ]);
    }

    /**
     * Provides statistics for dashboard charts.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function stats()
    {
        $stats = [
            'total_tickets' => Ticket::count(),
            'open_tickets' => Ticket::where('status', 'open')->count(),
            'in_progress_tickets' => Ticket::where('status', 'in_progress')->count(),
            'resolved_tickets' => Ticket::where('status', 'resolved')->count(),
            'closed_tickets' => Ticket::where('status', 'closed')->count(),
            'tickets_by_category' => Ticket::select('category', \DB::raw('count(*) as count'))
                ->groupBy('category')
                ->get(),
        ];
        
        return response()->json($stats);
    }
}