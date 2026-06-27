<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>DAI Admin Notification</title>
</head>
<body style="margin:0;padding:0;background:#f3f4f6;font-family:'Helvetica Neue',Arial,sans-serif">
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f3f4f6;padding:28px 0">
  <tr><td align="center">
    <table width="600" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;width:100%">

      {{-- Header --}}
      <tr>
        <td style="background:#132d5e;border-radius:10px 10px 0 0;padding:18px 28px">
          <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
              <td>
                <span style="font-size:18px;font-weight:900;color:#facc15;font-family:'Helvetica Neue',Arial,sans-serif">DAI</span>
                <span style="font-size:12px;font-weight:600;color:rgba(255,255,255,0.6);margin-left:8px;text-transform:uppercase;letter-spacing:0.5px">Admin Notification</span>
              </td>
              <td align="right">
                <span style="font-size:11px;color:rgba(255,255,255,0.45)">{{ now()->format('d M Y, H:i') }}</span>
              </td>
            </tr>
          </table>
        </td>
      </tr>

      {{-- Type badge --}}
      @if(!empty($type))
      <tr><td style="background:#1e3f7a;padding:8px 28px">
        <span style="font-size:11px;font-weight:700;color:#facc15;text-transform:uppercase;letter-spacing:0.8px">{{ $type }}</span>
      </td></tr>
      @endif

      {{-- Body --}}
      <tr>
        <td style="background:#ffffff;padding:28px;border-left:1px solid #e5e7eb;border-right:1px solid #e5e7eb">
          {!! $body !!}
        </td>
      </tr>

      {{-- Footer --}}
      <tr>
        <td style="background:#f9fafb;border:1px solid #e5e7eb;border-top:none;border-radius:0 0 10px 10px;padding:14px 28px">
          <p style="margin:0;font-size:11px;color:#9ca3af">
            Driver Assessments Ireland — Admin Panel Notification
            &nbsp;·&nbsp;
            <a href="{{ url('/admin') }}" style="color:#132d5e;text-decoration:none">View Admin Panel →</a>
          </p>
        </td>
      </tr>

    </table>
  </td></tr>
</table>
</body>
</html>
