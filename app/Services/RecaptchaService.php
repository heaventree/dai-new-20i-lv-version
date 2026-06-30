<?php
namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;

class RecaptchaService
{
    /**
     * Verifies a v3 token against Google's siteverify endpoint and checks the score.
     * Returns true if disabled, unconfigured, or the score passes the threshold.
     */
    public function verify(?string $token, string $expectedAction): bool
    {
        $enabled = Setting::get('recaptcha_enabled', '0') === '1';
        if (!$enabled) return true;

        $secretKey = Setting::get('recaptcha_secret_key', '');
        if (empty($secretKey)) return true;

        if (empty($token)) return false;

        try {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret'   => $secretKey,
                'response' => $token,
            ]);
            $result = $response->json();
        } catch (\Exception $e) {
            \Log::error('Recaptcha verify request failed', ['error' => $e->getMessage()]);
            return false;
        }

        if (empty($result['success'])) {
            \Log::warning('Recaptcha verification failed', ['result' => $result]);
            return false;
        }

        if (($result['action'] ?? null) !== $expectedAction) {
            \Log::warning('Recaptcha action mismatch', ['expected' => $expectedAction, 'got' => $result['action'] ?? null]);
            return false;
        }

        $threshold = (float)Setting::get('recaptcha_threshold', '0.5');
        $score     = (float)($result['score'] ?? 0);

        if ($score < $threshold) {
            \Log::warning('Recaptcha score below threshold', ['score' => $score, 'threshold' => $threshold]);
            return false;
        }

        return true;
    }
}
