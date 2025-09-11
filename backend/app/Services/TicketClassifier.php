<?php

namespace App\Services;

use App\Models\Ticket;
use OpenAI\Client as OpenAIClient;
use Illuminate\Support\Facades\Log;
use OpenAI\Exceptions\RateLimitExceededException;

class TicketClassifier
{
    /**
     * The OpenAI client instance.
     *
     * @var \OpenAI\Client
     */
    protected $openai;

    /**
     * Create a new service instance.
     *
     * @param  \OpenAI\Client  $openai
     * @return void
     */
    public function __construct(OpenAIClient $openai)
    {
        $this->openai = $openai;
    }

    /**
     * Classify a given ticket using the OpenAI API.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return void
     */
    public function classify(Ticket $ticket)
    {
        Log::info('Starting classification for ticket ID: ' . $ticket->id);
        
        $systemMessage = "You are an expert ticket classifier for a customer support system. Your task is to analyze the subject and body of a ticket and classify it into one of the following categories: 'Billing', 'Technical', 'General Inquiry', or 'Feature Request'. You must also provide a brief explanation for your classification and a confidence score between 0.0 and 1.0. The response must be a valid JSON object.";

        $userMessage = "Ticket Subject: " . $ticket->subject . "\nTicket Body: " . $ticket->body;

        try {
            $response = $this->openai->chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'system', 'content' => $systemMessage],
                    ['role' => 'user', 'content' => $userMessage],
                ],
                'response_format' => ['type' => 'json_object'],
            ]);
    
            $content = $response->choices[0]->message->content;
            $data = json_decode($content, true);

            if ($data === null) {
                Log::error('Invalid JSON received from OpenAI.', ['response' => $content, 'ticket_id' => $ticket->id]);
                return;
            }
    
            Log::info('Successfully received classification from OpenAI.', ['data' => $data, 'ticket_id' => $ticket->id]);
    
            $ticket->category = $data['category'] ?? 'Unclassified';
            $ticket->explanation = $data['explanation'] ?? null;
            $ticket->confidence = $data['confidence'] ?? $data['confidence_score'] ?? null;
            $ticket->save();
    
            Log::info('Ticket classified and updated successfully.', ['ticket_id' => $ticket->id]);
    
        } catch (\Exception $e) {
            throw $e;
        }
    }
}