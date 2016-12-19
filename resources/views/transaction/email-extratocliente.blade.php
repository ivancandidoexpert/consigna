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
Segue o extrato consolidado de todas as transações da nossa parceria para sua conferência.<BR> <BR>
Aqui estão relacionados todos os produtos recebidos em consignação por você e os faturados até o momento.<br><br>
Por favor, confira atentamente caso tenha qualquer dúvida ou reclamação, nos envie seus questionamentos respondendo neste mesmo e-mail ou encaminhando para (atendimento@ivancandidoexpert.com.br).<BR><BR>

<B>ATENÇÃO:</B> Você deverá receber no seu e-mail e no seu endereço o BOLETO BANCÁRIO referente ao último faturamento nos próximos dias, com vencimento para o dia 15.<bR>
<BR>
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
	@php $total_faturas = 0; @endphp
	@foreach ($cliente->remessas()->get() as $transaction)
		@php $itp=0; @endphp
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
	@endforeach
	  </tbody>
	  <tfoot>
	   	<tr>
	   		<td colspan=3 align=right><h5>Total de Itens Enviados:</h5></td>
	   		<td>{{$total_faturas}}</td>
	   	</tr>
		</tfoot>
  </table>
<h3 class="page-header">Faturas Emitidas</h3>
<p>Relação de todos os produtos vendidos faturados.</p>
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
	@php $total_faturas = 0; @endphp
	@foreach ($cliente->faturas()->get() as $transaction)
		@php $itp=0; @endphp
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
	@endforeach
	  </tbody>
	  <tfoot>
	   	<tr>
	   		<td colspan=3 align=right><h5>Total de Itens Faturados:</h5></td>
	   		<td>{{$total_faturas}}</td>
	   	</tr>
		</tfoot>
  </table>
 </div>

    </body>
</html>