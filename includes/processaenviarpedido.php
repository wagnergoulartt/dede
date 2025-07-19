<?php
ob_start();
session_start();
require('../_app/Config.inc.php');
require('../_app/Mobile_Detect.php');
$detect = new Mobile_Detect;
$site = HOME;
$getu = $_POST['user_id'];

$lerbanco->ExeRead('ws_empresa', "WHERE user_id = :f", "f={$getu}");
if (!$lerbanco->getResult()):
else:
	foreach ($lerbanco->getResult() as $i):
		extract($i);
	endforeach;
endif;


$cart = new Cart([
	//Total de item que pode ser adicionado ao carrinho 0 = Ilimitado
	'cartMaxItem' => 0,

	// A quantidade máxima de um item que pode ser adicionada ao carrinho, 0 = Ilimitado
	'itemMaxQuantity' => 0,

	// Não usar cookies, os itens do carrinho desaparecerão depois que o navegador for fechado
	'useCookie' => false,
]);

$bairrosstatus = 'false';
$pedidos = '';

$allItems = $cart->getItems();
foreach ($allItems as $items) {
	foreach ($items as $item) {
		if(!empty($item['attributes']['totalAdicionais'])):
			$todosOsAdicionais = '';
			$todosOsAdicionaisSoma = 0;
			for($i=0; $i < $item['attributes']['totalAdicionais']; $i++):
				$todosOsAdicionais = $todosOsAdicionais.$item['attributes']['adicional_nome'.$i].', ';
				$todosOsAdicionaisSoma = ($todosOsAdicionaisSoma + $item['attributes']['adicional_valor'.$i]);
			endfor;
		endif;							

		$pedidos = $pedidos.'<b>'.$texto['msg_qtd'].'</b> '
		.$item['quantity'].'x '
		.$item['attributes']['nome']
		.'<br /><b>'.$texto['msg_adicionais'].'</b> '.
		(!empty($item['attributes']['totalAdicionais']) ? $todosOsAdicionais : $texto['msg_sem_adicionais'])
		.'<br />'

		.'<b>'.$texto['msg_valor'].'</b> R$ '.Check::Real(($item['attributes']['preco'] * $item['quantity']) + (!empty($item['attributes']['totalAdicionais']) ? ($todosOsAdicionaisSoma * $item['quantity']) : 0) )
		.'<br /><b>OBS:</b> '.$item['attributes']['observacao']

		.'<br /><br />';
	}
}

function tirarAcentos($string){
	$formato = array();
	$formato['a'] = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜüÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿRr';
	$formato['b'] = 'AAAAAAAcEEEEIIIIDNOOOOOOUUUUuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
	$string = strtr(utf8_decode($string), utf8_decode($formato['a']), $formato['b']);

	return utf8_encode($string);
}


$get_dados_pedido = filter_input_array(INPUT_POST, FILTER_DEFAULT);


if(!empty($get_dados_pedido['confirm_whatsapp']) && $get_dados_pedido['confirm_whatsapp'] == 'true'):
	$get_dados_pedido['confirm_whatsapp'] = $get_dados_pedido['confirm_whatsapp'];
else:
	$get_dados_pedido['confirm_whatsapp'] = 'false';
endif;

if(isset($get_dados_pedido['enviar_pedido']) && $get_dados_pedido['enviar_pedido'] == 'enviar_agora'):
	unset($get_dados_pedido['enviar_pedido']);

		// LIMPA OS CAMPOS RETIRANDO TAGS E ESPAÇOS DESNECESSÁRIOS
	$get_dados_pedido = array_map('strip_tags', $get_dados_pedido);
	$get_dados_pedido = array_map('trim', $get_dados_pedido);

	$get_dados_pedido['complemento'] = (empty($get_dados_pedido['complemento']) ? '*Não informado*' : $get_dados_pedido['complemento']);
	$get_dados_pedido['observacao'] = (empty($get_dados_pedido['observacao']) ? '*Não informado*' : $get_dados_pedido['observacao']);

	$get_dados_pedido['telefone']    = preg_replace("/[^0-9]/", "", $get_dados_pedido['telefone']);

	if(in_array('', $get_dados_pedido) || in_array('null', $get_dados_pedido)):
		echo "<script>
	x0p('', 
	'Opss... {$texto['msg_msg_camposVazios']}',
	'error', false);

	var sound = new Howl({
		src: ['{$site}ops.mp3'],
		volume: 1.0,
		autoplay: true,
		});
		sound.play();
		</script>";
	elseif(strlen($get_dados_pedido['telefone']) < 11):
		echo "<script>
		x0p('', 
		'Opss... O numero de telefone informado e inválido!',
		'error', false);

		var sound = new Howl({
			src: ['{$site}ops.mp3'],
			volume: 1.0,
			autoplay: true,
			});
			sound.play();
			</script>";
		else:
			$moeda = "R$";
			$mes = date("m");
			$ano = date("y");
			$cont = 1;

			$lerbanco->ExeRead('ws_pedidos', "WHERE user_id = :usergeid AND (mes = :fmes AND ano = :fano)", "usergeid={$getu}&fmes={$mes}&fano={$ano}");
			if (!$lerbanco->getResult()):
				$get_dados_pedido['codigo_pedido'] = 'PED'.$mes.$ano.'-'.$cont;
			else:
				$get_dados_pedido['codigo_pedido'] = 'PED'.$mes.$ano.'-'.($cont + $lerbanco->getRowCount());
			endif;

			$get_dados_pedido['resumo_pedidos']   = $pedidos;

			$get_dados_pedido['mes']   = $mes;
			$get_dados_pedido['ano']   = $ano;
			$get_dados_pedido['resumo_pedidos']   = $pedidos;
			$get_dados_pedido['data']             = date('d/m/Y');
			$get_dados_pedido['data_chart']       = date('Y-m');
			$get_dados_pedido['data_chart2']      = date('Y-m-d');
			$get_dados_pedido['status']           = 'Aberto';
			$get_dados_pedido['nome']           = $get_dados_pedido['nome'];
			$get_dados_pedido['telefone_empresa'] = (!empty($telefone_empresa) ? $telefone_empresa : '');

		// Get all items in the cart

			$allItems = $cart->getItems();

			$get_dados_pedido['adicionais'] = 0;

			foreach ($allItems as $items):

				foreach ($items as $item):

					$todosOsAdicionaisSoma2 = 0;
					if(!empty($item['attributes']['totalAdicionais'])):

						for($i=0; $i < $item['attributes']['totalAdicionais']; $i++):
							$todosOsAdicionaisSoma2 = ($todosOsAdicionaisSoma2 + $item['attributes']['adicional_valor'.$i]);
						endfor;
						$todosOsAdicionaisSoma2 = ($todosOsAdicionaisSoma2 * $item['quantity']);
					endif;

					$get_dados_pedido['adicionais'] = $get_dados_pedido['adicionais'] + $todosOsAdicionaisSoma2;


				endforeach;
			endforeach;

			$dados_total_com_add = $cart->getAttributeTotal('preco') + $get_dados_pedido['adicionais'];

			if(!empty($_SESSION['desconto_cupom']) && $_SESSION['desconto_cupom']['user_id'] == $getu):
				$dados_total_com_add = ($dados_total_com_add - Check::porcentagem($_SESSION['desconto_cupom']['desconto'], $dados_total_com_add));
			endif;

			$valor_do_delivery0 = '';
			if($config_delivery_free == '0.00' && $get_dados_pedido['opcao_delivery'] == 'true'): 
				$valor_do_delivery0 = $config_delivery; 
			elseif($get_dados_pedido['opcao_delivery'] == 'true' && $config_delivery_free != '0.00' && $dados_total_com_add < $config_delivery_free):
				$valor_do_delivery0 = $config_delivery;  
			else: 
				$valor_do_delivery0 = '0.00';
			endif;

			$get_dados_pedido['total'] = $dados_total_com_add + $get_dados_pedido['valor_taxa'];

			$valorDaTaxa = Check::Real($get_dados_pedido['valor_taxa']);
			$msgSedelivery = ($get_dados_pedido['opcao_delivery'] == 'true' ? "*{$texto['msg_cart_delivery']}:* r$ {$valorDaTaxa}<br />" : '');


			$get_dados_pedido['total'] = Check::Real($get_dados_pedido['total']);
			$get_dados_pedido['nome'] = strip_tags(trim($get_dados_pedido['nome']));


			$get_dados_pedido['nome'] = str_replace(' ', '%20', $get_dados_pedido['nome']);
			$get_dados_pedido['nome'] = ucfirst ($get_dados_pedido['nome']);

			$inicio_texto = "Segue o pedido<br /><br />*{$get_dados_pedido['codigo_pedido']}*<br /><br />Nome: *{$get_dados_pedido['nome']}*<br /><br />Pedido:<br />";

			$enviarPedidos = str_replace('<br />', '%0A', $get_dados_pedido['resumo_pedidos']);
			$enviarPedidos = str_replace('<b>', '*', $enviarPedidos);
			$enviarPedidos = str_replace('</b>', '*', $enviarPedidos);
			$enviarPedidos = str_replace(' ', '%20', $enviarPedidos);




			$enviarPedidos = str_replace('r$', 'R$', $enviarPedidos);
			$enviarPedidos = str_replace('qtd', 'Qtd', $enviarPedidos);
			$enviarPedidos = str_replace('adicionais', 'Adicionais', $enviarPedidos);
			$enviarPedidos = str_replace('valor', 'Valor', $enviarPedidos);
		//$enviarPedidos = str_replace('Qtd:', '%0AQtd:', $enviarPedidos);

			if($get_dados_pedido['opcao_delivery'] != 'false'):								

				$bairrolink = (!empty($get_dados_pedido['bairro2']) ? $get_dados_pedido['bairro2'] : $get_dados_pedido['bairro']);
				$get_dados_pedido['bairro'] = (!empty($get_dados_pedido['bairro2']) ? $get_dados_pedido['bairro2'] : $get_dados_pedido['bairro']);
			endif;


			if($get_dados_pedido['opcao_delivery'] == 'true'):
				$terceira_parte_pedido = "*Endereço:*<br />Rua: {$get_dados_pedido['rua']}, Nº: {$get_dados_pedido['unidade']},<br />Bairro: {$bairrolink},<br />Cidade: {$get_dados_pedido['cidade']}, {$get_dados_pedido['uf']},<br />Complemento:<br />{$get_dados_pedido['complemento']}<br />OBS: {$get_dados_pedido['observacao']}<br /><br />";

			elseif(!empty($get_dados_pedido['mesa']) && !empty($get_dados_pedido['pessoas'])):

				$terceira_parte_pedido = "*Nº da mesa:* {$get_dados_pedido['mesa']}<br />*Pessoas:* {$get_dados_pedido['pessoas']}<br /><br />";
			$get_dados_pedido['msg_delivery_false'] = "Mesa: {$get_dados_pedido['mesa']}<br />Pessoas: {$get_dados_pedido['pessoas']}";			
		else:
			$terceira_parte_pedido = "*Vou Buscar no local*<br /><br />";
			$get_dados_pedido['msg_delivery_false'] = "Retirada no Balcão";
		endif;


		$terceira_parte_pedido = str_replace('<br />', '%0A', $terceira_parte_pedido);
		$terceira_parte_pedido = str_replace(' ', '%20', $terceira_parte_pedido);
		$terceira_parte_pedido = str_replace('endereco', 'Endereco', $terceira_parte_pedido);


		if(empty($get_dados_pedido['valor_troco'])):
			$get_dados_pedido['valor_troco'] = '0,00';
		endif;

		$porcentagemg = '';
		if(!empty($_SESSION['desconto_cupom']) && $_SESSION['desconto_cupom']['user_id'] == $getu):
			$porcentagemg = "*Desconto:* {$_SESSION['desconto_cupom']['desconto']}%<br />";
		endif;		


		if(!empty($get_dados_pedido['mesa']) && !empty($get_dados_pedido['pessoas'])):
			$quarta_parte_pedido = "{$porcentagemg}*Observações:* <br /> {$get_dados_pedido['name_observacao_mesa']}<br /><br />*{$get_dados_pedido['data']}*";
		$quarta_parte_pedido = str_replace('<br />', '%0A', $quarta_parte_pedido);
	elseif(!empty($get_dados_pedido['forma_pagamento'])):

		$quarta_parte_pedido = "*Pagamento:* {$get_dados_pedido['forma_pagamento']}<br />*SubTotal* R$ ".Check::Real($get_dados_pedido['sub_total'])."<br />{$porcentagemg}{$msgSedelivery}*Total:* {$moeda} {$get_dados_pedido['total']}<br />*Troco para:* {$moeda} {$get_dados_pedido['valor_troco']}<br /><br />*{$get_dados_pedido['data']}*";


		$quarta_parte_pedido = str_replace('pagamento', 'Pagamento', $quarta_parte_pedido);
		$quarta_parte_pedido = str_replace('total', 'Total', $quarta_parte_pedido);
		$quarta_parte_pedido = str_replace('troco', 'Troco', $quarta_parte_pedido);
		$quarta_parte_pedido = str_replace('r$', 'R$', $quarta_parte_pedido);
		$quarta_parte_pedido = str_replace('obrigado pelo pedido', 'Obrigado pelo pedido', $quarta_parte_pedido);
		$quarta_parte_pedido = str_replace('<br />', '%0A', $quarta_parte_pedido);
		$quarta_parte_pedido = str_replace(' ', '%20', $quarta_parte_pedido);

	endif;

	if(!empty($get_dados_pedido['mesa']) && !empty($get_dados_pedido['pessoas'])):
		unset($get_dados_pedido['mesa']);
		unset($get_dados_pedido['pessoas']);
	endif;


$linkTratado = "{$inicio_texto}";

$linkTratado = str_replace('<br />', '%0A', $linkTratado);
$linkTratado = str_replace(' ', '%20', $linkTratado);

$link = "https://api.whatsapp.com/send?phone=55{$get_dados_pedido['telefone_empresa']}&text=🔔%20{$linkTratado}{$enviarPedidos}{$terceira_parte_pedido}{$quarta_parte_pedido}";

if(!empty($get_dados_pedido['mesa']) && !empty($get_dados_pedido['pessoas'])):
else:
	$get_dados_pedido['valor_troco'] = Check::Valor($get_dados_pedido['valor_troco']);
endif;

$get_dados_pedido['total']       = Check::Valor($get_dados_pedido['total']);
$get_dados_pedido['data']        = Check::Data($get_dados_pedido['data']);


unset($get_dados_pedido['bairro2']);

$get_dados_pedido['valor_taxa'] = ($get_dados_pedido['opcao_delivery'] == 'true' ? $get_dados_pedido['valor_taxa'] : '0.00');								

$get_dados_pedido['view'] = 0;
if(!empty($_SESSION['desconto_cupom']) && $_SESSION['desconto_cupom']['user_id'] == $getu):
	$get_dados_pedido['desconto'] = $_SESSION['desconto_cupom']['desconto'];
endif;


								//INICI0 DO CODIGO DE VALODAÇÃO OPEN CLOSE

if(!empty($config_segunda)):
	$primeiroDia = explode(" ", $config_segunda);					
endif;
$mon_dia = (!empty($config_segunda) && $primeiroDia[0] == 'on' ? $primeiroDia[1] : '');
			//---------------------------------------------------------------------------------
if(!empty($config_terca)):
	$segundoDia = explode(" ", $config_terca);
endif;
$tue_dia = (!empty($config_terca) && $segundoDia[0] == 'on' ? $segundoDia[1] : '');
			//---------------------------------------------------------------------------------
if(!empty($config_quarta)):
	$terceiroDia = explode(" ", $config_quarta);
endif;
$wed_dia = (!empty($config_quarta) && $terceiroDia[0] == 'on' ? $terceiroDia[1] : '');
			//---------------------------------------------------------------------------------
if(!empty($config_quinta)):
	$quartoDia = explode(" ", $config_quinta);
endif;
$thu_dia = (!empty($config_quinta) && $quartoDia[0] == 'on' ? $quartoDia[1] : '');
			//---------------------------------------------------------------------------------
if(!empty($config_sexta)):
	$quintoDia = explode(" ", $config_sexta);
endif;
$fri_dia = (!empty($config_sexta) && $quintoDia[0] == 'on' ? $quintoDia[1] : '');
			//---------------------------------------------------------------------------------
if(!empty($config_sabado)):
	$sextoDia = explode(" ", $config_sabado);
endif;
$sat_dia = (!empty($config_sabado) && $sextoDia[0] == 'on' ? $sextoDia[1] : '');
			//---------------------------------------------------------------------------------
if(!empty($config_domingo)):
	$setimoDia = explode(" ", $config_domingo);
endif;
$sun_dia = (!empty($config_domingo) && $setimoDia[0] == 'on' ? $setimoDia[1] : '');
$hours = array(
	"mon" => array("{$mon_dia}"),
	"tue" => array("{$tue_dia}"),
	"wed" => array("{$wed_dia}"),
	"thu" => array("{$thu_dia}"), 
	"fri" => array("{$fri_dia}"),
	"sat" => array("{$sat_dia}"),
	"sun" => array("{$sun_dia}")
);
$lerbanco->ExeRead("ws_datas_close", "WHERE user_id = :delivdata", "delivdata={$getu}");
$exceptions = array();
if($lerbanco->getResult()):
	foreach($lerbanco->getResult() as $dadosC):
		extract($dadosC);
		$i = explode('/', $data);
		$i = array_reverse($i);
		$i = implode("-", $i);							

		if(isDateExpired($i, 1)):
			$exceptions["{$i}"] = array();							
		endif;
	endforeach;
endif;

$config = array(
	'separator'      => ' - ',
	'join'           => ' and ',
	'format'         => 'g:ia',
	'overview_weekdays'  => array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun')
);

					// Iniciando a classe
$store_hours = new StoreHours($hours, $exceptions, $config);

$dia_array = array('DOM','SEG','TER','QUA','QUI','SEX','SAB');

					//tratar a data:
					$hoje = strtotime(date('Y-m-j'));  // Y = ano, m = mês, j = dia
					$diaDaSemana = date('w', $hoje); // w = 0 (para domingo) até 6 (para sábado)
					$dia_nome  = $dia_array[$diaDaSemana]; 


					$verhoje = date('Y-m-d');

					if($dia_nome == 'DOM' && !empty($config_domingo) && $setimoDia[0] == 'on'):
						if(array_key_exists($verhoje, $exceptions)):
							//echo $texto['msg_fechado_dia'].'<br />';
						else:
							$domingoRay = explode("-", $sun_dia);
							//echo $domingoRay[0]." {$texto['msg_ate']} ".$domingoRay[1].'<br />';
						endif;
					elseif($dia_nome == 'SEG' && !empty($config_segunda) && $primeiroDia[0] == 'on'):
						if(array_key_exists($verhoje, $exceptions)):
							//echo $texto['msg_fechado_dia'].'<br />';
						else:
							$segundaRay = explode("-", $mon_dia);
							//echo $segundaRay[0]." {$texto['msg_ate']} ".$segundaRay[1].'<br />';
						endif;
					elseif($dia_nome == 'TER' && !empty($config_terca) && $segundoDia[0] == 'on'):
						if(array_key_exists($verhoje, $exceptions)):
							//echo $texto['msg_fechado_dia'].'<br />';
						else:
							$tercaRay = explode("-", $tue_dia);
							//echo $tercaRay[0]." {$texto['msg_ate']} ".$tercaRay[1].'<br />';
						endif;
					elseif($dia_nome == 'QUA' && !empty($config_quarta) && $terceiroDia[0] == 'on'):
						if(array_key_exists($verhoje, $exceptions)):
							//echo $texto['msg_fechado_dia'].'<br />';
						else:
							$quartaRay = explode("-", $wed_dia);
							//echo $quartaRay[0]." {$texto['msg_ate']} ".$quartaRay[1].'<br />';
						endif;												
					elseif($dia_nome == 'QUI' && !empty($config_quinta) && $quartoDia[0] == 'on'):
						if(array_key_exists($verhoje, $exceptions)):
							//echo $texto['msg_fechado_dia'].'<br />';
						else:
							$quintaRay = explode("-", $thu_dia);
							//echo $quintaRay[0]." {$texto['msg_ate']} ".$quintaRay[1].'<br />';
						endif;
					elseif($dia_nome == 'SEX' && !empty($config_sexta) && $quintoDia[0] == 'on'):
						if(array_key_exists($verhoje, $exceptions)):
							//echo $texto['msg_fechado_dia'].'<br />';
						else:
							$sextaRay = explode("-", $fri_dia);
							//echo $sextaRay[0]." {$texto['msg_ate']} ".$sextaRay[1].'<br />';
						endif;
					elseif($dia_nome == 'SAB' && !empty($config_sabado) && $sextoDia[0] == 'on'):
						if(array_key_exists($verhoje, $exceptions)):
							//echo $texto['msg_fechado_dia'].'<br />';
						else:
							$sabadoRay = explode("-", $sat_dia);
							//echo $sabadoRay[0]." {$texto['msg_ate']} ".$sabadoRay[1].'<br />';
						endif;
					else:
						//NÃO FAZ NADA!!
					endif; 
					 // Display open / closed menssagem
					if($store_hours->is_open()) {

						if($get_dados_pedido['opcao_delivery'] == 'true' && !empty($minimo_delivery) && $minimo_delivery != '0.00' && $get_dados_pedido['total'] < $minimo_delivery):
							$minimo_delivery = Check::Real($minimo_delivery);
							echo "<script>
							x0p('', 
							'Opss... O valor mínimo do delivery e de R$ {$minimo_delivery}',
							'error', false);

							var sound = new Howl({
								src: ['{$site}ops.mp3'],
								volume: 1.0,
								autoplay: true,
								});
								sound.play();
								</script>";
							else:
						//INICIO COLOCAR DENTRO DOCIDIGO APOS VALIDACÃO
								$addbanco->ExeCreate("ws_pedidos", $get_dados_pedido);
								if ($addbanco->getResult()):

									if(!empty($_SESSION['desconto_cupom'])):
										unset($_SESSION['desconto_cupom']);
									endif;
									$cart->clear();

									if($get_dados_pedido['confirm_whatsapp'] == 'true'):
										echo "
										<script type=\"text/javascript\">
										var link1 = \"{$link}\";
										window.location.replace(link1);
										</script>
										";
									else:
										echo "<script>
										x0p({
											title: 'Sucesso!',
											text: 'Recebemos seu pedido! Aguarde nosso contato.',
											animationType: 'slideUp',
											buttons: [

											{
												type: 'info',
												key: 50,
												text: 'OK'
											}
											]
											}).then(function(data) {
												if(data.button == 'info'){
													window.location.reload(1);
												}
												});

												var sound = new Howl({
													src: ['{$site}campainha.mp3'],
													volume: 1.0,
													autoplay: true,
													});
													sound.play();
													</script>";	
												endif;

											else:
												echo "<script>
												x0p('Opss...', 
												'OCORREU UM ERRO!',
												'error', false);

												var sound = new Howl({
													src: ['{$site}ops.mp3'],
													volume: 1.0,
													autoplay: true,
													});
													sound.play();
													</script>";								
							    endif;//INICIO COLOCAR DENTRO DOCIDIGO APOS VALIDACÃO
							    //INICIO COLOCAR DENTRO DOCIDIGO APOS VALIDACÃO

							endif;
						} else {

							echo "<script>
							sweetAlert(\"Oops...\", \"{$texto['msg_msg_fechado']}\", \"{$site}img/loja-fechado.png\");

							var sound = new Howl({
								src: ['{$site}ops.mp3'],
								volume: 1.0,
								autoplay: true,
								});
								sound.play();
								</script>";

												}//FIM DO CODIGO DE VALODAÇÃO OPEN CLOSE
					//FIM DO CODIGO DE VALODAÇÃO OPEN CLOSE

											endif;
										endif;

										ob_end_flush();