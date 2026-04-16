<x-mail::message>
# This month on the stack

Here is your **{{ now()->translatedFormat('F Y') }}** newsletter from {{ config('app.name') }}.

A few links and notes worth your time — APIs, frontend patterns, databases, and tooling.

---

**Tip:** Bookmark the [blog]({{ config('app.url') }}/blog) for the full archive between issues.

<x-mail::button :url="$unsubscribeUrl">
Unsubscribe from this list
</x-mail::button>

Thanks for reading,<br>
{{ config('app.name') }}
</x-mail::message>
