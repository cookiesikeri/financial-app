@component('mail::message')

Dear Admin,<br/><br/>
I wish to apply for premium account. Below is a brief description of some of my details:<br/>
Name: {{$user->name}}<br/>
Email: {{$user->email}}<br/>
Phone: {{$user->phone}}<br/>
Address: {{$kyc->address}}<br/>
Guarantor: {{$kyc->guarantor}}<br/>
Guarantor Contact: {{$kyc->guarantor_contact}}<br/>

Please, respond to the request as soon as possible by login into your dashboard your dashboard

@component('mail::button', ['url' => url('/login')])
Login To Transave
@endcomponent

Regards,<br>
{{ $kyc->first_name.' '.$kyc->last_name }}
@endcomponent
