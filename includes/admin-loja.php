<?php

$login = new Login(3);

if(!$login->CheckLogin()):
	unset($_SESSION['userlogin']);
	header("Location: {$site}");
else:
	$userlogin = $_SESSION['userlogin'];
endif;

$logoff = filter_input(INPUT_GET, 'logoff', FILTER_VALIDATE_BOOLEAN);

$dataFomatadarenovacao = explode('-', $empresa_data_renovacao);
$dataFomatadarenovacao = array_reverse($dataFomatadarenovacao);
$dataFomatadarenovacao = implode('/', $dataFomatadarenovacao);

if(!empty($_GET['statusmp'])):

	$statusMP = strip_tags(trim($_GET['statusmp']));

	if(!empty($statusMP) && $statusMP == "approved"):
		echo "<script>x0p('Sucesso!', 
		'Recebemos seu pagamento! Plano ativo até {$dataFomatadarenovacao}', 
		'ok', false);</script>";
	elseif(!empty($statusMP) && $statusMP == "rejected"):
		echo "<script>x0p('Ocorreu um Erro', 
		'Seu cartão foi recusado! Entre em contato conosco.',
		'error', false);</script>";
	endif;

endif;

if(!empty($logoff) && $logoff == true):
	$updateacesso = new Update;
	$dataEhora    = date('d/m/Y H:i');
	$ip           = get_client_ip();
	$string_last = array("user_ultimoacesso" => " Último acesso em: {$dataEhora} IP: {$ip} ");
	$updateacesso->ExeUpdate("ws_users", $string_last, "WHERE user_id = :uselast", "uselast={$userlogin['user_id']}");
	
	unset($_SESSION['userlogin']);
	header("Location: {$site}");
endif;

$updatebanco = new Update();
?>
<div style="background-color:#ffffff;" class="container margin_60">
	<div id="sendempresa"></div>


	<section id="section-1">
		<div class="indent_title_in">
			<i class="icon_house_alt"></i>
			<h3>Descrição geral do seu negócio</h3>
			<p>Insira no formulario abaixo detalhes do seu negócio e informações de contato.</p>
		</div>				

		<form method="post" action="#sendempresa" enctype="multipart/form-data">
			<div class="wrapper_indent">
				<?php

				$getdelldate = filter_input(INPUT_GET, 'dellDate', FILTER_VALIDATE_INT);

				if(!empty($getdelldate) && !isset($_POST['sendempresa'])):

					$lerbanco->ExeRead('ws_datas_close', "WHERE user_id = :userid AND id = :v", "userid={$userlogin['user_id']}&v={$getdelldate}");
				if ($lerbanco->getResult()):
					$deletbanco->ExeDelete("ws_datas_close", "WHERE user_id = :userid AND id = :k", "userid={$userlogin['user_id']}&k={$getdelldate}");
					if ($deletbanco->getResult()):
						echo "<div class=\"alert alert-success alert-dismissable\">
						<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button>
						<b class=\"alert-link\">SUCESSO!</b> A data de exceção foi deletada do sistema.
						</div>";
						header("Refresh: 5; url={$site}{$Url[0]}/admin-loja");
					else:
						echo "<div class=\"alert alert-danger alert-dismissable\">
						<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button>
						<b class=\"alert-link\">OCORREU UM ERRO DE CONEXÃO!</b> Tente novamente.
						</div>";
						header("Refresh: 5; url={$site}{$Url[0]}/admin-loja");
					endif;
				endif;
			endif;


			if(isset($_POST['sendempresa']) && $_POST['sendempresa'] == true):
				if(!empty($_POST['data_close'])):

					$dataClose1 = strip_tags(trim($_POST['data_close']));

					$data_c['data'] = $dataClose1;
					$data_c['user_id'] = $userlogin['user_id'];

					if(strlen($dataClose1) == 10):
						$lerbanco->ExeRead("ws_datas_close", "WHERE user_id = :userid AND data = :dat", "userid={$userlogin['user_id']}&dat={$dataClose1}");
						if($lerbanco->getResult()):
							//NÃO FAZ NADA
						else:
							$addbanco->ExeCreate("ws_datas_close", $data_c);
						endif;
					endif;						
				endif;
			endif;


			if(isset($_POST['sendempresa']) && $_POST['sendempresa'] == true):

				$segundaMostrar = (!empty($_POST['seg-check']) ? 'on' : 'off');
				$segundaDe = (!empty($_POST['config_segunda_de']) ? strip_tags(trim($_POST['config_segunda_de'])) : '00:00');
				$segundaAte = (!empty($_POST['config_segunda_ate']) ? strip_tags(trim($_POST['config_segunda_ate'])) : '00:00');

				$tercaMostrar = (!empty($_POST['ter-check']) ? 'on' : 'off');
				$tercaDe = (!empty($_POST['config_terca_de']) ? strip_tags(trim($_POST['config_terca_de'])) : '00:00');
				$tercaAte = (!empty($_POST['config_terca_ate']) ? strip_tags(trim($_POST['config_terca_ate'])) : '00:00');

				$quartaMostrar = (!empty($_POST['qua-check']) ? 'on' : 'off');
				$quartaDe = (!empty($_POST['config_quarta_de']) ? strip_tags(trim($_POST['config_quarta_de'])) : '00:00');
				$quartaAte = (!empty($_POST['config_quarta_ate']) ? strip_tags(trim($_POST['config_quarta_ate'])) : '00:00');

				$quintaMostrar = (!empty($_POST['qui-check']) ? 'on' : 'off');
				$quintaDe = (!empty($_POST['config_quinta_de']) ? strip_tags(trim($_POST['config_quinta_de'])) : '00:00');
				$quintaAte = (!empty($_POST['config_quinta_ate']) ? strip_tags(trim($_POST['config_quinta_ate'])) : '00:00');

				$sextaMostrar = (!empty($_POST['sex-check']) ? 'on' : 'off');
				$sextaDe = (!empty($_POST['config_sexta_de']) ? strip_tags(trim($_POST['config_sexta_de'])) : '00:00');
				$sextaAte = (!empty($_POST['config_sexta_ate']) ? strip_tags(trim($_POST['config_sexta_ate'])) : '00:00');

				$sabadoMostrar = (!empty($_POST['sab-check']) ? 'on' : 'off');
				$sabadoDe = (!empty($_POST['config_sabado_de']) ? strip_tags(trim($_POST['config_sabado_de'])) : '00:00');
				$sabadoAte = (!empty($_POST['config_sabado_ate']) ? strip_tags(trim($_POST['config_sabado_ate'])) : '00:00');

				$domingoMostrar = (!empty($_POST['dom-check']) ? 'on' : 'off');
				$domingoDe = (!empty($_POST['config_domingo_de']) ? strip_tags(trim($_POST['config_domingo_de'])) : '00:00');
				$domingoAte = (!empty($_POST['config_domingo_ate']) ? strip_tags(trim($_POST['config_domingo_ate'])) : '00:00');
			endif;

			$inputdadosempresa = filter_input_array(INPUT_POST, FILTER_DEFAULT);

			if ($inputdadosempresa && !empty($inputdadosempresa['sendempresa'])):					

				unset($inputdadosempresa['sendempresa']);
				unset($inputdadosempresa['_wysihtml5_mode']);
				unset($inputdadosempresa['data_close']);
				$inputdadosempresa['end_bairro_empresa'] = tratar_nome($inputdadosempresa['end_bairro_empresa']);

				if(!empty($inputdadosempresa['minimo_delivery'])):					
					$inputdadosempresa['minimo_delivery'] = Check::Valor($inputdadosempresa['minimo_delivery']);
				else:
					$inputdadosempresa['minimo_delivery'] = '0.00';
				endif;

						// LIMPA OS CAMPOS SOBRE OS HORÁRIOS PARA MAIS NA FRENTE REFASER
				unset($inputdadosempresa['config_segunda_de']); unset($inputdadosempresa['config_segunda_ate']);
				unset($inputdadosempresa['config_terca_de']); unset($inputdadosempresa['config_terca_ate']);
				unset($inputdadosempresa['config_quarta_de']); unset($inputdadosempresa['config_quarta_ate']);
				unset($inputdadosempresa['config_quinta_de']); unset($inputdadosempresa['config_quinta_ate']);
				unset($inputdadosempresa['config_sexta_de']); unset($inputdadosempresa['config_sexta_ate']);
				unset($inputdadosempresa['config_sabado_de']); unset($inputdadosempresa['config_sabado_ate']);
				unset($inputdadosempresa['config_domingo_de']); unset($inputdadosempresa['config_domingo_ate']);


				unset($inputdadosempresa['seg-check']); 
				unset($inputdadosempresa['ter-check']); 
				unset($inputdadosempresa['qua-check']);
				unset($inputdadosempresa['qui-check']);
				unset($inputdadosempresa['sex-check']);
				unset($inputdadosempresa['sab-check']);
				unset($inputdadosempresa['dom-check']);

						// LIMPA OS CAMPOS RETIRANDO TAGS E ESPAÇOS DESNECESSÁRIOS
				$inputdadosempresa = array_map('strip_tags', $inputdadosempresa);
				$inputdadosempresa = array_map('trim', $inputdadosempresa);

				if(!empty($inputdadosempresa['confirm_delivery'])):
					$inputdadosempresa['confirm_delivery'] = "true";
				else:
					$inputdadosempresa['confirm_delivery'] = "false";
				endif;
				if(!empty($inputdadosempresa['confirm_balcao'])):
					$inputdadosempresa['confirm_balcao'] = "true";
				else:
					$inputdadosempresa['confirm_balcao'] = "false";
				endif;
				if(!empty($inputdadosempresa['confirm_mesa'])):
					$inputdadosempresa['confirm_mesa'] = "true";
				else:
					$inputdadosempresa['confirm_mesa'] = "false";
				endif;	


						// COMO NÃO EXISTE UM INPUT PARA IMAGEM TEMOS QUE FAZER VALIDAÇÃO VIA $_FILE MESMO

			// INICIO DA VALIDAÇÃO DA IMAGEM DE FUNDO
				if (isset($_FILES['img_header']['tmp_name']) && $_FILES['img_header']['tmp_name'] != ""):
					$inputdadosempresa['img_header'] = $_FILES['img_header'];
				else:
					unset($inputdadosempresa['img_header']);
				endif;


				if(!empty($inputdadosempresa['img_header'])):                        
					$upload = new Upload("uploads/");
					$upload->Image($inputdadosempresa['img_header']);

					if(isset($upload) && $upload->getResult()):
						$inputdadosempresa['img_header'] = $upload->getResult();
					if(!empty($inputdadosempresa['img_header']) && !empty($img_logo) && file_exists("uploads/{$img_header}") && !is_dir("uploads/{$img_header}")):
						unlink("uploads/{$img_header}");
				endif;
			elseif(is_array($inputdadosempresa['img_header'])):
				unset($inputdadosempresa['img_header']);
			endif;



		endif;
			// FIM DA VALIDAÇÃO DA IMAGEM DE FUNDO


						// INICIO DA VALIDAÇÃO DA IMAGEM PERFIL
		if (isset($_FILES['img_logo']['tmp_name']) && $_FILES['img_logo']['tmp_name'] != ""):
			$inputdadosempresa['img_logo'] = $_FILES['img_logo'];
		else:
			unset($inputdadosempresa['img_logo']);
		endif;


		if(!empty($inputdadosempresa['img_logo'])):                        
			$upload = new Upload("uploads/");
			$upload->Image($inputdadosempresa['img_logo']);

			if (isset($upload) && $upload->getResult()):	

				$inputdadosempresa['img_logo'] = $upload->getResult();

		elseif(is_array($inputdadosempresa['img_logo'])):
			unset($inputdadosempresa['img_logo']);
		endif;

		if(!empty($inputdadosempresa['img_logo']) && !empty($img_logo) && file_exists("uploads/{$img_logo}") && !is_dir("uploads/{$img_logo}")):
			unlink("uploads/{$img_logo}");
	endif;						

endif;

if(empty($inputdadosempresa['facebook_empresa'])):
	unset($inputdadosempresa['facebook_empresa']);
endif;

if(empty($inputdadosempresa['instagram_empresa'])):
	unset($inputdadosempresa['instagram_empresa']);
endif;

if(empty($inputdadosempresa['twitter_empresa'])):
	unset($inputdadosempresa['twitter_empresa']);
endif;

						// FIM DA VALIDAÇÃO DA IMAGEM DE PERFIL 
						//---------------------------				

if (in_array('', $inputdadosempresa) || in_array('null', $inputdadosempresa)):
	echo "<div class=\"alert alert-info alert-dismissable\">
<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button>
Preencha todos os campos!
</div>";
header("Refresh: 5; url={$site}{$Url[0]}/admin-loja");
elseif (!Check::Email($inputdadosempresa['email_empresa'])):
	echo "<div class=\"alert alert-warning alert-dismissable\">
	<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button>
	O EMAIL informado não e valido!
	</div>";
	header("Refresh: 5; url={$site}{$Url[0]}/admin-loja");
else:						
	$inputdadosempresa['telefone_empresa'] = preg_replace("/[^0-9]/", "", $inputdadosempresa['telefone_empresa']);
	$inputdadosempresa['user_id'] = $userlogin['user_id'];	

	$inputdadosempresa['config_delivery'] = Check::Valor($inputdadosempresa['config_delivery']);

	$inputdadosempresa['config_segunda'] = "{$segundaMostrar} {$segundaDe}-{$segundaAte}";						
	$inputdadosempresa['config_terca'] = "{$tercaMostrar} {$tercaDe}-{$tercaAte}";						
	$inputdadosempresa['config_quarta'] = "{$quartaMostrar} {$quartaDe}-{$quartaAte}";
	$inputdadosempresa['config_quinta'] = "{$quintaMostrar} {$quintaDe}-{$quintaAte}";
	$inputdadosempresa['config_sexta'] = "{$sextaMostrar} {$sextaDe}-{$sextaAte}";
	$inputdadosempresa['config_sabado'] = "{$sabadoMostrar} {$sabadoDe}-{$sabadoAte}";
	$inputdadosempresa['config_domingo'] = "{$domingoMostrar} {$domingoDe}-{$domingoAte}";


							//COMEÇO A FAZER A GRAVAÇÃO DOS DADOS::::::::::::::::::::::::::::::::::::::::::::::::::
	$lerbanco->ExeRead('ws_empresa', "WHERE user_id = :v", "v={$userlogin['user_id']}");
	if (!$lerbanco->getResult()):		
		$addbanco->ExeCreate("ws_empresa", $inputdadosempresa);
		if ($addbanco->getResult()):												
			echo "<div class=\"alert alert-success alert-dismissable\">
			<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button>
			<b class=\"alert-link\">SUCESSO!</b> Seus dados foram Inseridos no sistema.
			</div>";
			header("Refresh: 5; url={$site}{$Url[0]}/admin-loja");
		else:
			echo "<div class=\"alert alert-danger alert-dismissable\">
			<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button>
			<b class=\"alert-link\">OCORREU UM ERRO!</b> Tente novamente.
			</div>";
			header("Refresh: 5; url={$site}{$Url[0]}/admin-loja");
		endif;

	else:
		$updatebanco->ExeUpdate("ws_empresa", $inputdadosempresa, "WHERE user_id = :up", "up={$userlogin['user_id']}");
		if ($updatebanco->getResult()):
			echo "<div class=\"alert alert-success alert-dismissable\">
			<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button>
			<b class=\"alert-link\">SUCESSO!</b> Seus dados foram Atualizados no sistema.
			</div>";
			header("Refresh: 5; url={$site}{$Url[0]}/admin-loja");
		else:
			echo "<div class=\"alert alert-danger alert-dismissable\">
			<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button>
			<b class=\"alert-link\">OCORREU UM ERRO!</b> Tente novamente.
			</div>";
			header("Refresh: 5; url={$site}{$Url[0]}/admin-loja");
		endif;
	endif;					

endif;
endif;
?>
<div class="form-group">
	<label for="nome_empresa">Nome do seu negócio:</label>
	<input class="form-control" required value="<?=(!empty($nome_empresa) ? $nome_empresa : '');?>" name="nome_empresa" id="nome_empresa" type="text">
</div>
<div class="form-group">
	<label for="descricao_empresa">Breve descrição do seu negócio:</label>
	<input type="text" required maxlength="297" name="descricao_empresa" class="form-control" placeholder="Digite uma descrição..." value="<?=(!empty($descricao_empresa) ? $descricao_empresa : '');?>" />
</div>
<div class="row">
	<div class="col-sm-6">
		<div class="form-group">
			<label for="telefone_empresa">Suporte WhatsApp:</label>
			<input required type="tel" placeholder="(99) 99999-9999" data-mask="(00) 00000-0000" maxlength="15" id="telefone_empresa" name="telefone_empresa" value="<?=(!empty($telefone_empresa) ? $telefone_empresa : '');?>" class="form-control">
		</div>
	</div>
	<div class="col-sm-6">
		<div class="form-group">
			<label for="email_empresa">E-mail:</label>
			<input required type="email" id="email_empresa" value="<?=(!empty($email_empresa) ? $email_empresa : '');?>" name="email_empresa" class="form-control">
		</div>
	</div>
</div>
</div><!-- End wrapper_indent -->

<hr />

<div class="indent_title_in">
	<i class="icon_pin_alt"></i>
	<h3>Endereço</h3>
	<p>
		Defina o endereço do seu negócio!
	</p>
</div>
<div class="wrapper_indent">
	<div class="row">
		<div class="col-sm-6">
			<div class="form-group">
				<label for="estados">ESTADO:</label>
				<select required class="form-control" name="end_uf_empresa" id="estados">
					
				</select>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label for="cidade_empresa">CIDADE:</label>
				<select required class="form-control" name="cidade_empresa" id="cidades">

				</select>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6">
			<div class="form-group">
				<label required for="end_rua_n_empresa">RUA / Nº:</label>
				<input type="text" id="end_rua_n_empresa" value="<?=(!empty($end_rua_n_empresa) ? $end_rua_n_empresa : '');?>" name="end_rua_n_empresa" class="form-control">
			</div>
		</div>
		<div class="col-sm-6">
			<div class="form-group">
				<label for="end_bairro_empresa">BAIRRO:</label>
				<input required type="text" id="end_bairro_empresa" value="<?=(!empty($end_bairro_empresa) ? $end_bairro_empresa : '');?>" name="end_bairro_empresa" class="form-control">
			</div>
		</div>
	</div>
</div><!-- End wrapper_indent -->

<hr />

<div class="indent_title_in">
	<i class="fa fa-motorcycle" aria-hidden="true"></i>
	<h3>Opções de entrega</h3>
	<div class="form-group">	

		<div class="icheck-material-green">
			<input <?=(!empty($confirm_delivery) && $confirm_delivery == "true" ? "checked" : "");?> type="checkbox" name="confirm_delivery" value="true" id="confirm_delivery" />
			<label for="confirm_delivery"><strong>Permitir delivery</strong></label>
		</div>
		<div class="icheck-material-green">
			<input <?=(!empty($confirm_balcao) && $confirm_balcao == "true" ? "checked" : "");?> type="checkbox" name="confirm_balcao" value="true" id="confirm_balcao" />
			<label for="confirm_balcao"><strong>Permitir retirada no balcão</strong></label>
		</div>
		<div class="icheck-material-green">
			<input <?=(!empty($confirm_mesa) && $confirm_mesa == "true" ? "checked" : "");?> type="checkbox" name="confirm_mesa" value="true" id="confirm_mesa" />
			<label for="confirm_mesa"><strong>Permitir pedido na mesa</strong></label>
		</div>
	</div>

	<p>
		<span style="color: red;">O valor inserido em "Custo padrão de entrega", será universal se não for adicionando nenhum bairro com taxas diferentes.</span>
		
	</p>
</div>

<div class="wrapper_indent">
	<div class="row">
		<div class="col-md-6 col-sm-6">
			<div class="form-group">
				<label for="config_delivery">Custo padrão de entrega:</label>
				<input type="text" required maxlength="11" onkeypress="return formatar_moeda(this, '.', ',', event);" data-mask="#.##0,00" data-mask-reverse="true" class="form-control" id="config_delivery" name="config_delivery" value="<?=(!empty($config_delivery) ? Check::Real($config_delivery) : '0,00');?>" />
			</div>
		</div>

		<div class="col-md-6 col-sm-6">
			<div class="form-group">
				<label for="minimo_delivery">Valor Mínimo do Delivery: <small style="color: red;">Opcional</small></label>
				<input type="text" required maxlength="11" onkeypress="return formatar_moeda(this, '.', ',', event);" data-mask="#.##0,00" data-mask-reverse="true" class="form-control" id="minimo_delivery" name="minimo_delivery" value="<?=(!empty($minimo_delivery) ? Check::Real($minimo_delivery) : '0,00');?>" />
			</div>
		</div>
	</div>	
	<div class="row">
		<div class="col-md-6 col-sm-6">
			<div class="form-group">
				<label>Mensagem sobre tempo de Delivery:</label>
				<input type="text" required class="form-control" id="msg_tempo_delivery" name="msg_tempo_delivery" value="<?=(!empty($msg_tempo_delivery) ? $msg_tempo_delivery : "Entre 30 e 60 minutos.");?>" />
			</div>
		</div>
		<div class="col-md-6 col-sm-6">
			<div class="form-group">
				<label>Mensagem sobre retirar no local:</label>
				<input type="text" required class="form-control" id="msg_tempo_buscar" name="msg_tempo_buscar" value="<?=(!empty($msg_tempo_buscar) ? $msg_tempo_buscar : "Em 30 minutos.");?>" />
			</div>
		</div>
	</div>
</div>

<hr />

<div class="indent_title_in">
	<i class="fa fa-clock-o" aria-hidden="true"></i>
	<h3>Horários de funcionamento</h3>
	<p>
		Defina o seu horário de atendimento para que seus clientes saibam quando seus serviços estiverem disponíveis.
	</p>
</div>

<div class="panel panel-default">
	<div style="background-color: #85c99d;color: #ffffff;" class="panel-heading">
		<h4 data-toggle="collapse" data-parent="#accordion" href="#collapse1" class="panel-title expand">
			<div class="right-arrow pull-right"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></div>
			<center><a style="color: #ffffff;" href="#">Cique aqui para Configurar Horários.</a></center>
		</h4>
	</div>
	<div id="collapse1" class="panel-collapse collapse">
		<div class="panel-body">
			<?php
			if(!empty($config_segunda)):
				$primeiroDia = explode(" ", $config_segunda);
				$primeiroDiaHorarios = explode("-", $primeiroDia[1]);

			endif;
			?>
			<div class="wrapper_indent">
				<input id="seg-check" name="seg-check" type="checkbox" <?=(!empty($config_segunda) && $primeiroDia[0] == 'on' ? 'checked' : '');?> /> <label for="seg-check"><strong style="color:#85c99d;">SELECIONE PARA ABRIR DIA DE SEGUNDA </strong></label>
				<div class="row">						
					<div class="col-sm-6">
						<div class="form-group">
							<label for="config_segunda_de">DE:</label>									
							<input required type="time" name="config_segunda_de" id="config_segunda_de" data-mask="00:00" value="<?=(!empty($config_segunda) ? $primeiroDiaHorarios[0] : '00:00');?>" class="form-control"/>									
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label for="config_segunda_ate">ATÉ:</label>
							<input required type="time" name="config_segunda_ate" id="config_segunda_ate" data-mask="00:00" value="<?=(!empty($config_segunda) ? $primeiroDiaHorarios[1] : '00:00');?>" class="form-control"/> 
						</div>
					</div>
				</div>
			</div><!-- End wrapper_indent -->	
			<?php
			if(!empty($config_terca)):
				$segundoDia = explode(" ", $config_terca);
				$segundoDiaHorarios = explode("-", $segundoDia[1]);

			endif;
			?>
			<div class="wrapper_indent">
				<input <?=(!empty($config_terca) && $segundoDia[0] == 'on' ? 'checked' : '');?> id="ter-check" name="ter-check" type="checkbox"> <label for="ter-check"><strong style="color:#85c99d;">SELECIONE PARA ABRIR DIA DE TERÇA</strong></label>
				<div class="row">						
					<div class="col-sm-6">
						<div class="form-group">
							<label for="config_terca_de">DE:</label>
							<input required type="time" name="config_terca_de" id="config_terca_de" data-mask="00:00" value="<?=(!empty($config_terca) ? $segundoDiaHorarios[0] : '00:00');?>" class="form-control"/>	
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label for="config_terca_ate">ATÉ:</label>
							<input required type="time" name="config_terca_ate" id="config_terca_ate" data-mask="00:00" value="<?=(!empty($config_terca) ? $segundoDiaHorarios[1] : '00:00');?>" class="form-control"/>	
						</div>
					</div>
				</div>
			</div><!-- End wrapper_indent -->
			<?php
			if(!empty($config_quarta)):
				$terceiroDia = explode(" ", $config_quarta);
				$terceiroDiaHorarios = explode("-", $terceiroDia[1]);

			endif;
			?>
			<div class="wrapper_indent">
				<input <?=(!empty($config_quarta) && $terceiroDia[0] == 'on' ? 'checked' : '');?> id="qua-check" name="qua-check" type="checkbox"> <label for="qua-check"><strong style="color:#85c99d;">SELECIONE PARA ABRIR DIA DE QUARTA</strong></label>
				<div class="row">						
					<div class="col-sm-6">
						<div class="form-group">
							<label for="config_quarta_de">DE:</label>
							<input required type="time" name="config_quarta_de" id="config_quarta_de" data-mask="00:00" value="<?=(!empty($config_quarta) ? $terceiroDiaHorarios[0] : '00:00');?>" class="form-control"/>	
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label for="config_quarta_ate">ATÉ:</label>
							<input required type="time" name="config_quarta_ate" id="config_quarta_ate" data-mask="00:00" value="<?=(!empty($config_quarta) ? $terceiroDiaHorarios[1] : '00:00');?>" class="form-control"/>	
						</div>
					</div>
				</div>
			</div><!-- End wrapper_indent -->
			<?php
			if(!empty($config_quinta)):
				$quartoDia = explode(" ", $config_quinta);
				$quartoDiaHorarios = explode("-", $quartoDia[1]);

			endif;
			?>
			<div class="wrapper_indent">
				<input <?=(!empty($config_quinta) && $quartoDia[0] == 'on' ? 'checked' : '');?> id="qui-check" name="qui-check" type="checkbox"> <label for="qui-check"><strong style="color:#85c99d;">SELECIONE PARA ABRIR DIA DE QUINTA</strong></label>
				<div class="row">						
					<div class="col-sm-6">
						<div class="form-group">
							<label for="config_quinta_de">DE:</label>
							<input required type="time" name="config_quinta_de" id="config_quinta_de" data-mask="00:00" value="<?=(!empty($config_quinta) ? $quartoDiaHorarios[0] : '00:00');?>" class="form-control"/>	
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label for="config_quinta_ate">ATÉ:</label>
							<input required type="time" name="config_quinta_ate" id="config_quinta_ate" data-mask="00:00" value="<?=(!empty($config_quinta) ? $quartoDiaHorarios[1] : '00:00');?>" class="form-control"/>	
						</div>
					</div>
				</div>
			</div><!-- End wrapper_indent -->
			<?php
			if(!empty($config_sexta)):
				$quintoDia = explode(" ", $config_sexta);
				$quintoDiaHorarios = explode("-", $quintoDia[1]);

			endif;
			?>
			<div class="wrapper_indent">
				<input <?=(!empty($config_sexta) && $quintoDia[0] == 'on' ? 'checked' : '');?> id="sex-check" name="sex-check" type="checkbox"> <label for="sex-check"><strong style="color:#85c99d;">SELECIONE PARA ABRIR DIA DE SEXTA</strong></label>
				<div class="row">						
					<div class="col-sm-6">
						<div class="form-group">
							<label for="config_sexta_de">DE:</label>
							<input required type="time" name="config_sexta_de" id="config_sexta_de" data-mask="00:00" value="<?=(!empty($config_sexta) ? $quintoDiaHorarios[0] : '00:00');?>" class="form-control"/>	
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label for="config_sexta_ate">ATÉ:</label>
							<input required type="time" name="config_sexta_ate" id="config_sexta_ate" data-mask="00:00" value="<?=(!empty($config_sexta) ? $quintoDiaHorarios[1] : '00:00');?>" class="form-control"/>	
						</div>
					</div>
				</div>
			</div><!-- End wrapper_indent -->

			<?php
			if(!empty($config_sabado)):
				$sextoDia = explode(" ", $config_sabado);
				$sextoDiaHorarios = explode("-", $sextoDia[1]);

			endif;
			?>
			<div class="wrapper_indent">
				<input <?=(!empty($config_sabado) && $sextoDia[0] == 'on' ? 'checked' : '');?> id="sab-check" name="sab-check" type="checkbox"> <label for="sab-check"><strong style="color:#85c99d;">SELECIONE PARA ABRIR DIA DE SABADO</strong></label>
				<div class="row">						
					<div class="col-sm-6">
						<div class="form-group">
							<label for="config_sabado_de">DE:</label>
							<input required type="time" name="config_sabado_de" id="config_sabado_de" data-mask="00:00" value="<?=(!empty($config_sabado) ? $sextoDiaHorarios[0] : '00:00');?>" class="form-control"/>	
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label for="config_sabado_ate">ATÉ:</label>
							<input required type="time" name="config_sabado_ate" id="config_sabado_ate" data-mask="00:00" value="<?=(!empty($config_sabado) ? $sextoDiaHorarios[1] : '00:00');?>" class="form-control"/>	
						</div>
					</div>
				</div>
			</div><!-- End wrapper_indent -->
			<?php
			if(!empty($config_domingo)):
				$setimoDia = explode(" ", $config_domingo);
				$setimoDiaHorarios = explode("-", $setimoDia[1]);

			endif;
			?>
			<div class="wrapper_indent">
				<input <?=(!empty($config_domingo) && $setimoDia[0] == 'on' ? 'checked' : '');?> id="dom-check" name="dom-check" type="checkbox"> <label for="dom-check"><strong style="color:#85c99d;">SELECIONE PARA ABRIR DIA DE DOMINGO</strong></label>
				<div class="row">						
					<div class="col-sm-6">
						<div class="form-group">
							<label for="config_domingo_de">DE:</label>
							<input required type="time" name="config_domingo_de" id="config_domingo_de" data-mask="00:00" value="<?=(!empty($config_domingo) ? $setimoDiaHorarios[0] : '00:00');?>" class="form-control"/>	
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label for="config_domingo_ate">ATÉ:</label>
							<input required type="time" name="config_domingo_ate" id="config_domingo_ate" data-mask="00:00" value="<?=(!empty($config_domingo) ? $setimoDiaHorarios[1] : '00:00');?>" class="form-control"/>	
						</div>
					</div>
				</div>
			</div><!-- End wrapper_indent -->
		</div>
	</div>
</div>

<hr />

<div class="indent_title_in">
	<i class="fa fa-calendar" aria-hidden="true"></i>
	<h3>Fechado na Data</h3>
	<p>
		Adicione exceções (ótimo para feriados etc.)
	</p>
</div>

<div class="panel-group" id="accordion">
	<div class="panel panel-default">
		<div style="background-color: #85c99d;color: #ffffff;" class="panel-heading">
			<h4 data-toggle="collapse" data-parent="#accordion" href="#collapse2" class="panel-title expand">
				<div class="right-arrow pull-right"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></div>
				<center><a style="color: #ffffff;" href="#">Clique aqui para adicionar uma data</a></center>
			</h4>
		</div>

		<div id="collapse2" class="panel-collapse collapse">
			<div class="panel-body">
				<div class="col-md-12 col-sm-12">
					<div class="form-group">
						<label for="datepicker">Inserir Data:</label>
						<input type="text" class="form-control" name='data_close' id="datepicker" data-mask="00/00/0000" placeholder="00/00/0000" />
					</div>					
					<label for="datepicker">Fechado nas Datas:</label><br />
					<?php
					$lerbanco->ExeRead("ws_datas_close", "WHERE user_id = :userid ORDER BY id ASC", "userid={$userlogin['user_id']}");
					if($lerbanco->getResult()):						
						foreach ($lerbanco->getResult() as $dadosC):
							extract($dadosC);		

							$i = explode('/', $data);
							$i = array_reverse($i);
							$i = implode("-", $i);							

							if(isDateExpired($i, 1)):
								?>

								<a title="Deletar" href="<?=$site.$Url[0].'/admin-loja&dellDate='.$id.'#sendempresa';?>">
									<button type="button" class="btn btn-danger">
										<strong><?=$data;?> = </strong> <span class="glyphicon glyphicon-trash"></span>
									</button>
								</a>
								<?php
							endif;
						endforeach;
					else:								
					endif;
					?>				
				</div>
			</div>


		</div>
	</div>

	<hr />

	<div class="indent_title_in">
		<i class="fa fa-share-square-o" aria-hidden="true"></i>
		<h3>Redes Sociais</h3>
		<p>
			Insira as urls de suas redes sociais!
		</p>
	</div>

	<div class="wrapper_indent">
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label for="facebook_status">Facebook Status:</label>
					<select required class="form-control" name="facebook_status">
						<?php 
						if(!empty($facebook_status) && $facebook_status == 2):
							echo "
							<option value=\"2\">Mostrar no Site</option>
							<option value=\"1\">Não Mostrar no Site</option>			
							";

						else:
							echo "
							<option value=\"1\">Não Mostrar no Site</option>
							<option value=\"2\">Mostrar no Site</option>	
							";
						endif;
						?>					
					</select>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="facebook_empresa">Facebook URL:</label>
					<input type="text" placeholder="https://www.facebook.com/Meu_Perfil" class="form-control" value="<?=(!empty($facebook_empresa) ? $facebook_empresa : "");?>" name="facebook_empresa" >
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label for="instagram_status">Instgram Status:</label>
					<select required class="form-control" name="instagram_status">
						<?php 
						if(!empty($instagram_status) && $instagram_status == 2):
							echo "
							<option value=\"2\">Mostrar no Site</option>
							<option value=\"1\">Não Mostrar no Site</option>			
							";

						else:
							echo "
							<option value=\"1\">Não Mostrar no Site</option>
							<option value=\"2\">Mostrar no Site</option>	
							";
						endif;
						?>
					</select>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="instagram_empresa">Instgram URL:</label>
					<input type="text" placeholder="https://www.instagram.com/Meu_Perfil" class="form-control" value="<?=(!empty($instagram_empresa) ? $instagram_empresa : "");?>" name="instagram_empresa" >
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label for="twitter_status">Twitter Status:</label>
					<select required class="form-control" name="twitter_status">
						<?php 
						if(!empty($twitter_status) && $twitter_status == 2):
							echo "
							<option value=\"2\">Mostrar no Site</option>
							<option value=\"1\">Não Mostrar no Site</option>			
							";

						else:
							echo "
							<option value=\"1\">Não Mostrar no Site</option>
							<option value=\"2\">Mostrar no Site</option>	
							";
						endif;
						?>
					</select>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="twitter_empresa">Twitter URL:</label>
					<input type="text" placeholder="https://twitter.com/Meu_Perfil" class="form-control" value="<?=(!empty($twitter_empresa) ? $twitter_empresa : "");?>" name="twitter_empresa" >
				</div>
			</div>
		</div>
	</div><!-- End wrapper_indent -->

	<div class="indent_title_in">
		<i class="icon_images"></i>
		<h3>Imagens de fundo e de Perfil</h3>
		<p>
			Imagens que serão usadas na página inicial do site!
		</p>
	</div>

	<div class="wrapper_indent add_bottom_45">

		<div class="form-group">
			<label>Imagem utilizada como banner de fundo no site:</label>
			<div class="input-file-container">  
				<input name="img_header" class="input-file" id="my-file" type="file" />
				<label tabindex="0" for="my-file" class="input-file-trigger">Enviar Imagem...</label>
			</div>
			<p class="file-return"></p>
			<br />
			<?=(!empty($img_header) ? "<spa style=\"color:#70bb0f;\">VOCÊ JÁ ENVIOU UMA IMAGEM!</span>" : "");?>
		</div>	

		<div class="form-group">
			<label>Imagem de perfil, será redimensionada em 240 X 240:</label>
			<div class="input-file-container">  
				<input name="img_logo" class="input-file" id="my-file" type="file" />
				<label tabindex="0" for="my-file" class="input-file-trigger">Enviar Imagem...</label>
			</div>
			<p class="file-return"></p>
			<br />
			<?=(!empty($img_logo) ? "<spa style=\"color:#70bb0f;\">VOCÊ JÁ ENVIOU UMA IMAGEM!</span>" : "");?>
		</div>
	</div><!-- End wrapper_indent -->
	<div class="wrapper_indent add_bottom_45">
	</div><!-- End wrapper_indent -->
	<hr />
	<div class="wrapper_indent">
		<input type="hidden" name="user_id" value="<?=$userlogin['user_id'];?>" />
		<input type="hidden" name="sendempresa" value="true" />
		<button type="input" class="btn_1">SALVAR ALTERAÇOES</button>
		<b style="float: right;color: green;font-weight: bold;">Data de Renovação: 
			<?php
			echo $dataFomatadarenovacao;
			?></b>
		</div><!-- End wrapper_indent -->
		<div class="panel-group" id="accordion">

		</form>

	</section><!-- End section 1 -->

</div>