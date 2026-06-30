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
        if (!$enabled) {
            \Log::info('Recaptcha DEBUG: skipped — disabled', ['action' => $expectedAction]);
            return true;
        }

        $secretKey = Setting::get('recaptcha_secret_key', '');
        if (empty($secretKey)) {
            \Log::info('Recaptcha DEBUG: skipped — no secret key configured', ['action' => $expectedAction]);
            return true;
        }

        if (empty($token)) {
            \Log::info('Recaptcha DEBUG: result', [
                'action'    => $expectedAction,
                'score'     => null,
                'threshold' => (float)Setting::get('recaptcha_threshold', '0.5'),
                'result'    => 'rejected',
                'reason'    => 'missing token',
            ]);
            return false;
        }

        try {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret'   => $secretKey,
                'response' => $token,
            ]);
            $result = $response->json();
        } catch (\Exception $e) {
            \Log::error('Recaptcha verify request failed', ['error' => $e->getMessage()]);
            \Log::info('Recaptcha DEBUG: result', [
                'action'    => $expectedAction,
                'score'     => null,
                'threshold' => (float)Setting::get('recaptcha_threshold', '0.5'),
                'result'    => 'rejected',
                'reason'    => 'request exception',
            ]);
            return false;
        }

        $threshold = (float)Setting::get('recaptcha_threshold', '0.5');
        $score     = isset($result['score']) ? (float)$result['score'] : null;

        if (empty($result['success'])) {
            \Log::warning('Recaptcha verification failed', ['result' => $result]);
            \Log::info('Recaptcha DEBUG: result', [
                'action'    => $expectedAction,
                'score'     => $score,
                'threshold' => $threshold,
                'result'    => 'rejected',
                'reason'    => 'google success=false',
                'errors'    => $result['error-codes'] ?? null,
            ]);
            return false;
        }

        if (($result['action'] ?? null) !== $expectedAction) {
            \Log::warning('Recaptcha action mismatch', ['expected' => $expectedAction, 'got' => $result['action'] ?? null]);
            \Log::info('Recaptcha DEBUG: result', [
                'action'         => $expectedAction,
                'returned_action'=> $result['action'] ?? null,
                'score'          => $score,
                'threshold'      => $threshold,
                'result'         => 'rejected',
                'reason'         => 'action mismatch',
            ]);
            return false;
        }

        if ($score < $threshold) {
            \Log::warning('Recaptcha score below threshold', ['score' => $score, 'threshold' => $threshold]);
            \Log::info('Recaptcha DEBUG: result', [
                'action'    => $expectedAction,
                'score'     => $score,
                'threshold' => $threshold,
                'result'    => 'rejected',
                'reason'    => 'score below threshold',
            ]);
            return false;
        }

        \Log::info('Recaptcha DEBUG: result', [
            'action'    => $expectedAction,
            'score'     => $score,
            'threshold' => $threshold,
            'result'    => 'passed',
        ]);
        return true;
    }
}
