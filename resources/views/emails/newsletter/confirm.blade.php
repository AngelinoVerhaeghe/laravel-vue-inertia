<x-mail::message>
# One more step

You asked to join the **{{ config('app.name') }}** newsletter. Click the button below to confirm your email address. Until you do, you will not receive issues or other list mail.

<x-mail::button :url="$confirmUrl">
Confirm subscription
</x-mail::button>

If you did not request this, you can ignore this message—no subscription will be created.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
