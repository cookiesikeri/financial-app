@component('mail::message')
Dear, <b>{{ $name  }}</b>

We wish to inform you that a credit transaction occurred  on your account with Us.

The details of this transaction are shown below

 Account Number: <b>******{{ substr($transaction->sender_account_number,-4) }}</b><br>

 Amount: <b>{{ number_format($amount)  }}</b><br>

 Reference: <b>{{ $transaction->reference }}</b><br>

 Date of transaction: <b>{{ date('d-m-Y',strtotime($transaction->created_at))}}</b>
{{--Time of transaction:--}}

The balances on the account as at <b>{{ $transaction->created_at->format('H:i') }}</b> is
Current Balance: <b>{{ number_format($total->balance) }}</b>

<br>

@endcomponent
