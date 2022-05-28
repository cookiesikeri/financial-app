@component('mail::message')
Dear, <b>{{ $name  }}</b>

We wish to inform you that a credit transaction occured  on your account with Us.

The details of this transaction are shown below

 Amount: <b>{{ number_format($amount)  }}</b><br>

<br>

@endcomponent

