@component('mail::message')
    Dear Admin,<br/><br/>
    {{$message['content']}}<br/>
    Below are some details about me:
    Name: {{$user->name}}<br/>
    Email: {{$user->email}}<br/>
    Phone: {{$user->phone}}<br/><br/>
    Please, respond to the request as soon as possible by login into your dashboard your dashboard

    @component('mail::button', ['url' => url('/login')])
        Login To Transave
    @endcomponent

    Regards,<br>
    {{ $kyc->first_name.' '.$kyc->last_name }}
@endcomponent
