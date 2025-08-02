<x-mail::message>
# Introduction

We kindly ask you to renew your subscription as soon as possible to continue enjoying our services without interruption

<x-mail::button :url="'http://127.0.0.1:8000/all-packages'">
Renew Subscription
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
