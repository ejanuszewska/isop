<html lang="pl">
  <head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" >
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../lib/multiselect/jquery.multiselect.css"/>
        <script type='text/javascript' src='jquery-1.3.2.js'></script>
        <script type='text/javascript' src='jquery-1.8.0.min.js'></script>
        <script type='text/javascript' src='jquery-1.11.2.min.js'></script>
        <script src='../lib/jquery.validate.min.js' type='text/javascript' charset='utf-8'></script>
<script src='../lib/multiselect/jquery.multiselect.js' type='text/javascript' charset='utf-8'></script>
<script src='../lib/jquery.jeditable.mini.js' type='text/javascript' charset='utf-8'></script>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?libraries=places&language=pl"></script>

<script type="text/javascript" src="../lib/dist/js/bootstrap-multiselect.js"></script>
<link rel="stylesheet" href="../lib/dist/css/bootstrap-multiselect.css" type="text/css"/>

<link rel="stylesheet" href="../lib/City/city-autocomplete.css"/>
<link href="../lib/magic/magicsuggest.css" rel="stylesheet"/>

<script src="../lib/City/jquery.city-autocomplete.js"></script>

  <title>
<?php
if (!empty($GLOBALS['TEMPLATE']['title']))
{
    echo $GLOBALS['TEMPLATE']['title'];
}
?>
</title>
    <?php
    if (!empty($GLOBALS['TEMPLATE']['extra_head']))
    {
        echo $GLOBALS['TEMPLATE']['extra_head'];
    }
    ?>
    <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="../lib/css/bootstrap.min.css" type="text/css"/>

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.9.4/css/bootstrap-select.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.9.4/js/bootstrap-select.min.js"></script>


   
<style>
 
    
    .umiejetnosci{
    margin-bottom: 5px;
}

.umiejetnosci .name{
    font-weight: bold;
    color: #333;
}
.umiejetnosci .prop{
    float: left;
    width: 50%;
}
.umiejetnosci .prop .lbl{
    font-size: 11px;
    line-height: 11px;
    float: left;
    color: #AAA;
    margin-left: 25px;
    margin-right: 5px;
}
.umiejetnosci .prop .val{
    font-size: 11px;
    line-height: 11px;
    color: #666;
}

.ms-sel-item img{
    float: left;
    position: relative;
    top: 3px;
    margin-right: 3px;
}
.ms-sel-item .name{
    float: left;
}
      #footer {
        padding-top: 30px;
      }
 
      .navbar {
        border-radius: 0 !important;
      }
      
      .g{
          background-color: #f5f5f5;
          
          padding: 10px 15px;
    border-bottom: 1px solid white;
   
       
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#fff5f5f5', endColorstr='#ffe8e8e8', GradientType=0);
    background-repeat: repeat-x;
        font-size: 16px;
    
      }
      
           .c{
          background-color: white;
          
          padding: 10px 15px;
    border-bottom: 1px solid white;
   
       
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#fff5f5f5', endColorstr='#ffe8e8e8', GradientType=0);
    background-repeat: repeat-x;
        font-size: 16px;
    
      }
      .inform{
          border-color: white;
          outline: none;
          border: 0px;
         transition: width 0s;
         border-radius: 0px;
         box-shadow: none;
      }
      
      .t{
          width:100%;
      }

.form-inline > * {
   margin:5px 3px;
}

#footer{
    
    color:white;
    bottom:0px;
}

.footer {
  position: absolute;
  bottom: 0;
  width: 100%;
  /* Set the fixed height of the footer here */
  height: 60px;
  background-color: #2c3e50;
  color:white;
  text-align: center;
 
}

body {
  /* Margin bottom by footer height */
  margin-bottom: 60px;
}
html {
  position: relative;
  min-height: 100%;
}
    </style>
 
  </head>
  <body>
 
<nav class="navbar navbar-default">
  <div class="container">
    <div class="navbar-header">
      <a class="navbar-brand" href="index.php">
         Ogłoszenia
      </a>
    </div>
   
 
    <ul class="nav navbar-nav navbar-right">
        <?php
         if (isset($_SESSION['userId'])){
      echo '<li><a href="profil.php">Profil</a></li>';
      echo '<li><a href="lista_aplikacji.php">Twoje aplikacje</a></li>';
         }
      if (isset($_SESSION['rId'])){
           echo '<li><a href="profil_rekrutera.php">Profil</a></li>';
      echo '<li><a href="panel_rek.php">Panel rekrutera</a></li>';
      echo    '<li><a href="dodajogloszenie.php">Dodaj ogłoszenie</a></li>';
      }
      if (!isset($_SESSION['rId']) AND !isset($_SESSION['userId'])){
      echo '<li><a href="register.php">Rejestracja</a></li>';
     echo '<li><a href="login.php">Logowanie</a></li>';
      }
     else{
      echo '<li><a href="logout.php">Wyloguj</a></li>';
     }
     ?>
    </ul>
  </div>
</nav>
 
<div id="content" class="container">
         
        <?php
        if (!empty($GLOBALS['TEMPLATE']['content']))
        {
            echo $GLOBALS['TEMPLATE']['content'];
        }
        ?>
</div>
 
 

  <div class="footer">
    <h6>Copyright &#169;<?php echo date('Y'); ?><h6>
  </div>

 
 
  </body>
</html>
<link href="../lib/magic/magicsuggest.css" rel="stylesheet"/>
