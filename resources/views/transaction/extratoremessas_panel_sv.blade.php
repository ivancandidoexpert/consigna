

<h3 class=page-header> Remessas Geradas</h3>

@php
	$transactions = $cliente->remessas()->get();
@endphp
@foreach ($transactions as $transaction)
<div class="panel-group">
    <div class="panel panel-default">
		<div class="panel-heading">
			<div class="panel-title">
	          <a data-toggle="collapse" href="#remessapn{{$loop->iteration}}">Data: {{$transaction->created_at}}  - ID: {{$transaction->tipoTransacao}}{{$transaction->id}} <a class="btn btn-info" href="https://www.bling.com.br/vendas.php#edit/{{$transaction->codPedidoExterno}}">Bling! ({{$transaction->codPedidoExterno}})</a> 
	         </div>
        </div>
        <div id="remessapn{{$loop->iteration}}" class="panel-collapse collapse">
			<table class="table table-bordered">
			  <thead>
			    <tr>
					<th>Produto</th>
					<th>Quantidade</th>
					<th></th>
			    </tr>
			  </thead>
			  <tbody>
			@foreach ($transaction->products()->get() as $product)
			    <tr>
			      <td>{{$product->nome}}</td>
			      <td align=center>{{$product->pivot->quantidade}}</td>
			      <td>
			      {!! Form::open(['action' => 'TransactionController@delete']) !!}
			<input type="hidden" name="transaction_id" value="{{$transaction->id}}">
			<input type="submit" value="Excluir" class="btn btn-danger" href="{{action('TransactionController@delete',['transaction_id'=>$transaction->id])}}" onclick="return confirm('Tem certeza que deseja excluir a transação?')">	{!! Form::close() !!}  
			    </tr>
			@endforeach
			  </tbody>
			</table>
		</div>
	</div>
</div>
@endforeach