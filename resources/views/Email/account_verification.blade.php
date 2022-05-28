<h3 style="background:rgb(30, 30, 141); color:white; width:295px; height:42px; text-align:center;font-size: 22px; padding-top: 5px; margin-left:300px ">{{ config('app.name', 'Laravel') }}</h3>
<h1>Hi {{$data['name']}},</h1>
<h2> Kindly find below your access token to complete your account registeration</h2>
<p style="font-size: 20px" ><b style="color:rgb(35, 35, 99)">Account Verification OTP:</b>{{$data['code']}}</p>
<p style="font-size: 17px">
    If you did not sign up on our app, please ignore this email.
  </p>
  <p style="font-size: 17px">
    Thank you,
  </p>
  <p style="font-size: 17px">
    AFTJ Church Team
  </p>
<p>
    &copy; {{ config('app.name', 'Laravel') }} {{date('Y')}}
</p>
