
@extends('layout')

@section('title')
	Acompanhamento de Cliente
@stop

@section('body')

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
	      <div id="collapse1" class="panel-collapse">
	        <div class="panel-body">
	        	<p> Vendedor: {{$cliente->nomeVendedor}}  |  CNPJ:{{$cliente->cnpj}}</p>
				<p>{{$cliente->uf}} -  {{$cliente->cidade}} - {{$cliente->bairro}}</p>
	        </div>
	        <div class="panel-footer">
				<a class="btn btn-warning" href="{{action('TransactionController@createRemessa',['cliente_id'=>$cliente->id])}}"> Remessas</a>   
				<a class="btn btn-danger" href="{{action('TransactionController@createFatura',['cliente_id'=>$cliente->id])}}">Faturas</a>  
				<a class="btn btn-info" href="{{action('ClienteController@showExtrato',['cliente_id'=>$cliente->id])}}">Extrato</a>	
		    </div>
	      </div>
	    </div>
    </div>
    </div>
    <div class=col-sm-4>

	  <div class="panel-group">
	    <div class="panel panel-default">
	      <div class="panel-heading">
	       	<div class="panel-body">
	       		      	<p>Falar com {{$cliente->nomegerente}}</p><a href="tel:{{$cliente->telefone}}">{{$cliente->telefone}}</a> | <a href="tel:{{$cliente->whatsapp}}">{{$cliente->whatsapp}}</a> | <a href="mailto:{{$cliente->email}}">{{$cliente->email}}</a>
	       	</div>
	      </div>
	      </div>
	    </div>

    </div>
 </div>

<?php if (count($stocks) > 0): ?>

<div class="panel-group">
    <div class="panel">
		<div class="panel-heading">
			<div class="panel-title">
	          <a data-toggle="collapse" href="#sumarioestoque">Sumário do Estoque (+)</a> 
	         </div>
        </div>
        <div id="sumarioestoque" class="panel-collapse collapse">
			<table class="table table-bordered">
			  <thead>
			    <tr>
					<th>Produto</th>
					<th>Estoque Remetido (total)</th>
					<th>Estoque Faturado (total)</th>
					<th>Estoque Atual</th>
					<th>A Faturar</th>
					<th>Data Atualização</th>
					{{--<th>Qtd Última Fatura</th>
					<th>Qtd Última Remessa</th>--}}
			    </tr>
			  </thead>
			  <tbody>
			@foreach ($stocks as $row)
				@if ($row['product_id'] !== 0)
			    <tr>
			      <td>{{$row['nomeproduto']}}</td>
			      <td align=center>{{$row['estoqueremetido']}}</td>
			      <td align=center>{{$row['estoquefaturado']}}</td>
			      <td align=center>{{$row['estoqueatual']}}</td>
			      <td align=center>{{$row['afaturar']}}</td> 
			      <td align=center>{{$row['dataafericao']}}</td> 
			     {{-- <td align=center>{{$row['ultimaqtdfaturada']}}</td>
			      <td align=center>{{$row['ultimaqtdenviada']}}</td> --}}
			      </td>
			    </tr>
			    @endif
			@endforeach
			  </tbody>
			</table>
		</div>
	</div>
</div>	

<div class="panel-group">
    <div class="panel panel-default">
		<div class="panel-heading">
			<div class="panel-title">
	          <a data-toggle="collapse" href="#collapse2">Atualização de Estoque </a>
	         </div>
        </div>
        <div id="collapse2" class="panel-collapse">
			<div class="form-group">
			{!! Form::open(['action' => 'TransactionController@updateStock']) !!}
			<table class="table table-striped">
			  <thead>
			    <tr>
					<th>Item:</th><th>A Faturar</th><th>Estoque Anterior</th><th>Na Data</th><th>Estoque Atual</th>
			    </tr>
			  </thead>
			  <tbody>
			<?php foreach ($stocks as $row): array_map('htmlentities', $row); ?>
				<?php  if($product=App\Product::find($row['product_id'])) {?>
			    <tr>
			      <td>{{$product->nome}}</td>
			      <td align=center>{{$row['afaturar']}}</td>
			      <td align=center>{{$row['estoqueatual']}}</td>
			      <td align=center>{{$row['dataafericao']}}</td> 
			      <td> {!! Form::text('products_qty[]', "", ['class' => 'form-control','maxlength'=>3]) !!}
						<input type="hidden" name="products_id[]" value="{{$row['product_id']}}">
						<input type="hidden" name="cliente_id" value="{{$cliente->id}}">
			      </td>

			    </tr>

			<?php } endforeach; ?>
			  </tbody>
			</table>
				{!! Form::submit('Atualizar Estoque do PDV',['class' => 'form-control btn btn-primary', 'onclick'=> 'return confirm(\'Tem certeza que deseja atualizar o estoque?\')'] )!!}
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@include('transaction.extratoremessas_panel_sv', $cliente)

@include('transaction.extratofaturas_panel_sv', $cliente)



<?php endif; ?>

@stop