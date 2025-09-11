<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('tickets')->group(function () {
    // GET /api/tickets - lists all tickets with filtering, searching, and pagination
    Route::get('/', [TicketController::class, 'index']);

    // POST /api/tickets - creates a new ticket
    Route::post('/', [TicketController::class, 'store']);

    // GET /api/tickets/{id} - shows a specific ticket's details
    Route::get('/{id}', [TicketController::class, 'show']);

    // PATCH /api/tickets/{id} - updates a specific ticket's status, category, and notes
    Route::patch('/{id}', [TicketController::class, 'update']);
    
    // POST /api/tickets/{id}/classify - dispatches a queued AI classification job
    Route::post('/{id}/classify', [TicketController::class, 'classify']);
    
    // DELETE /api/tickets/{id} - deletes a ticket
    Route::delete('/{id}', [TicketController::class, 'destroy']);
});

// GET /api/stats - provides dashboard statistics
Route::get('/stats', [TicketController::class, 'stats']);