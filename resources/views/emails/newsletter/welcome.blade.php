<x-mail::message>
# Thanks for subscribing

You are on the list for **{{ config('app.name') }}** — a monthly note on full-stack craft (APIs, frontend, databases, and shipping calmly in production).

We will only email you when there is something worth your inbox. You can leave any time using the unsubscribe link in each message.

@if($subscriber->unsubscribe_token)
<x-mail::button :url="route('newsletter.unsubscribe', ['token' => $subscriber->unsubscribe_token])">
Unsubscribe
</x-mail::button>
@endif

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
