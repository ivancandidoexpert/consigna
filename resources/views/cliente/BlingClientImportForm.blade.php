
@extends('layout')

@section('title')
Importar Clientes do Bling
@stop

@section('body')

<!-- FORMULÁRIO PARA BUSCA DO CLIENTE NO BLING -->
<div class=page-header>
	<div class="form-inline">
		<div class="form-group">
			{!! Form::open(['action' => 'ClienteController@show']) !!}
			Digite o CPF ou CNPJ: {!! Form::text('cpf_cnpj','',array('class' => 'form-control')) !!}
			{!! Form::submit('Buscar no Bing!'); !!}
			{!! Form::close() !!}
		</div>
	</div>
</div>

<!--- se  da consulta e se o usuário é novo, passível de cadastro -->
@if (isset($response))
	<DIV class="row">
		<div class="col-md-6">
			<?php $area = json_decode($response['response'], true); ?>
			<BR><label for="nome">Nome:</label> {{$area['retorno']['contatos'][0]['contato']['nome']}}
			<BR><label>Nome Fantasia:</label> {{$area['retorno']['contatos'][0]['contato']['fantasia']}}
			<BR><label>CNPJ/CPF:</label> {{$area['retorno']['contatos'][0]['contato']['cnpj']}}  ({{$area['retorno']['contatos'][0]['contato']['tipo']}})
			<BR><label>Bairro:</label> {{$area['retorno']['contatos'][0]['contato']['bairro']}}
			<BR><label>Cidade:</label> {{$area['retorno']['contatos'][0]['contato']['cidade']}}
			<BR><label>UF:</label> {{$area['retorno']['contatos'][0]['contato']['uf']}}
			<BR><label>Fone:</label> {{$area['retorno']['contatos'][0]['contato']['fone']}}
			<BR><label>e-mail:</label> {{$area['retorno']['contatos'][0]['contato']['email']}}
			<BR><label>Nome Vendedor:</label> {{$area['retorno']['contatos'][0]['contato']['nomeVendedor']}}
			@if (!isset($area['retorno']['contatos'][0]['contato']['nomeVendedor']))
				<span class="alert alert-warning"> Impossível importar sem um vendedor associado. Indique um vendedor no Bling.</h4>
				</div>
			@else
			</div>
			<div class="col-md-6">
				@if (!isset($cliente)) 
					<h4> Importar Novo </h4>
					<div class="form-inline">
					<div class="form-group">
						{!! Form::open(['action' => 'ClienteController@create']) !!}
						<input type="hidden" name="cnpj" value="{{$area['retorno']['contatos'][0]['contato']['cnpj']}}">
						<input type="hidden" name="nomefantasia" value=
						@if ($area['retorno']['contatos'][0]['contato']['fantasia'] !== '')
							"{{$area['retorno']['contatos'][0]['contato']['fantasia']}}"
						@else
							"{{$area['retorno']['contatos'][0]['contato']['nome']}}"
						@endif>
						<input type="hidden" name="razaosocial" value="{{$area['retorno']['contatos'][0]['contato']['nome']}}">
						<input type="hidden" name="bairro" value="{{$area['retorno']['contatos'][0]['contato']['bairro']}}">
						<input type="hidden" name="cidade" value="{{$area['retorno']['contatos'][0]['contato']['cidade']}}">
						<input type="hidden" name="uf" value="{{$area['retorno']['contatos'][0]['contato']['uf']}}">
						<input type="hidden" name="telefone" value="{{$area['retorno']['contatos'][0]['contato']['fone']}}">
						<input type="hidden" name="email" value="{{$area['retorno']['contatos'][0]['contato']['email']}}">
						<input type="hidden" name="nomeVendedor" value="{{$area['retorno']['contatos'][0]['contato']['nomeVendedor']}}">
						<input type="hidden" name="tipoPessoa" value="{{$area['retorno']['contatos'][0]['contato']['tipo']}}">
						<label>WhatsApp:</label> {!! Form::text('whatsapp','',array('class' => 'form-control')) !!} </Br>
						<label>Nome Gerente:</label> {!! Form::text('nomegerente','',array('class' => 'form-control')) !!}</Br>
						{!! Form::submit('Importar','',array('class' => 'form-control')); !!}
						{!! Form::close() !!}
					</div>
					</div>
				@else
					<h4> Atualizar </h4>
					<div class="form-inline">
					<div class="form-group">
						{!! Form::model($cliente, [ 'method'=>'PUT', 'action' => ['ClienteController@update', $cliente->id]]) !!}
						<input type="hidden" name="cnpj" value="{{$area['retorno']['contatos'][0]['contato']['cnpj']}}">
						<input type="hidden" name="nomefantasia" value=
						@if ($area['retorno']['contatos'][0]['contato']['fantasia'] !== '')
							"{{$area['retorno']['contatos'][0]['contato']['fantasia']}}"
						@else
							"{{$area['retorno']['contatos'][0]['contato']['nome']}}"
						@endif>
						<input type="hidden" name="razaosocial" value="{{$area['retorno']['contatos'][0]['contato']['nome']}}">
						<input type="hidden" name="bairro" value="{{$area['retorno']['contatos'][0]['contato']['bairro']}}">
						<input type="hidden" name="cidade" value="{{$area['retorno']['contatos'][0]['contato']['cidade']}}">
						<input type="hidden" name="uf" value="{{$area['retorno']['contatos'][0]['contato']['uf']}}">
						<input type="hidden" name="telefone" value="{{$area['retorno']['contatos'][0]['contato']['fone']}}">
						<input type="hidden" name="email" value="{{$area['retorno']['contatos'][0]['contato']['email']}}">
						<input type="hidden" name="nomeVendedor" value="{{$area['retorno']['contatos'][0]['contato']['nomeVendedor']}}">
						<input type="hidden" name="tipoPessoa" value="{{$area['retorno']['contatos'][0]['contato']['tipo']}}">
						
						<label>WhatsApp:</label> {!! Form::text('whatsapp',null,['class' => 'form-control']) !!} </Br>
						<label>Nome Gerente:</label> {!! Form::text('nomegerente',null,['class' => 'form-control']) !!}</Br>
						{!! Form::submit('Atualizar','',array('class' => 'form-control')); !!}
						{!! Form::close() !!}
					</div>
					</div>
				@endif
			</div>
		@endif
	</div>
@endif
	

@stop