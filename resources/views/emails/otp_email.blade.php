@component('mail::message')
Dear {{$name}}

Verify your phone number.
Below is your OTP:

# {{$otp}}

If you did not request this, you can ignore this email or let us know.


Thanks,<br>
{{ config('app.name') }}
@endcomponent
