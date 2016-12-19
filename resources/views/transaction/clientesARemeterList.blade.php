
@extends('layout')

@section('title')
	Reposição de Estoques - Remessas
@stop

@section('body')

@foreach ($clientes as $cliente)
{!! Form::open([action('TransactionController@storeRemessa',['cliente_id'=>$cliente->id])])!!}
<div class="panel-group">
    <div class="panel">
		<div class="panel-heading">
			<div class="panel-title">
	          {{$loop->iteration}}. <a data-toggle="collapse" href="#collapse{{$loop->iteration}}">{{$cliente->nomefantasia}} ({{$cliente->razaosocial}})</a> <a class="btn btn-info" href="{{action('ClienteController@showFollowUpPanel',['cliente_id'=>$cliente->id])}}">Detalhes</a>  
	          <a class="btn btn-warning" href="{{action('TransactionController@createRemessa',['cliente_id'=>$cliente->id])}}">Ver Remessas</a>    
	         </div>
        </div>
        <div id="collapse{{$loop->iteration}}" class="panel-collapse">
			<table class="table table-bordered">
			  <thead>
			    <tr>
					<th align="center">Produto</th>
					<th align="center">Estoque Atual</th>
					<th align="center">Última Remessa</th>
					<th align="center">Qtd A ENVIAR</th>
			    </tr>
			  </thead>
			  <tbody>
			@foreach ($cliente->stocks() as $stock)
			@php $info = $cliente->getDadosUltimaReposicao($stock['product_id']); @endphp
			  <tr>
			      <td>{{$stock['nomeproduto']}}</td>
			      <td 
			      @if($stock['estoqueatual']<4)
			      	class="alert alert-danger" 
			      @elseif($stock['estoqueatual']<6)
			      	class="alert alert-warning" 
			      @endif
			      align=center>{{$stock['estoqueatual']}}</td>
			      <td align="center">
			      @if (isset($info['quantidadeultima']))
			      	<a class="btn btn-info" href="https://www.bling.com.br/vendas.php#edit/{{$info['codPedidoExterno']}}"> {{$info['quantidadeultima']}}  
			      	({{$info['dataultima']}})</a>
			      	@endif
			      </td>
			      <td align=center> 
			      	{!! Form::text('products_qty[]', "", ['class' => 'form-control','maxlength'=>3, 'placeholder'=>'Sugerido: '.$info['quantidadeultima']] ) !!}
						<input type="hidden" name="products_id[]" value="{{$stock['product_id']}}">
						<input type="hidden" name="cliente_id" value="{{$cliente->id}}">
			      </td> 
			      </td>
			    </tr>
			@endforeach
			    <tr>
			    	<td colspan="3"></td>
				    <td colspan="">
				    {!! Form::submit('Criar Remessa',['class' => 'form-control btn btn-danger', 'onclick'=> 'return confirm(\'Tem certeza que deseja criar a remessa?\')'] )!!}
				    </td>
			    </tr>

			  </tbody>
			</table>
		</div>
	</div>
</div>
{!! Form::close() !!}
@endforeach
@stop