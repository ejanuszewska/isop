
                <?php
session_start();

include '../lib/common.php';
include '../lib/db.php';
include '../lib/functions.php';
include '../lib/User.php';
include '../lib/rec.php';


include '401.php';


$user = User::getById($_SESSION['userId']);
ob_start();
?>
<div class="container">
  <div class="col-sm-6 col-xs-12">
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"
 method="post" id="reg">
  <div class="form-group"><label for="email">Adres email</label>
   <input type="text" name="email" id="email" class="form-control"
    value="<?php echo (isset($_POST['email']))? htmlspecialchars(
$_POST['email']) : $user->emailAddr; ?>"/>
  </div>
    <div class="form-group"><label for="password">Nowe hasło</label>
  <input type="password" name="password1" id="password1" class="form-control"/>
    </div>
   <div class="form-group"><label for="password2">Powtórz hasło</label>
  <input type="password" name="password2" id="password2" class="form-control"/>
   </div>
   <div class="form-group">
   <input type="submit" value="Zapisz" class="btn btn-default"/>
   <input type="hidden" name="submitted" value="1"/>
   </div>
</form></div></div>
<?php
$form = ob_get_clean();


if (!isset($_POST['submitted']))
{
    $GLOBALS['TEMPLATE']['content'] = $form;
}

else
{
    
    $password1 = (isset($_POST['password1']) && $_POST['password1']) ?
        sha1($_POST['password1']) : $user->password;
    $password2 = (isset($_POST['password2']) && $_POST['password2']) ?
        sha1($_POST['password2']) : $user->password;
    $password = ($password1 == $password2) ? $password1 : '';

 
    if (User::validateEmailAddr($_POST['email']) && $password)
    {
        $user->emailAddr = $_POST['email'];
        $user->password = $password;
        $user->save();

        $GLOBALS['TEMPLATE']['content'] = '<div class="container">
  <div class="col-sm-6 col-xs-12"><p><strong>Informacje ' .
            'w bazie danych zostały uaktualnione.</strong></p></div></div>';
    }

    else
    {
        $GLOBALS['TEMPLATE']['content'] .= '<div class="container">
  <div class="col-sm-6 col-xs-12"><p><strong>Podano nieprawidłowe ' .
            'dane.</strong></p></div></div>';
        $GLOBALS['TEMPLATE']['content'] .= $form;
    }
}

include '../templates/template-page.php';
?>
 <script>
            $(document).ready(function () {
                
            $('#reg').validate({
    rules: {
   
     email: {
        required: true,
        email: true
    },
     password1: {
        required: true,
        minlength: 6
    },
     password2: {
         required: true,
       equalTo: "#password1"
    }
   
    },
    success: function(label) {
      label.text('OK!').addClass('valid');
    }
  });
  jQuery.extend(jQuery.validator.messages, {
      email: 'Wprowadź prawidłowy adres e-mail',
    required: 'To pole jest wymagane',
    equalTo: "Wprowadź tę samą treść.",
    minlength: $.validator.format("Wprowadź co najmniej {0} znaków.")
  });

            });
            </script>