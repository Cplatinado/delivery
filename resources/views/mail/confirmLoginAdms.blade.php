@component('mail::message')
<h1>É importante {{$user->name}}</h1>

<p>
    Um acesso a sua conta na Xmartt foi efetuado a partir de uma rede de IP ou dispositivo diferente do habitual. Confira se foi você:
</p>

<ul>
    <li class="display-inline">
       <p> <b>IP:</b> {{$user->ip}}</p>
    </li>
    <li class="display-inline">
        <p> <b>DATA:</b> {{$user->date}}</p>
    </li>
</ul>

<p>
    Por segurança bloquemos a sua conta temporariamente  por segurança para poder  fazer o login novamente clique no botão abaixo
</p>
@component('mail::button',['url' => $user->link])
Desbloquear login
@endcomponent
<h2>
    NÃO FOI VOCÊ? É importante atualizar sua conta o quanto antes para manter seus dados seguros. Para isso ACESSE SUA CONTA e altere sua senha.
</h2>
@component('mail::button',['url' => 'http://localhost:8080/beta'])
    alterar senha
@endcomponent

<p>
    FOI VOCÊ? Então pode ignorar este e-mail, mas ele sempre será enviado como medida de segurança para que você tenha certeza que está tudo certo com sua conta. Como você sabe, nela existem dados sensíveis (pessoais, materiais e financeiros).
</p>
    <p>
        DICA: Não compartilhe sua conta com ninguém, se tem um parceiro que deseja ter acesso junto com você, converse conosco para um plano diferenciado. Lembre-se que você é o único responsável por manter seus dados de acesso seguros.
    </p>


@endcomponent
