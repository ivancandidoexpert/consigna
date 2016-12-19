
@extends('layout')

@section('title')
	Faturamento de Unidades Vendidas
@stop

@section('body')

{{-- BLOCO DE DADOS DO CLIENTE --}}
<div class="row">
	<div class="col-sm-8">

	  <div class="panel-group">
	    <div class="panel panel-default">
	      <div class="panel-heading">
	        <div class="panel-title">
	          <h3><a data-toggle="collapse" href="#collapse1">{{$cliente->nomefantasia}}</a></h3>
	          <h5>({{$cliente->razaosocial}})</h5>
	        </div>
	      </div>
	      <div id="collapse1" class="panel-collapse collapse">
	        <div class="panel-body">
	        	<p> Vendedor: {{$cliente->nomeVendedor}}  |  CNPJ:{{$cliente->cnpj}}</p>
				<p>{{$cliente->uf}} -  {{$cliente->cidade}} - {{$cliente->bairro}}</p>
	        </div>
	        <div class="panel-footer"><a class="btn btn-warning" href="{{action('TransactionController@createRemessa',['cliente_id'=>$cliente->id])}}">Remessas</a>  <a class="btn btn-info" href="{{action('ClienteController@showFollowUpPanel',['cliente_id'=>$cliente->id])}}">Ver</a></div>
	      </div>
	    </div>
    </div>
    </div>
    <div class=col-sm-4>

	  <div class="panel-group">
	    <div class="panel panel-default">
	      <div class="panel-heading">
	       	<div class="panel-body">
	       		      	<p>Conversar com {{$cliente->nomegerente}}</p><a href="tel:{{$cliente->telefone}}">{{$cliente->telefone}}</a> | <a href="tel:{{$cliente->whatsapp}}">{{$cliente->whatsapp}}</a> | <a href="mailto:{{$cliente->email}}">{{$cliente->email}}</a>
	       	</div>
	      </div>
	      </div>
	    </div>

    </div>
 </div>


{{-- BLOCO DE FORMULÁRIO DE CRIAÇÃO DE FATURA --}}
<div class="panel-group">
    <div class="panel panel-default">
		<div class="panel-heading">
			<div class="panel-title">
	          <span class="h3 alert-danger"><a data-toggle="collapse" href="#collapse2">Criar Fatura</a></span> 
	         </div>
        </div>
        <div id="collapse2" class="panel-collapse">
			<div class="form-group">
			
			{!! Form::open([action('TransactionController@storeRemessa',['cliente_id'=>$cliente->id])])!!}
			
			<input type="hidden" name="tipoTransacao" value="F">
			<input type="hidden" name="cliente_id" value="{{$cliente->id}}">

			<table class="table table-striped">
			  <thead>
			    <tr>
					<th align=center>Item:</th><th align=center>Estoque Aferido</th><th align=center>Na Data</th><th align=center>Enviar Qtd.</th>
			    </tr>
			  </thead>
			  <tbody>

			<?php foreach ($cliente->stocks() as $row): array_map('htmlentities', $row); ?>
				<?php  if($product=App\Product::find($row['product_id'])) {?>
			    <tr>
			      <td>{{$product->nome}}</td>
			      <td align=center>{{$row['estoqueatual']}}</td>
			      <td >{{$row['dataafericao']}}</td> 
			      <td> {!! Form::text('products_qty[]', "", ['class' => 'form-control','maxlength'=>3,'placeholder'=>'Sugerido: '.$row['afaturar']]) !!}
						<input type="hidden" name="products_id[]" value="{{$row['product_id']}}">
						<input type="hidden" name="cliente_id" value="{{$cliente->id}}">
			      </td>

			    </tr>
			    <?php }; ?>
			<?php endforeach; ?>
			  </tbody>
			</table>

				{!! Form::submit('Faturar Vendas Realizadas',['class' => 'form-control btn btn-danger', 'onclick'=> 'return confirm(\'Tem certeza que deseja faturar para o cliente?\')'] )!!}
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>


{{-- BLOCO DE EXTRATO DE REMESSAS  --}}
@include('transaction.extratofaturas_panel_sv', $cliente)

@stop