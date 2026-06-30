{{--
    Usage: @include('partials.recaptcha', ['formId' => 'hcp-referral-form', 'action' => 'hcp_referral'])
    Place anywhere inside the target <form id="{{ $formId }}">.
    Generates a v3 token on submit and injects it into a hidden 'recaptcha_token' field
    before the form actually posts. No-ops entirely if reCAPTCHA is disabled or unconfigured.
--}}
@php
    $rcEnabled = \App\Models\Setting::get('recaptcha_enabled', '0') === '1';
    $rcSiteKey = \App\Models\Setting::get('recaptcha_site_key', '');
@endphp
@if($rcEnabled && $rcSiteKey)
<input type="hidden" name="recaptcha_token" id="recaptcha_token_{{ $formId }}">
@push('scripts')
<script>
(function() {
    if (!window.__recaptchaScriptLoaded) {
        window.__recaptchaScriptLoaded = true;
        var s = document.createElement('script');
        s.src = 'https://www.google.com/recaptcha/api.js?render={{ $rcSiteKey }}';
        document.head.appendChild(s);
    }
    var form = document.getElementById('{{ $formId }}');
    if (!form) return;
    var submitting = false;
    form.addEventListener('submit', function(e) {
        if (submitting) return;
        e.preventDefault();
        function execute() {
            grecaptcha.ready(function() {
                grecaptcha.execute('{{ $rcSiteKey }}', {action: '{{ $action }}'}).then(function(token) {
                    document.getElementById('recaptcha_token_{{ $formId }}').value = token;
                    submitting = true;
                    form.submit();
                });
            });
        }
        if (window.grecaptcha) {
            execute();
        } else {
            var tries = 0;
            var check = setInterval(function() {
                tries++;
                if (window.grecaptcha) { clearInterval(check); execute(); }
                else if (tries > 50) { clearInterval(check); submitting = true; form.submit(); }
            }, 100);
        }
    });
})();
</script>
@endpush
@endif
