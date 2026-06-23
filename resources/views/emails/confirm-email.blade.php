@extends('emails.layout')

@section('content')
<h2 style="margin-bottom: 20px;">Confirm your email address</h2>
<p>Hi there,</p>
<p>Thanks for registering for <strong>Branch</strong>! To complete your account setup and get started, please confirm your email address by clicking the button below.</p>
<table width="100%" cellspacing="0" cellpadding="0">
   <tr>
      <td align="center" style="padding: 25px 0;">
         <a href="{{ $url }}" style="background-color: #007bff; color: #ffffff; padding: 12px 25px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block;">Confirm Email Address</a>
      </td>
   </tr>
</table>
<p style="font-size: 14px; color: #4b5563;">
   If you did not create an account or request this verification, you can safely ignore this email.
</p>
@endsection
