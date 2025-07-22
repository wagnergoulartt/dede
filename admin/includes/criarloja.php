<?php
require_once '../_app/Config.inc.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$texto['titulo_site_landing'];?></title>
    
    <!-- CSS -->
    <link href='https://fonts.googleapis.com/css?family=Lato:400,700,900,400italic,700italic,300,300italic' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Gochi+Hand' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="<?=$site;?>css/base.css">
    <link rel="stylesheet" href="<?=$site;?>css/style.css">
</head>
<body>

<div id="cadastrar" class="container margin_60">
    <div class="main_title margin_mobile">

    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <form id="formCadastro" autocomplete="off" method="post">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nome_empresa">Nome da Loja</label>
                            <input type="text" autocomplete="off" id="nome_empresa" name="nome_empresa" class="form-control" required placeholder="Nome da Loja">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nome_empresa_link"><?=$site;?></label>
                            <input type="text" autocomplete="off" id="nome_empresa_link" name="nome_empresa_link" class="form-control" required placeholder="/ Use maiúsculas, minúsculas e underline.">
                            <a class="btn btn-success btn-xs" id="verificarDisponibilidadeLink" style="color: #ffffff;cursor: pointer;margin-top: 5px;"><strong> verificar Disponibilidade </strong></a>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="estados">Estado</label>
                            <select required class="form-control" name="end_uf_empresa" id="estados">     
                            </select>    
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="cidades">Cidade</label>
                            <select required class="form-control" name="cidade_empresa" id="cidades">    
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="end_bairro_empresa">Bairro</label>
                            <input type="text" autocomplete="off" id="end_bairro_empresa" required name="end_bairro_empresa" class="form-control" placeholder="Bairro...">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="end_rua_n_empresa">Rua / Nº</label>
                            <input type="text" autocomplete="off" id="end_rua_n_empresa" required name="end_rua_n_empresa" class="form-control" placeholder="Rua e Nº">
                        </div>
                    </div>
                </div>

                <hr />

                <div class="row">
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <label for="user_name">Nome</label>
                            <input type="text" required autocomplete="off" class="form-control" id="user_name" name="user_name" placeholder="Seu Nome">
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <label for="user_lastname">Sobrenome</label>
                            <input type="text" required autocomplete="off" class="form-control" id="user_lastname" name="user_lastname" placeholder="Seu Sobrenome">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <label for="user_email">E-mail:</label>
                            <input type="email" required autocomplete="off" id="user_email" name="user_email" class="form-control" placeholder="E-mail">
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <label for="user_telefone">Telefone para contato:</label>
                            <input type="tel" required autocomplete="off" id="user_telefone" name="user_telefone" class="form-control" placeholder="(99) 99999-9999" data-mask="(00) 00000-0000" maxlength="15">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="user_password">Senha</label>
                            <input type="password" required autocomplete="off" class="form-control" placeholder="*******" name="user_password" id="user_password" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="user_password2">Repita a Senha</label>
                            <input type="password" required autocomplete="off" class="form-control" placeholder="*******" name="user_password2" id="user_password2" />
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Escolha seu plano</label>
                    <select name="user_plano" class="form-control" required>
                        <option value="">Selecione um Plano</option>
                        <option value="1"><?=$texto['nomePlanoUm'];?></option>
                        <option value="2"><?=$texto['nomePlanoDois'];?></option>
                        <option value="3"><?=$texto['nomePlanoTres'];?></option>
                    </select>
                </div>

                <div class="text-center">
                    <input type="hidden" name="empresa_status" value="true">
                    <button type="button" id="cadastrarUser" class="btn_full_outline">Cadastrar Minha Loja</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="<?=$site;?>js/jquery-2.2.4.min.js"></script>
<script src="<?=$site;?>js/common_scripts_min.js"></script>
<script src="<?=$site;?>assets/validate.js"></script>
<script src="<?=$site;?>js/jquery.mask.js"></script>
<script src="<?=$site;?>assets/sweetalert.min.js"></script>

<script type="text/javascript">
    // Script para verificar disponibilidade do link
    $(document).ready(function(){
        $('#verificarDisponibilidadeLink').click(function(){
            var linkuser = $('#nome_empresa_link').val();

            if(linkuser == ''){
                x0p('Opss...', 
                    'Antes preencha o campo!',
                    'error', false);
            }else{
                $.ajax({
                    url: '<?=$site?>controlers/processaverificadisponibilidadelink.php',
                    method: 'post',
                    data: {'linkuser' : linkuser},
                    success: function(data){
                        if(data == 'true'){
                            x0p('Que pena! 😔', 
                                'Esse link não está disponível!',
                                'error', false);
                        }else{
                            $('#nome_empresa_link').val(data);
                            x0p('Muito bom! 😍', 
                                '<?=$site;?>'+data+' está disponível!', 
                                'ok', false);
                        }          
                    }
                });
            }
        });
    });

    // Script para carregar estados e cidades
    $(document).ready(function () {
        $.getJSON('<?=$site;?>estados_cidades.json', function (data) {
            var items = [];
            var options = '<option value="">Escolha um estado</option>';  

            $.each(data, function (key, val) {
                options += '<option value="' + val.sigla + '">' + val.sigla + '</option>';
            });         
            $("#estados").html(options);        

            $("#estados").change(function () {        
                var options_cidades = '<option value="">Escolha uma Cidade</option>';
                var str = "";         

                $("#estados option:selected").each(function () {
                    str += $(this).text();
                });

                $.each(data, function (key, val) {
                    if(val.sigla == str) {              
                        $.each(val.cidades, function (key_city, val_city) {
                            options_cidades += '<option value="' + val_city + '">' + val_city + '</option>';
                        });             
                    }
                });

                $("#cidades").html(options_cidades);
            }).change();    
        });
    });

    // Script para processar o cadastro
    $(document).ready(function(){
        $("#cadastrarUser").click(function(){
            $(this).html('<i class="icon-spin5 animate-spin"></i> AGUARDE...');
            $(this).prop('disabled', true);

            $.ajax({
                url: '<?=$site;?>controlers/processaCadastroUser.php',
                method: 'post',
                data: $('#formCadastro').serialize(),
                success: function(data){
                    if(data == "erro1"){
                        x0p('Opsss', 'Preencha todos os campos!', 'error', false);
                    }else if(data == "erro2"){
                        x0p('Opsss', 'O E-mail informado e inválido!', 'error', false);
                    }else if(data == "erro3"){
                        x0p('Opsss', 'A senha informada deve ter no mínimo 8 caracteres!', 'error', false);
                    }else if(data == "erro4"){
                        x0p('Opsss', 'As senhas não coincidem!', 'error', false);
                    }else if(data == "erro5"){
                        x0p('Opsss', 'Esse link não está disponível!', 'error', false);
                    }else if(data == "erro6"){
                        x0p('Opsss', 'Já existe uma conta com esses dados!', 'error', false);
                    }else if(data == "erro0"){
                        x0p('Opsss', 'OCORREU UM ERRO AO CADASTRAR!', 'error', false);
                    }else{
                        x0p('Sucesso!', 'Agora você pode fazer login.', 'ok', false);
                    }
                    $('#cadastrarUser').html('Cadastrar Minha Loja');
                    $('#cadastrarUser').prop('disabled', false);
                }
            });
        }); 
    });

    // Máscaras
    $(document).ready(function(){
        $('.telefone').mask('(00) 0 0000-0000');
        $('.estado').mask('AA');
        $('.numero').mask('#########0');
    });
</script>

</body>
</html>
