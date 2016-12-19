
@extends('layout')

@section('title')
Lançar Transação
@stop

@section('body')
@if(isset($status))
	@if( $status=='mostrapainel')
		<div class="alert alert-success"> 
			Nome: {{$transaction->cliente()->first()->nomefantasia}}<Br>
	    	Transação:  {{$transaction->tipoTransacao}}<br>
	    	<BR>Itens:
	    	@foreach ($transaction->products()->get() as $item)
			<br>{{$item->pivot->quantidade}} (un) > {{$item->nome}}
			@endforeach

			{!! Form::open(['action' => 'TransactionController@delete']) !!}
			<input type="hidden" name="transaction_id" value="{{$transaction->id}}">
			{!! Form::submit('Cancelar Registro'); !!}

			{!! Form::close() !!}
		</div>
	@endif
		<div>
			<h3>Últimos Registros</h3>
			<ul class=list-group>
			@foreach ($transactions as $transaction)
				<li class=list-group-item>
				{{$transaction->created_at}} | Cliente: {{$transaction->cliente()->first()->nomefantasia}} | Transação:  {{$transaction->tipoTransacao}}
				@foreach ($transaction->products()->get() as $item)
					<br>{{$item->pivot->quantidade}} (un) > {{$item->nome}}
				@endforeach
				</li>
			@endforeach
			</ul>
		</div>
	</div>
@else
<div class="page-header"></div>
	<div class="form-group">
		{!! Form::open(['action' => 'TransactionController@store']) !!}
    	{!! Form::Label('cliente_id', 'Cliente:') !!}
    	{!! Form::select("cliente_id", $clientes, null, ['class' => 'form-control']) !!}
    	<bR>
    	{!! Form::Label('tipoTransacao', 'Tipo de Transação:') !!}
    	{!! Form::select("tipoTransacao", $tiposTransacao, null, ['class' => 'form-control']) !!}
    	<BR>{!! Form::Label('item', 'Itens:') !!}<BR>
    	<div class="row">
    	@foreach ($produtos as $produto)
    		<div class="col-md-1, col-sm-2"> <img src="{{ $produto['urlImagem'] }}" height="100px"  /></div>
    		<div class="col-md-11, col-sm-10">{{$produto['nome']}} {!! Form::text('products_qty[]', "", ['class' => 'form-control','maxlength'=>2]) !!}</div>
    		<input type="hidden" name="products_id[]" value="{{$produto['id']}}">
    	@endforeach
    	
    	</div>
		<BR><BR>{!! Form::submit('Registrar'); !!}
		{!! Form::close() !!}
	</div>
</div>
@endif

@stop