<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Ivan Candido Expert - Extrato de Conferência de Consignados</title>
        <meta http-equiv="cache-control" content="private, max-age=0, no-cache">
        <meta http-equiv="pragma" content="no-cache">
        <meta http-equiv="expires" content="0">

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        
        @yield('header')
    </head>
    <body>

<div class="container">

<div class="container">
		<img class="img-responsive" src="http://ivancandidoexpert.com.br/wp-content/uploads/Topo.jpg">
</div>
<Br><BR>
<p>Prezados,<Br><BR>
Este e-mail é sobre a remessa em consignação que você recebeu ou está para receber.
Lembrando que enviamos automaticamente para você não perder nenhuma oportunidade de venda! <bR><bR>
Uma nota fiscal no valor total acompanha a remessa, mas não se preocupe, você deverá pagar apenas o que consumir ou revender e quando o fizer. <br><br>
As entregas ocorrem pelos Correios ou transportadora, fique atento e nos avise caso não receba nossa caixa em até 10 dias respondendo este e-mail ou encaminhando para (atendimento@ivancandidoexpert.com.br).
Se não houver manifestação contrária, fica considerado recebidos os bens.
<BR><bR>
Segue abaixo a relação dos itens que estão sendo enviados:
<BR><BR>
Atenciosamente,<BR>
Equipe de Atendimento</p>
<br>
	<h2 class="page-header">{{$cliente->nomefantasia}}</h2>
	<h3 class="page-header">Remessas de Estoque</h3>
	<p>Relação dos produtos entregues em consignação</p>
	<table class="table table-bordered table-striped table-condensed">
	  <thead>
	    <tr>
	    	<th align="center">ID</th>	
			<th align="center">Data</th>
			<th align="center">Produto</th>
			<th align="center">Quantidade</th>
	    </tr>
	  </thead>
	  <tbody>
	@php 
		$total_faturas = 0; $itp=0;
		$transaction = $cliente->remessas()->get()->last();
	@endphp
		@foreach ($transaction->products()->get() as $product)
			@php $total_faturas+=$product->pivot->quantidade; $itp++;  @endphp
			<tr>
		  	@if ($itp==1) 
		      <td rowspan="{{$loop->count}}">{{$transaction->id}}</td>
		      <td rowspan="{{$loop->count}}">{{$transaction->created_at}}</td>
		      <td>{{$product->nome}}</td>
		      <td>{{$product->pivot->quantidade}}</td>
		    @else
		      <td>{{$product->nome}}</td>
		      <td>{{$product->pivot->quantidade}}</td>
		    @endif
		    </tr>
	    @endforeach
	  </tbody>
	  <tfoot>
	   	<tr>
	   		<td colspan=3 align=right><h5>Total de Itens Enviados:</h5></td>
	   		<td>{{$total_faturas}}</td>
	   	</tr>
		</tfoot>
  </table>
 </div>

    </body>
</html>