
                <?php

include '../lib/common.php';
include '../lib/db.php';
include '../lib/functions.php';
include '../lib/User.php';
include '../lib/rec.php';
require '../lib/PHPMailer-master/PHPMailerAutoload.php';
 
session_start();


header('Cache-control: private');
 

ob_start();
?>
<div class="container">
  <div class="col-sm-6 col-xs-12">
<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" id="reg">
  <div class="form-group">
     <label for="email">Adres email</label>
     <input class="form-control" type="text" name="email" id="email" placeholder="Email" value="<?php if (isset($_POST['email'])) echo htmlspecialchars($_POST['email']); ?>"/>
   </div>
  <div class="form-group">
   <label for="password1">Hasło</label>
   <input class="form-control" type="password" name="password1" placeholder="Hasło" id="password1" value=""/>
  </div>
  <div class="form-group">
    <label for="password2">Powtórz hasło</label>
    <input class="form-control" type="password" name="password2" placeholder="Powtórz hasło" id="password2" value=""/>
   </div>
  <div class="form-group">
   <label for="captcha">Weryfikacja</label>
   Wpisz tekst widoczny na obrazku<br/>
   <img src="img/captcha.php?nocache=<?php echo time(); ?>" alt=""/>
   <input class="form-control" type="text" name="captcha" id="captcha"/>
  </div>
  <div class="form-group">
    <div class="radio">
      <label>
    <input type="radio" name="user" value="user" checked="checked" />
    Poszukujący pracy
  </label>
    </div>
    <div class="radio">
      <label>
    <input type="radio" name="user" value="recruiter"/>
    Rekruter
  </label>
    </div>
  </div>
  <div class="form-group">
   <input class="btn btn-default" type="submit" value="Zarejestruj"/>
   <input type="hidden" name="submitted" value="1"/>
  </div>
</form>
</div>
</div>
<?php
$form = ob_get_clean(); 
 

if (!isset($_POST['submitted']))
{
    $GLOBALS['TEMPLATE']['content'] = $form;
}
 

else
{
    
    $password1 = (isset($_POST['password1'])) ? $_POST['password1'] : '';
    $password2 = (isset($_POST['password2'])) ? $_POST['password2'] : '';
    $password = ($password1 && $password1 == $password2) ?
        sha1($password1) : '';
    $userType=$_POST['user'];
 

    $captcha = (isset($_POST['captcha']) && 
        strtoupper($_POST['captcha']) == $_SESSION['captcha']);
 

    if ($password &&
        $captcha &&
        $userType=='user' &&    
        User::validateEmailAddr($_POST['email']))
    {
      
        $user = User::getByUsermail($_POST['email']);
        if ($user->userId)
        {
            $GLOBALS['TEMPLATE']['content'] = '<div class="container"><div class="col-md-6 col-xs-12"><p><strong>Przepraszamy, ' .
                'takie konto już istnieje.</strong></p> <p>Prosimy podać ' .
                'inny e-mail.</p>';
            $GLOBALS['TEMPLATE']['content'] .= $form;
            $GLOBALS['TEMPLATE']['content'] .= '</div></div>';
        }
        else
        {
          
            $u = new User();
            $u->emailAddr = $_POST['email'];
            $u->password = $password;
            $token = $u->setInactive();
            
            

$mail = new PHPMailer;
$mail->CharSet = 'UTF-8';
$mail->isSMTP();                                     
$mail->Host = 'smtp.poczta.onet.pl';  
$mail->SMTPAuth = true;                               
$mail->Username = 'ejanuszewska@onet.pl';                 
$mail->Password = 'Przemek1';                         
$mail->SMTPSecure = 'ssl';                            
$mail->Port = 465; 

$mail->From = 'ejanuszewska@onet.pl';
$mail->FromName = 'Mailer';
$mail->addAddress($u->emailAddr);     
$mail->addReplyTo('ejanuszewska@onet.pl', 'Information');


$mail->WordWrap = 50;                                 
$mail->isHTML(true);                                 

$mail->Subject = 'Potwierdzenie rejestracji';
$mail->Body    = '<p><strong>Dziękujemy za ' .
                'zarejestrowanie się.</strong></p> <p>Należy pamiętać o ' .
                'zweryfikowaniu konta i kliknąć łącze <a href="http://ogloszeniapraca.cba.pl/public_files/verify.php?uid=' .
                $u->userId . '&token=' . $token . '">http://ogloszeniapraca.cba.pl/public_files/verify.php?uid=' .
                $u->userId . '&token=' . $token . '</a></p>';
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

if(!$mail->send()) {
    $GLOBALS['TEMPLATE']['content'] = 'Message could not be sent.';
    
} else {
    $GLOBALS['TEMPLATE']['content'] = '<div class="container"><div class="col-md-6 col-xs-12">Na Twój adres e-mail została wysłana wiadomość z linkiem aktywacyjnym. Kliknij link, aby zakończyć rejestrację.</div></div>';
}
       
             
         
         }
    }
    elseif($password &&
        $captcha &&
        $userType=='recruiter' &&    
        Rekruter::validateEmailAddr($_POST['email'])){
         
         $rekruter = Rekruter::getByUsermail($_POST['email']);
        if ($rekruter->rId)
        {
            $GLOBALS['TEMPLATE']['content'] = '<div class="container">
  <div class="col-sm-6 col-xs-12"><p><strong>Przepraszamy, ' .
                'takie konto już istnieje.</strong></p> <p>Prosimy podać ' .
                'inny e-mail.</p></div></div>';
            $GLOBALS['TEMPLATE']['content'] .= $form;
        }
        else{
            $r = new Rekruter();
            $r->adresEmail = $_POST['email'];
            $r->haslo = $password;
            $token = $r->setInactive();
 
           $mail = new PHPMailer;

$mail->isSMTP();      
$mail->CharSet = 'UTF-8';
$mail->Host = 'smtp.poczta.onet.pl';  
$mail->SMTPAuth = true;                               
$mail->Username = 'ejanuszewska@onet.pl';                
$mail->Password = 'Przemek1';                          
$mail->SMTPSecure = 'ssl';                           
$mail->Port = 465; 

$mail->From = 'ejanuszewska@onet.pl';
$mail->FromName = 'Mailer';
$mail->addAddress($r->adresEmail);    
$mail->addReplyTo('ejanuszewska@onet.pl', 'Information');


$mail->WordWrap = 50;                             
$mail->isHTML(true);                                 

$mail->Subject = 'Potwierdzenie rejestracji';
$mail->Body    = '<p><strong>Dziękujemy za ' .
                'zarejestrowanie się.</strong></p> <p>Należy pamiętać o ' .
                'zweryfikowaniu konta i kliknąć łącze <a href="http://ogloszeniapraca.cba.pl/public_files/verifyrec.php?rid=' .
                $r->rId . '&token=' . $token . '">http://ogloszeniapraca.cba.pl/public_files/verifyrec.php?rid=' .
                $r->rId . '&token=' . $token . '</a></p>';
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

if(!$mail->send()) {
    $GLOBALS['TEMPLATE']['content'] = 'Message could not be sent.';
    
} else {
    $GLOBALS['TEMPLATE']['content'] = '<div class="container"><div class="col-md-6 col-xs-12">Na Twój adres e-mail została wysłana wiadomość z linkiem aktywacyjnym. Kliknij link, aby zakończyć rejestrację.</div></div>';
}
       
             
         
        }
         
    }

    else
    {
        $GLOBALS['TEMPLATE']['content'] .= '<div class="container">
  <div class="col-sm-6"><p><strong>Podano nieprawidłowy kod weryfikacyjny ' .
            '</strong></p> <p>Prosimy prawidłowo wypełnić ' .
            'wszystkie pola, abyśmy mogli zarejestrować konto użytkownika.</p></div></div>';
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
     captcha: {
        required: true,
     
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