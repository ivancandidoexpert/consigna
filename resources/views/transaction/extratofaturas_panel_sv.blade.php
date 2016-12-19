

<h3 class=page-header> Faturas Geradas</h3>

@php
	$transactions = $cliente->faturas()->get();
@endphp
@foreach ($transactions as $transaction)
<div class="panel-group">
    <div class="panel panel-default">
		<div class="panel-heading">
			<div class="panel-title">
	          <a data-toggle="collapse" href="#faturapn{{$loop->iteration}}">Data: {{$transaction->created_at}} - ID: {{$transaction->tipoTransacao}}{{$transaction->id}} <a class="btn btn-info" href="https://www.bling.com.br/vendas.php#edit/{{$transaction->codPedidoExterno}}">Bling! ({{$transaction->codPedidoExterno}})</a> </a> 
	         </div>
        </div>
        <div id="faturapn{{$loop->iteration}}" class="panel-collapse collapse">
			<table class="table table-bordered">
			  <thead>
			    <tr>
					<th>Produto</th>
					<th>Quantidade</th>
					<th>{!! Form::open(['action' => 'TransactionController@delete']) !!}
			<input type="hidden" name="transaction_id" value="{{$transaction->id}}">
			<input type="submit" value="Excluir" class="btn btn-danger" href="{{action('TransactionController@delete',['transaction_id'=>$transaction->id])}}" onclick="return confirm('Tem certeza que deseja excluir a transação?')">	{!! Form::close() !!} </th>
			    </tr>
			  </thead>
			  <tbody>
			@foreach ($transaction->products()->get() as $product)
			    <tr>
			      <td>{{$product->nome}}</td>
			      <td align=center>{{$product->pivot->quantidade}}</td>
			      <td>
			       
			    </tr>
			@endforeach
			  </tbody>
			</table>
		</div>
	</div>
</div>
@endforeach