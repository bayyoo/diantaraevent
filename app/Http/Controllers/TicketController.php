<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Generate and download e-ticket PDF
     */
    public function download(Participant $participant)
    {
        // Load relationships
        $participant->load(['user', 'event']);
        
        // Check if user owns this participant record
        if (auth()->check() && $participant->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this ticket.');
        }
        
        // Generate PDF
        $pdf = Pdf::loadView('tickets.e-ticket', compact('participant'));
        
        // Set paper size
        $pdf->setPaper('A4', 'portrait');
        
        // Generate filename
        $filename = 'e-ticket_' . str_replace(' ', '_', strtolower($participant->name)) . '_' . time() . '.pdf';
        
        // Return PDF download
        return $pdf->download($filename);
    }
    
    /**
     * View e-ticket in browser
     */
    public function view(Participant $participant)
    {
        // Load relationships
        $participant->load(['user', 'event']);
        
        // Check if user owns this participant record
        if (auth()->check() && $participant->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this ticket.');
        }
        
        // Generate PDF
        $pdf = Pdf::loadView('tickets.e-ticket', compact('participant'));
        
        // Set paper size
        $pdf->setPaper('A4', 'portrait');
        
        // Return PDF stream (view in browser)
        return $pdf->stream('e-ticket.pdf');
    }
    
    /**
     * Generate and save e-ticket path (called after registration)
     */
    public function generateTicketPath(Participant $participant)
    {
        // Load relationships
        $participant->load(['user', 'event']);
        
        // Generate filename
        $filename = 'ticket_' . $participant->id . '_' . time() . '.pdf';
        $path = 'tickets/' . $filename;
        
        // Create directory if not exists
        $fullPath = storage_path('app/public/' . $path);
        $directory = dirname($fullPath);
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }
        
        try {
            // Generate PDF
            $pdf = Pdf::loadView('tickets.e-ticket', compact('participant'));
            $pdf->setPaper('A4', 'portrait');
            
            // Save PDF to storage
            $pdf->save($fullPath);
            
            return $path;
        } catch (\Exception $e) {
            \Log::error('E-Ticket generation failed: ' . $e->getMessage());
            return null;
        }
    }
}
