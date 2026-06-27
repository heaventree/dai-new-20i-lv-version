<?php
namespace App\Services;
use App\Models\EmailLog;
use App\Models\EmailTemplate;
use App\Models\Setting;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;

class EmailService
{
    private string $lastError = '';

    public function getLastError(): string { return $this->lastError; }

    public function sendFromTemplate(string $slug, string $toEmail, string $toName, array $vars = []): bool
    {
        $template = EmailTemplate::where('slug', $slug)->where('is_active', true)->first();
        if (!$template) return false;
        $subject = $this->replace($template->subject, $vars);
        $body    = $this->replace($template->body, $vars);
        return $this->send($toEmail, $toName, $subject, $body, $slug);
    }

    /**
     * Send an HTML email.
     *
     * $template === ''           → admin notification layout (plain-text body auto-formatted)
     * $template === any slug     → branded customer layout (body is HTML from DB template)
     */
    public function send(string $toEmail, string $toName, string $subject, string $body, string $template = ''): bool
    {
        $this->lastError = '';
        $this->applySmtpFromSettings();

        $isAdmin = ($template === '');

        if ($isAdmin && !str_contains($body, '<')) {
            $htmlBody = $this->plainTextToAdminHtml($body);
        } else {
            $htmlBody = $body;
        }

        $type = $isAdmin ? $this->extractNotificationType($subject) : '';
        $layout = $isAdmin ? 'emails.layout-admin' : 'emails.layout-customer';
        $rendered = view($layout, ['body' => $htmlBody, 'type' => $type])->render();

        try {
            Mail::html($rendered, function (Message $msg) use ($toEmail, $toName, $subject) {
                $from = Setting::get('mail_from_address')
                    ?: Setting::get('mail_username')
                    ?: env('MAIL_FROM_ADDRESS', 'info@driverassessmentsireland.ie');
                $msg->to($toEmail, $toName)->subject($subject)
                    ->from($from, 'Driver Assessments Ireland');
            });
            EmailLog::create(['to_email' => $toEmail, 'to_name' => $toName, 'subject' => $subject, 'template' => $template, 'status' => 'sent']);
            return true;
        } catch (\Exception $e) {
            $this->lastError = $e->getMessage();
            EmailLog::create(['to_email' => $toEmail, 'to_name' => $toName, 'subject' => $subject, 'template' => $template, 'status' => 'failed', 'error_message' => $e->getMessage()]);
            return false;
        }
    }

    private function applySmtpFromSettings(): void
    {
        $host     = Setting::get('mail_host');
        $port     = Setting::get('mail_port');
        $username = Setting::get('mail_username');
        $password = Setting::get('mail_password');

        if ($host && $username && $password) {
            $port       = (int)($port ?: 465);
            $encryption = $port === 465 ? 'ssl' : 'tls';
            config([
                'mail.default'                 => 'smtp',
                'mail.mailers.smtp.host'       => $host,
                'mail.mailers.smtp.port'       => $port,
                'mail.mailers.smtp.username'   => $username,
                'mail.mailers.smtp.password'   => $password,
                'mail.mailers.smtp.encryption' => $encryption,
                'mail.mailers.smtp.timeout'    => 30,
            ]);
        }
    }

    /**
     * Convert plain-text admin notifications (lines / key: value pairs) into
     * a styled HTML block for the admin email layout.
     */
    private function plainTextToAdminHtml(string $text): string
    {
        $paragraphs = preg_split('/\n{2,}/', trim($text));
        $html = '';

        foreach ($paragraphs as $para) {
            $lines = array_filter(array_map('trim', explode("\n", $para)));
            if (empty($lines)) continue;

            $hasKeyValue = collect($lines)->filter(fn($l) => str_contains($l, ':'))->count() > 1;

            if ($hasKeyValue) {
                $html .= '<table style="width:100%;border-collapse:collapse;margin:0 0 20px">';
                foreach ($lines as $line) {
                    if (str_contains($line, ':')) {
                        [$key, $val] = explode(':', $line, 2);
                        $html .= '<tr>'
                            . '<td style="padding:7px 12px 7px 0;font-size:13px;font-weight:700;color:#374151;white-space:nowrap;width:160px;vertical-align:top">' . e(trim($key)) . '</td>'
                            . '<td style="padding:7px 0;font-size:13px;color:#111827;border-bottom:1px solid #f3f4f6">' . nl2br(e(trim($val))) . '</td>'
                            . '</tr>';
                    } else {
                        $html .= '<tr><td colspan="2" style="padding:7px 0;font-size:13px;color:#111827">' . e($line) . '</td></tr>';
                    }
                }
                $html .= '</table>';
            } else {
                foreach ($lines as $line) {
                    $html .= '<p style="margin:0 0 12px;font-size:14px;color:#374151;line-height:1.6">' . e($line) . '</p>';
                }
            }
        }

        return $html;
    }

    private function extractNotificationType(string $subject): string
    {
        if (stripos($subject, 'HCP') !== false)        return 'HCP Referral';
        if (stripos($subject, 'Assessment') !== false)  return 'Assessment Submission';
        if (stripos($subject, 'Contact') !== false)     return 'Contact Enquiry';
        if (stripos($subject, 'Payment') !== false)     return 'Payment';
        return 'Admin Notification';
    }

    private function replace(string $text, array $vars): string
    {
        foreach ($vars as $k => $v) {
            $text = str_replace('{{'.$k.'}}', $v, $text);
        }
        return $text;
    }
}
