
<head>

    <link rel="stylesheet" type="text/css" media="screen" href="css.css" />



</head>
<body>
    <?php
    session_start();
    include("db.php");
    include("../lib/ogloszenie.php");
    include '../lib/common.php';
    include '../lib/functions.php';
    include '../lib/User.php';
    ?>

    <div class="jumbotron">
        <h1>Znajdź pracę!</h1>


        <p>
            Poznaj aktualne oferty pracy.
        </p>
        <form class="form-inline" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <div class="form-group">
                <input type="text" class="form-control" name="slowo_kluczowe" placeholder="Stanowisko"  value="<?php
    if (isset($_POST['slowo_kluczowe'])) {
        echo htmlspecialchars($_POST['slowo_kluczowe']);
    }
    ?>" />
            </div>
            <?php
            
                   

            
            ?>
            
            
           
                
                <?php 
            
            if (!isset($_POST['branza'])) { ?>
             <div class="form-group">
                    <select name="branza[]" multiple="multiple" class="form-control"  id='branza' style='min-height: 1px;'><?php
                    $sql2 = "SELECT * FROM branza";
                    $result2 = mysql_query($sql2);

                    while ($row = mysql_fetch_assoc($result2)) {
                        echo "<option value={$row['nazwa_branzy']}>{$row['nazwa_branzy']}</option>";
                    }
        ?>
                    </select>
                </div>
            <?php
                } else {
                    
            ?>
            <div class="form-group">
                    <select name="branza[]" multiple="multiple" class="form-control"  id='branza'>
                        
                        <?php
                        
                        $sql2 = "SELECT * FROM branza";
                        
                        $result2 = mysql_query($sql2);
                        
                        while ($row = mysql_fetch_assoc($result2)) {
                            echo "<option value=\"{$row['nazwa_branzy']}\"";
                            
                            if (in_array($row['nazwa_branzy'], $_POST['branza'])) {
                                echo " selected='selected'>{$row['nazwa_branzy']}</option>";
                            } else {
                                echo ">{$row['nazwa_branzy']}</option>";
                            }
                        }
                        echo "</select></div>";
                    }
                
                ?>
                <div class="form-group">

                    <input class='form-control' id="city" name="miasto" autocomplete="off" data-country="pl" placeholder='Województwo lub miasto'  value="<?php
                if (isset($_POST['miasto'])) {
                    echo htmlspecialchars($_POST['miasto']);
                }
                ?>"/></div>
                <div class="form-group ">
                    <button class="btn btn-primary" type="submit">Szukaj</button>
                </div>
        </form>
    </p>
</div>




<?php
if (!isset($_POST["miasto"])) {
    $form = ob_get_clean();
    $GLOBALS['TEMPLATE']['content'] .= $form;

    $sql = 'SELECT * FROM ogloszenie ORDER BY id_ogloszenia DESC';
    $GLOBALS['TEMPLATE']['content'] .= '<h2>Najnowsze oferty</h2>';
} else {
    $form = ob_get_clean();
    $GLOBALS['TEMPLATE']['content'] .= $form;
    //$sql = 'SELECT * FROM ogloszenia WHERE nazwa="' . $_POST["query"] . '"';
    $form = ob_get_clean();
    $GLOBALS['TEMPLATE']['content'] .= $form;
    $lokalizacja = $_POST['miasto'];
    $stanowisko = $_POST['slowo_kluczowe'];
    $branza = $_POST['branza'];
    $sql = Ogloszenie::filtruj($lokalizacja, $branza, $stanowisko);
    $GLOBALS['TEMPLATE']['content'] .= '<h2>Wyniki wyszukiwania:</h2>';
}



$GLOBALS['TEMPLATE']['content'] .= '<table class="table table-hover">';


//$result = mysql_query($sql);
//$total = mysql_num_rows($result);
$q = mysql_query($sql);
$total = mysql_num_rows($q);

while ($row = mysql_fetch_array($q)) {
    $ogl = Ogloszenie::getById($row['id_ogloszenia']);
    $r = $ogl->wyswietl(true);
    $GLOBALS['TEMPLATE']['content'] .= $r[1];
}
$perPage = 10;

$GLOBALS['TEMPLATE']['content'] .= '</table>';

$i = 0;

$GLOBALS['TEMPLATE']['content'] .= '<div><div class="btn-group" role="group">';
for ($i = 1; $i < $total / $perPage + 1; $i++) {
    $GLOBALS['TEMPLATE']['content'] .= '<button class="btn btn-default" onclick="showPage(' . $i . ')">' . $i . '</button> ';
}
$GLOBALS['TEMPLATE']['content'] .= '</div></div>';




include '../templates/template-page.php';
?>
<script>
    $(document).ready(function () {
        showPage = function (page) {
            pageSize = 10;
            $(".wpis").hide();
            $(".wpis").each(function (n) {
                if (n >= pageSize * (page - 1) && n < pageSize * page)
                    $(this).show();
            });
        }
        showPage(1);
        $('input#city').cityAutocomplete();
        $(function () {

        });
    }

    );


</script>

<script type="text/javascript">
    $(document).ready(function () {
        $('#branza').multiselect({
            nonSelectedText: 'Wybierz branżę',
            buttonWidth: '150px',
            allSelectedText: 'Wszystkie'
        });
    });
</script>
