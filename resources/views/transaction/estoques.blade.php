
@extends('layout')

@section('title')
	Relatório de Estoques
@stop

@section('body')
<?php $iterator=0;?>
@foreach ($clientes as $cliente)
<?php 
	$stocks = $cliente->stocks();
	$iterator++;
	if (count($stocks) > 0): ?>
<div class="panel-group">
    <div class="panel">
		<div class="panel-heading">
			<div class="panel-title">
	          <a data-toggle="collapse" href="#collapse{{$iterator}}">{{$cliente->nomefantasia}} ({{$cliente->razaosocial}})</a> <a class="btn btn-info" href="{{action('ClienteController@showFollowUpPanel',['cliente_id'=>$cliente->id])}}">Ver</a>   
	         </div>
        </div>
        <div id="collapse{{$iterator}}" class="panel-collapse">
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
			      <td 
			      @if($row['estoqueatual']<4)
			      	class="alert alert-danger" 
			      @elseif($row['estoqueatual']<6)
			      	class="alert alert-warning" 
			      @endif
			      align=center>{{$row['estoqueatual']}}</td>
			      <td align=center>{{$row['afaturar']}}</td> 
			      <td align=center>{{$row['dataafericao']}}</td> 
			      {{--<td align=center>{{$row['ultimaqtdfaturada']}}</td>
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


<?php endif; ?>
@endforeach
@stop