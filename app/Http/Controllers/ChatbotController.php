<?php

namespace App\Http\Controllers;

use App\Models\ChatbotResponse;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    public function respond(Request $request)
    {
        $request->validate(['message' => 'required|string|max:500']);

        $userMessage = strtolower(trim($request->message));
        $response    = $this->findResponse($userMessage);

        return response()->json([
            'success'   => true,
            'message'   => $response,
            'timestamp' => now()->format('H:i'),
        ]);
    }

    private function findResponse(string $message): string
    {
        $clean = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $message);
        $clean = preg_replace('/\s+/', ' ', trim($clean));

        $words = array_filter(explode(' ', $clean), fn($w) => mb_strlen($w) >= 2);

        $responses = ChatbotResponse::where('is_active', true)
            ->where('category', '!=', 'default')
            ->orderBy('priority', 'desc')
            ->get();

        $bestMatch      = null;
        $bestScore      = 0;

        foreach ($responses as $resp) {
            $keywords = array_map('strtolower', $resp->keywords ?? []);
            if (empty($keywords)) continue;

            $score = 0;

            foreach ($keywords as $keyword) {
                if (str_contains($clean, $keyword)) {
                    $score += mb_strlen($keyword) >= 4 ? 3 : 2; 
                    continue;
                }

                foreach ($words as $word) {
                    if ($word === $keyword) {
                        $score += 2; 
                    } elseif (str_contains($keyword, $word) && mb_strlen($word) >= 3) {
                        $score += 1; 
                    }
                }
            }

            $weightedScore = $score + ($resp->priority * 0.1);

            if ($weightedScore > $bestScore) {
                $bestScore = $weightedScore;
                $bestMatch = $resp;
            }
        }

        if ($bestMatch && $bestScore >= 3) {
            return nl2br(e($bestMatch->response));
        }

        $default = ChatbotResponse::where('category', 'default')
            ->where('is_active', true)
            ->first();

        return $default
            ? nl2br(e($default->response))
            : 'Maaf, saya tidak memahami pertanyaan Anda. Silakan hubungi admin kami. ';
    }
}
