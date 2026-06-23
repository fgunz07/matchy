@extends('emails.layout')

@section('content')
<h2 style="margin-bottom: 20px;">Reset your password</h2>
<p>Hi there,</p>
<p>We received a request to reset the password for your account. If you didn't make this request, you can safely ignore this email.</p>
<p>To set a new password, click the button below:</p>
<table width="100%" cellspacing="0" cellpadding="0">
   <tr>
      <td align="center" style="padding: 25px 0;">
         <a href="{{ $url }}" style="background-color: #007bff; color: #ffffff; padding: 12px 25px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block;">Reset Password</a>
      </td>
   </tr>
</table>
<p style="font-size: 14px; color: #4b5563;">
   For your security, this password reset link will expire in 60 minutes.
</p>
@endsection
