<div style="margin-left: 50px; margin-right: 50px">
  <p style="font-size: 17px">
    Hi {{ $data['user']['name'] }},
  </p>
  <p style="font-size: 17px">
    You have just requested a new password for your Transave app user account.
  </p>
  <p style="font-size: 17px">
    To confirm this password request, use the token below:
  </p>
  <h2 style="font-size: center">
   <b style="font-size:20px">Password Reset Token : </b> {{ $data['verified_otp'] }}
  </h2>
  <p style="font-size: 17px">
    If the code to resetting your password has expired you will need to request a new password reset. You can use the "Forgot Password" process, to begin the process again
  </p>
  <p style="font-size: 17px">
    If you did not request a password reset, please ignore this email.
  </p>
  <p style="font-size: 17px">
    Thank you,
  </p>
  <p style="font-size: 17px">
    Transave Nigeria
  </p>
</div>
