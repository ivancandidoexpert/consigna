
@extends('layout')

@section('title')
	Follow Ups!
@stop

@section('body')

<ul class=list-group>
@foreach($clientes_ids as $cliente_id)
	<li class=list-group-item>
		{{$loop->iteration}}. <a href="{{action('ClienteController@showFollowUpPanel',['cliente_id'=>$cliente_id['id']])}}">{{$cliente_id['nomefantasia']}}</a>

		
	</li>
@endforeach
</ul>

@stop