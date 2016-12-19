
@extends('layout')

@section('title')
Clientes a Faturar
@stop

@section('body')

<ul class=list-group>
@foreach($clientes as $cliente)
	<li class=list-group-item>
		{{$loop->iteration}}. {{$cliente->nomefantasia}}   <a class="btn btn-success" href="{{action('TransactionController@createFatura',['cliente_id'=>$cliente->id])}}">Faturar</a> <a class="btn btn-info" href="{{action('ClienteController@showFollowUpPanel',['cliente_id'=>$cliente->id])}}">Ver</a>
	</li>
@endforeach
</ul>

@stop