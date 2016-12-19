
@extends('layout')

@section('title')
	 Clientes
@stop

@section('body')

<ul class=list-group>
@foreach($clientes as $cliente)
	<li class=list-group-item>
		{{$loop->iteration}}. {{link_to_action('ClienteController@showFollowUpPanel', $cliente->nomefantasia." (".$cliente->razaosocial.") ", ['cliente_id'=>$cliente->id]) }}

		<a class="btn btn-success" href="{{action('ClienteController@showFollowUpPanel',['cliente_id'=>$cliente->id])}}">Ver</a>
		<a class="btn btn-warning" href="{{action('TransactionController@createRemessa',['cliente_id'=>$cliente->id])}}">Enviar Remessa</a>   
		<a class="btn btn-danger" href="{{action('TransactionController@createFatura',['cliente_id'=>$cliente->id])}}">Faturar Vendas</a>  
		<a class="btn btn-info" href="{{action('ClienteController@showExtrato',['cliente_id'=>$cliente->id])}}">Extrato</a>	
	</li>
@endforeach
</ul>

@stop