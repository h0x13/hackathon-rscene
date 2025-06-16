<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Email Verification</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      line-height: 1.6;
      color: #333;
      margin: 0;
      padding: 0;
      background-color: #f4f4f4;
    }
    .container {
      max-width: 600px;
      margin: 20px auto;
      padding: 20px;
      background-color: #ffffff;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .header {
      text-align: center;
      padding: 20px 0;
      background: linear-gradient(135deg, #6e8efb, #a777e3);
      border-radius: 8px 8px 0 0;
      margin: -20px -20px 20px -20px;
    }
    .header h1 {
      color: #ffffff;
      margin: 0;
      font-size: 24px;
    }
    .content {
      padding: 20px;
    }
    .verification-code {
      background-color: #f8f9fa;
      padding: 15px;
      border-radius: 6px;
      text-align: center;
      margin: 20px 0;
      font-size: 24px;
      font-weight: bold;
      color: #2c3e50;
      letter-spacing: 2px;
    }
    .message {
      margin-bottom: 20px;
      color: #2c3e50;
    }
    .footer {
      text-align: center;
      padding-top: 20px;
      border-top: 1px solid #eee;
      color: #666;
      font-size: 12px;
    }
    .button {
      display: inline-block;
      padding: 12px 24px;
      background: linear-gradient(135deg, #6e8efb, #a777e3);
      color: #ffffff;
      text-decoration: none;
      border-radius: 6px;
      margin: 20px 0;
    }
    .warning {
      background-color: #fff3cd;
      color: #856404;
      padding: 10px;
      border-radius: 4px;
      margin: 20px 0;
      font-size: 14px;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h1><?= $is_password_reset ? 'Password Reset' : 'Email Verification' ?></h1>
    </div>
    <div class="content">
      <div class="message">
        <?php if ($is_password_reset): ?>
          <p>Hello,</p>
          <p>We received a request to reset your password. To proceed with the password reset, please use the verification code below:</p>
        <?php else: ?>
          <p>Hello,</p>
          <p>Thank you for registering! To complete your registration, please use the verification code below:</p>
        <?php endif; ?>
      </div>

      <div class="verification-code">
        <?= $verification_code ?>
      </div>

      <div class="warning">
        <?php if ($is_password_reset): ?>
          This verification code will expire in 10 minutes. If you did not request a password reset, please ignore this email.
        <?php else: ?>
          This verification code will expire in 10 minutes. Please verify your email address to complete your registration.
        <?php endif; ?>
      </div>

      <div class="message">
        <?php if ($is_password_reset): ?>
          <p>If you need any assistance, please contact our support team.</p>
        <?php else: ?>
          <p>After verifying your email, you'll be able to access all features of your account.</p>
        <?php endif; ?>
      </div>
    </div>
    <div class="footer">
      <p>This is an automated message, please do not reply to this email.</p>
      <p>&copy; <?= date('Y') ?> Your Company Name. All rights reserved.</p>
    </div>
  </div>
</body>
</html>
