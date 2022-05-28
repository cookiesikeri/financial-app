@component('mail::layout')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => config('app.url')])
{{ config('app.name') }}
@endcomponent
@endslot

{{-- Body --}}
{!! $slot !!}

{{-- Subcopy --}}
@isset($subcopy)
@slot('subcopy')
@component('mail::subcopy')
{{ $subcopy }}
@endcomponent
@endslot
@endisset

{{-- Footer --}}
@slot('footer')
@component('mail::footer')
    <div style="">
        Your digital banking password, PIN, OTP, debit card number or debit card PIN are personal details and should not be revealed to anyone. TranSave will never ask you to disclose any of these details via phone, mail, website or any other means. Please disregard any requests for your private data and report any suspicious activities to <span style="color: #3869d4">support@transave.com.ng</span> We are always available to answer your questions.
    </div>
@endcomponent
@endslot
@endcomponent
