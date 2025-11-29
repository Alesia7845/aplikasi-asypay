<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class ChatAIController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        $client = new Client();

        try {
            $response = $client->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent', [
                'query' => [
                    'key' => env('GEMINI_API_KEY')
                ],
                'json' => [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $request->message]
                            ]
                        ]
                    ]
                ]
            ]);

            $data = json_decode($response->getBody(), true);

            $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Tidak ada jawaban';

            return response()->json(['reply' => $reply]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function view()
    {
        return view('chat');
    }
}
