@component('mail::message')
<h1>Bem vindo {{$user->name}}</h1>

<h2>
Bem vindo sua conta foi criada com sucesso!
</h2>
<h2>
Sua conta foi criada por nossa equipe, e precisamos te passar alguns dados de acesso
</h2>
<p>
Para acessar o seu painel de {{$user->office}} é necessário fazer o login   com os seguintes dados
</p>

<ul>
<li>
<p>
e-mail  que você informou para nossa equipe
</p>
</li>
<li>
<p>
 sua senha é seu cpf que você informou para nossa equipe
</p>
</li>
<li>
<p>
ATENÇÃO  é altamente recomendado que  você troque sua senha por uma de segurança  maior, para sua segurança nos monitoramos os logins dos produtores e toda fez que notar algo de estranho, iremos bloquear o acesso a plataforma, e enviar um e-mail de notificação para  saber se realmente foi você ou não
</p>
</li>
<li>
<p>
DICA: Não compartilhe sua conta com ninguém, se tem um parceiro que deseja ter acesso junto com você, converse conosco para um plano diferenciado. Lembre-se que você é o único responsável por manter seus dados de acesso seguros.
</p>
</li>
</ul>
<h2>
Atenção ao confirmar este e-mail você concorda com todos os termos de uso da Xmartt,
</h2>
@component('mail::button',['url' => $user->link])
Confirmar e-mail
@endcomponent


<p>
Gostaria de ler os termos uso ?
@component('mail::button',['url' => 'http://localhost:8080/beta'])
Termos de Uso
@endcomponent


@endcomponent
