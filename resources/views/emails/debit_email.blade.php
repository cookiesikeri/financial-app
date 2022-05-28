@component('mail::message')
Dear, <b>{{ $name  }}</b>

A debit of N{{ number_format($amount)  }} has occured on your account.

Details:

Account Number: <b>******{{ substr($account->account_number,-4) }}</b><br>

Amount: <b>{{ number_format($amount)  }}</b><br>

{{--    Transaction ID: <b>{{ $transaction->reference }}</b><br>--}}

Date of transaction: <b>{{ date('d-m-Y',strtotime($transaction->created_at))}}</b>
    {{--Time of transaction:--}}

The balances on the account as at <b>{{ $transaction->created_at->format('H:i') }}</b> is
Current Balance: <b>{{ number_format($total->balance) }}</b>

<br>
@endcomponent
