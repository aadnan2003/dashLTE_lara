<x-mail::message>
# UCAS-104 System

Welcome {{$name}} in UCAS 104 System

<x-mail::panel>
Your email is: {{$email}}
</x-mail::panel>

<x-mail::button :url="'http://127.0.0.1:8000/cms/admin/login'">
Admin Panel
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
