<x-mail::message>
# Hello

Vous recevez cet e-mail car nous avons reçu une demande de réinitialisation du mot de passe pour votre compte.
<x-mail::button :url="'http://localhost:3000/' . $token ">
réinitialiser le mot de passe</x-mail::button>

Thanks
</x-mail::message>
