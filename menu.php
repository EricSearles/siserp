<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
            
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <?php
        if($_SESSION['empresa']=='1'){
            echo 
            '<a class="navbar-brand" href="administrativo.php"><img src ="images/mveiga-arquitetura-logo1.fw.png" width="50" />MVeiga Arquitetura</a><br/><br/>';
        }elseif($_SESSION['empresa']=='2'){
            echo 
            '<a class="navbar-brand" href="administrativo.php"><img src ="images/logo-tcassessoria.fw.png" width="50" />TC Assessoria</a><br/><br/>';
  
        }
        ?>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
        
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
          <a class="nav-link" href="administrativo.php">Início <span class="sr-only">(current)</span></a>
      </li>

      <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="administrativo.php" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Cadastros
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="usuario.php?pg=">Usuários</a>
          <a class="dropdown-item" href="permissao.php?pg=">Permissões</a>
<!--  coloca uma linha separando        <div class="dropdown-divider"></div>-->
          <a class="dropdown-item" href="servicoDisponibilizado.php?pg=0">Serviços Oferecidos</a>
          <a class="dropdown-item" href="produtoDisponibilizado.php?pg=0">Produtos Oferecidos</a>
          <a class="dropdown-item" href="formaPagamento.php?pg=">Formas de Pagamento</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="cliente.php?pg=0">Clientes</a>
          <a class="dropdown-item" href="colaborador.php?pg=0">Solicitantes</a>
          <a class="dropdown-item" href="fornecedor.php?pg=0">Fornecedores</a>
        </div>
      </li>
      
       <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="administrativo.php" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Serviços
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="orcamento.php?pg=0">Orçamentos</a>
          <a class="dropdown-item" href="orcamento_1.php?pg=0">Orçamentos Antigos</a>
          <a class="dropdown-item" href="ordemServico.php?pg=0">Ordem de Serviço</a>
        </div>
      </li>
      
            <li class="nav-item">
        <a class="nav-link" href="relatorio.php">Relatórios</a>
      </li>
            <li class="nav-item">
        <a class="nav-link" href="dadosUsuario.php">Meus Dados</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="?agenda=1">Agenda</a>
      </li>
            <li class="nav-item">
        <a class="nav-link" href="sair.php">Sair</a>
      </li>
<!--      <li class="nav-item">
        <a class="nav-link disabled" href="#">Disabled</a>
      </li>-->
    </ul>
<!--    <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="search" placeholder="Buscar" aria-label="Search">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Buscar</button>
    </form>-->
  </div>
</nav>
    </body>
</html>
