<?php
if(!isset($_SESSION['administrador'])):
    header("Location: " . HOME);
    die;
endif;

//INSTANCIA AS CLASSES
$lerbanco = new Read;
$updatebanco = new Update;
$deletbanco = new Delete;
$createbanco = new Create;

$site = HOME; // Adiciona a variável $site igual a landing page
?>

<div class="row">
    <div class="col-md-12">
        <div class="white-box">
            <div class="main_title margin_mobile">
                <h3 class="box-title">Criar Nova Loja</h3>
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
                                    <label for="nome_empresa_link"><?=HOME;?></label>
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
                                    <input type="tel" required autocomplete="off" id="user_telefone" name="user_telefone" class="form-control telefone" placeholder="(99) 99999-9999" maxlength="15">
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
                                <?php
                                $lerbanco->ExeRead("ws_planos", "WHERE plano_status = :stats", "stats=1");
                                if($lerbanco->getResult()):
                                    foreach($lerbanco->getResult() as $planos):
                                        extract($planos);
                                ?>
                                        <option value="<?=$plano_id;?>"><?=$plano_nome;?> - R$ <?=number_format($plano_valor, 2, ',', '.');?></option>
                                <?php
                                    endforeach;
                                endif;
                                ?>
                            </select>
                        </div>

                        <div id="pass-info" class="clearfix"></div>

                        <hr style="border-color:#ddd;">

                        <div class="text-center">
                            <input type="hidden" name="empresa_status" value="true">
                            <button type="button" id="cadastrarUser" class="btn btn-success">Cadastrar Minha Loja</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function(){
    // Verificar link
    $('#verificarDisponibilidadeLink').click(function(){
        var linkuser = $('#nome_empresa_link').val();
        
        if(linkuser == ''){
            x0p('Opss...', 
                'Antes preencha o campo link!',
                'error', false);
            return false;
        }

        $.ajax({
            url: '<?=$site;?>includes/verificalink.php',
            method: 'post',
            data: {'linkuser' : linkuser},
            success: function(data){
                if(data == 'true'){
                    x0p('Que pena!', 
                        'Este link já esta sendo usado!',
                        'error', false);
                }else{
                    x0p('Muito bom!', 
                        'Este link está disponível!',
                        'success', false);
                }
            }
        });
    });

    // Carregar estados
    $.getJSON('<?=$site;?>estados_cidades.json', function(data){
        var items = [];
        items.push('<option value="">Selecione o Estado</option>');
        $.each(data.estados, function(key, val){
            items.push('<option value="' + val.sigla + '">' + val.nome + '</option>');
        });
        $("#estados").html(items.join(''));
    });

    // Carregar cidades 
    $("#estados").change(function(){
        var estado = $(this).val();
        $.getJSON('<?=$site;?>estados_cidades.json', function(data){
            var items = ['<option value="">Selecione a Cidade</option>'];
            $.each(data.estados, function(key, val){
                if(val.sigla == estado){
                    $.each(val.cidades, function(cidadeKey, cidadeVal){
                        items.push('<option value="' + cidadeVal + '">' + cidadeVal + '</option>');
                    });
                }
            });
            $("#cidades").html(items.join(''));
        });
    });

    // Cadastrar
    $('#cadastrarUser').click(function(){
        var user_password = $('#user_password').val();
        var user_password2 = $('#user_password2').val();

        if(user_password != user_password2){
            x0p('Opss...', 
                'As senhas não conferem!',
                'error', false);
            return false;
        }

        $.ajax({
            url: '<?=$site;?>includes/processacadastro.php',
            method: 'post',
            data: $('#formCadastro').serialize(),
            success: function(data){
                if(data == 'true'){
                    x0p('Sucesso!', 
                        'Dados cadastrados! Você será redirecionado ao seu painel em instantes!',
                        'success', false);

                    setTimeout(function() {
                        window.location.href = '<?=$site;?>admin/painel.php?exe=home';
                    }, 3000);
                }else{
                    x0p('Opss...', 
                        data,
                        'error', false);
                }
            }
        });
    });
});
</script>
