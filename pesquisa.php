<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
 <style>
/*.frmSearch {border: ;background-color: ;margin: ;padding:;border-radius:;}*/
#lista{float:left;list-style:none;margin-top:-3px;padding:0;width:190px;position: absolute;}
#lista li{padding: 10px; background: #f0f0f0; border-bottom: #bbb9b9 1px solid;}
#lista li:hover{background:#ece3d2;cursor: pointer;}
/*#search-box{padding: ;border:;border-radius:;}*/
</style>
<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>


<!--**********************************************************-->

<script>
$(document).ready(function(){
    $("#search-colaborador").keyup(function(){
        $.ajax({
        type: "POST",
        url: "preencheColaborador.php",
        data:'keyword='+$(this).val(),
        beforeSend: function(){
            $("#search-colaborador").css("background","#FFF url(Loader images/Icon.gif) no-repeat 165px");
        },
        success: function(data){
            $("#suggesstion-colaborador").show();
            $("#suggesstion-colaborador").html(data);
            $("#search-colaborador").css("background","#FFF");
        }
        });
    });
});    


$(document).ready(function(){
    $("#search-fornecedor").keyup(function(){
        $.ajax({
        type: "POST",
        url: "preencheFornecedor.php",
        data:'keyword='+$(this).val(),
        beforeSend: function(){
            $("#search-fornecedor").css("background","#FFF url(Loader images/Icon.gif) no-repeat 165px");
        },
        success: function(data){
            $("#suggesstion-fornecedor").show();
            $("#suggesstion-fornecedor").html(data);
            $("#search-fornecedor").css("background","#FFF");
        }
        });
    });
});   
    
    
    
$(document).ready(function(){
    $("#search-cliente").keyup(function(){
        $.ajax({
        type: "POST",
        url: "preencheCliente.php",
        data:'keyword='+$(this).val(),
        beforeSend: function(){
            $("#search-cliente").css("background","#FFF url(Loader images/Icon.gif) no-repeat 165px");
        },
        success: function(data){
            $("#suggesstion-cliente").show();
            $("#suggesstion-cliente").html(data);
            $("#search-cliente").css("background","#FFF");
        }
        });
    });
});

$(document).ready(function(){
    $("#search-usuario").keyup(function(){
        $.ajax({
        type: "POST",
        url: "preencheUsuario.php",
        data:'keyword='+$(this).val(),
        beforeSend: function(){
            $("#search-usuario").css("background","#FFF url(Loader images/Icon.gif) no-repeat 165px");
        },
        success: function(data){
            $("#suggesstion-usuario").show();
            $("#suggesstion-usuario").html(data);
            $("#search-usuario").css("background","#FFF");
        }
        });
    });
});

$(document).ready(function(){
    $("#search-documento").keyup(function(){
        $.ajax({
        type: "POST",
        url: "preencheDocumento.php",
        data:'keyword='+$(this).val(),
        beforeSend: function(){
            $("#search-documento").css("background","#FFF url(Loader images/Icon.gif) no-repeat 165px");
        },
        success: function(data){
            $("#suggesstion-documento").show();
            $("#suggesstion-documento").html(data);
            $("#search-documento").css("background","#FFF");
        }
        });
    });
});

$(document).ready(function(){
    $("#search-processo").keyup(function(){
        $.ajax({
        type: "POST",
        url: "preencheProcesso.php",
        data:'keyword='+$(this).val(),
        beforeSend: function(){
            $("#search-processo").css("background","#FFF url(Loader images/Icon.gif) no-repeat 165px");
        },
        success: function(data){
            $("#suggesstion-processo").show();
            $("#suggesstion-processo").html(data);
            $("#search-processo").css("background","#FFF");
        }
        });
    });
});

$(document).ready(function(){
    $("#search-orcamento").keyup(function(){
        $.ajax({
        type: "POST",
        url: "preencheOrcamento.php",
        data:'keyword='+$(this).val(),
        beforeSend: function(){
            $("#search-orcamento").css("background","#FFF url(Loader images/Icon.gif) no-repeat 165px");
        },
        success: function(data){
            $("#suggesstion-orcamento").show();
            $("#suggesstion-orcamento").html(data);
            $("#search-orcamento").css("background","#FFF");
        }
        });
    });
});


function selecionaColaborador(val, val1, val2) {
$("#search-colaborador").val(val);
$("#search-idcolaborador").val(val1);
$("#search-tipocolaborador").val(val2);
$("#suggesstion-colaborador").hide();
}
function selecionaFornecedor(val, val1, val2) {
$("#search-fornecedor").val(val);
$("#search-idfornecedor").val(val1);
$("#search-tipofornecedor").val(val2);
$("#suggesstion-fornecedor").hide();
}
function selecionaCliente(val,val1, val2) {
$("#search-cliente").val(val);
$("#search-idcliente").val(val1);
$("#search-tipocliente").val(val2);
$("#suggesstion-cliente").hide();
}
function selecionaUsuario(val, val1) {
$("#search-usuario").val(val);
$("#search-idusuario").val(val1);
$("#suggesstion-usuario").hide();
}
function selecionaDocumento(val, val1) {
$("#search-documento").val(val);
$("#search-idOrdemServico").val(val1);
$("#suggesstion-documento").hide();
}
function selecionaProcesso(val, val1) {
$("#search-processo").val(val);
$("#search-idOS").val(val1);
$("#suggesstion-processo").hide();
}

function selecionaOrcamento(val, val1) {
$("#search-orcamento").val(val);
$("#search-idOrcamento").val(val1);
$("#suggesstion-orcamento").hide();
}

</script>
   
    
    
    <body>
        <?php
        
        ?>
    </body>
</html>
