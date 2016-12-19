
@extends('layout')

@section('title')
	Extrato de Transações
@stop

@section('body')

$transaction = new Transaction();
<<div = "container">
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
<p>Relação de todos os produtos vendidos cobrados.</p>
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

<a class="btn btn-info" href="{{action('ClienteController@sendExtratoEmail',['cliente_id'=>$cliente->id])}}">Enviar Extrato por E-mail</a>	


@stop