<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>New Inquiry — MandiSecure</title>
<style>
  body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f6f8; margin: 0; padding: 20px; color: #374151; }
  .wrap { max-width: 600px; margin: 0 auto; }
  .header {
    background: linear-gradient(135deg, #0e3b1f, #1a6b3c);
    border-radius: 12px 12px 0 0;
    padding: 2rem 2rem 1.5rem;
    text-align: center;
  }
  .header h1 { color: #fff; font-size: 1.4rem; margin: 0.5rem 0 0; font-weight: 700; }
  .header p  { color: rgba(255,255,255,0.7); font-size: 0.85rem; margin: 0.25rem 0 0; }
  .logo { display: inline-flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem; }
  .logo-icon {
    width: 38px; height: 38px; background: #e8a020; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; color: #1a1a1a; font-weight: 800;
  }
  .logo-name { font-size: 1.1rem; font-weight: 800; color: #fff; letter-spacing: 0.02em; }
  .body { background: #fff; padding: 2rem; }
  .badge {
    display: inline-block;
    background: #d4f0e0; color: #065f46;
    font-size: 0.78rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.08em; padding: 0.25rem 0.75rem; border-radius: 100px;
    margin-bottom: 1.25rem;
  }
  .field { margin-bottom: 1.25rem; }
  .field-label { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #9ca3af; margin-bottom: 0.35rem; }
  .field-value { font-size: 0.95rem; color: #111827; font-weight: 500; }
  .field-value a { color: #1a6b3c; text-decoration: none; }
  .message-box {
    background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 10px;
    padding: 1.25rem; font-size: 0.93rem; line-height: 1.75; color: #374151;
    white-space: pre-wrap;
  }
  .divider { border: none; border-top: 1px solid #e5e7eb; margin: 1.25rem 0; }
  .footer {
    background: #f9fafb; border-radius: 0 0 12px 12px;
    padding: 1.25rem 2rem; text-align: center;
    border-top: 1px solid #e5e7eb;
    font-size: 0.8rem; color: #9ca3af;
  }
  .footer a { color: #1a6b3c; text-decoration: none; }
</style>
</head>
<body>
<div class="wrap">

  <div class="header">
    <div class="logo">
      <div class="logo-icon">M</div>
      <span class="logo-name">MandiSecure</span>
    </div>
    <h1>New Contact Form Inquiry</h1>
    <p>Submitted on {{ now()->format('d M Y, h:i A') }} IST</p>
  </div>

  <div class="body">
    <div class="badge">📬 New Inquiry</div>

    <div class="field">
      <div class="field-label">Subject</div>
      <div class="field-value" style="font-size:1.05rem;color:#1a6b3c;font-weight:700">{{ $inquirySubject }}</div>
    </div>

    <hr class="divider">

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.25rem">
      <div class="field" style="margin-bottom:0">
        <div class="field-label">Name</div>
        <div class="field-value">{{ $senderName }}</div>
      </div>
      <div class="field" style="margin-bottom:0">
        <div class="field-label">Mobile</div>
        <div class="field-value">{{ $senderMobile ?: '—' }}</div>
      </div>
    </div>

    <div class="field">
      <div class="field-label">Email</div>
      <div class="field-value"><a href="mailto:{{ $senderEmail }}">{{ $senderEmail }}</a></div>
    </div>

    <hr class="divider">

    <div class="field">
      <div class="field-label">Message</div>
      <div class="message-box">{{ $body }}</div>
    </div>

    <hr class="divider">

    <p style="font-size:0.85rem;color:#6b7280;margin:0">
      To reply, click <strong>Reply</strong> in your email client — the reply will go directly to
      <a href="mailto:{{ $senderEmail }}">{{ $senderEmail }}</a>.
    </p>
  </div>

  <div class="footer">
    &copy; {{ date('Y') }} Mandi Secure Private Limited &mdash;
    <a href="https://www.mandisecure.com">www.mandisecure.com</a><br>
    Marasinganahalli Road, Hosakere, Karnataka, India
  </div>

</div>
</body>
</html>
