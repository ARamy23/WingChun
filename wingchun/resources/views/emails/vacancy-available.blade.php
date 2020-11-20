@component('mail::message')
# Vacancy Available

{{ $user->name }} Cancelled the session taking place in {{ $readableSessionFromDate }} till {{ $readableSessionToDate }}, you can take his place by pressing
the button below

@component('mail::button', ['url' => 'www.google.com'])
Book his place.
@endcomponent

Stay safe,<br>
{{ config('app.name') }}
@endcomponent
