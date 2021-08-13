@component('mail::message')
<h1>Bem vindo {{$user->name}}</h1>

<h2>
Bem vindo sua conta foi criada com sucesso!
</h2>

<p>
Para continuar o  seu cadatro clique no botão a baixo para confirmar o seu e-mail
</p>

<h2>
Atenção ao confirmar este e-mail você concorda com todos os termos de uso da Xmartt,
</h2>
@component('mail::button',['url' => $user->link])
Confirmar e-mail
@endcomponent

<ul>
<li>
<p>
DICA: Não compartilhe sua conta com ninguém, se tem um parceiro que deseja ter acesso junto com você, converse conosco para um plano diferenciado. Lembre-se que você é o único responsável por manter seus dados de acesso seguros.
</p>
</li>
</ul>



<p>
Gostaria de ler os termos uso ?
@component('mail::button',['url' => env('LOCALHOST').'termos-de-uso'])
Termos de Uso
@endcomponent


@endcomponent
