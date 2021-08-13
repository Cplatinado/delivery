@component('mail::message')

<p>
Para trocar senha basta clicar no link a baixo
</p>

@component('mail::button',['url' => $user->link])
alterar senha
@endcomponent


<p>
    DICA: Não compartilhe sua conta com ninguém, se tem um parceiro que deseja ter acesso junto com você, converse conosco para um plano diferenciado. Lembre-se que você é o único responsável por manter seus dados de acesso seguros.
</p>


@endcomponent
