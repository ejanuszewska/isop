<?php
session_start();

include '../lib/common.php';
include '../lib/db.php';
include '../lib/functions.php';
include '../lib/User.php';
include '../lib/rec.php';
require '../lib/PHPMailer-master/PHPMailerAutoload.php';


ob_start();


?>
<div class="container">
  <div class="col-sm-6 col-xs-12">
<form action="<?php echo htmlspecialchars($_SEVER['PHP_SELF']); ?>"
 method="post">
<p>Podaj adres e-mail. Nowe hasło zostanie wysłane
na podany adres.</p>
<div class="form-group"><label for="username">Adres e-mail</label>
 <input type="text" name="username" id="username" class="form-control"
  value="<?php if (isset($_POST['username'])){
  echo htmlspecialchars($_POST['username']);
  
  } ?>"/></div>
 <div class="form-group"><input type="submit" value="Zatwierdź" class="btn btn-default"/>
 <input type="hidden" name="submitted" value="1"/>
 </div>
</form>
</div></div>
<?php
$form = ob_get_clean();

if (!isset($_POST['submitted']))
 
{
   
    $GLOBALS['TEMPLATE']['content'] = $form;
}

else
{


  
        $user = User::getByUsermail($_POST['username']);
          $rekruter = Rekruter::getByUsermail($_POST['username']);
       
        if ($user->userId)
        {
    
            $password = random_text(8);

      
           
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
$mail->addAddress($u->emailAddr);    
$mail->addReplyTo('ejanuszewska@onet.pl', 'Information');


$mail->WordWrap = 50;                                 
$mail->isHTML(true);                                 

$mail->Subject = 'Zapomniałeś hasła';
$mail->Body    = '<p>Nowe hasło: ' .
                $password .  '</p>';
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

if(!$mail->send()) {
    $GLOBALS['TEMPLATE']['content'] = 'Message could not be sent.';
    
}
         

            $GLOBALS['TEMPLATE']['content'] = '<p><strong>Nowe hasło ' .
                'wysłano na podany adres.</strong></p>';

         
            $user->password = sha1($password);
              $user->emailAddr = ($_POST['username']);
            $user->save();
        }
    elseif($rekruter->rId)
                {
           
            $password = random_text(8);

         
           
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

$mail->Subject = 'Zapomniałeś hasła';
$mail->Body    = '<p>Nowe hasło: ' .
                $password .  '</p>';
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

if(!$mail->send()) {
    $GLOBALS['TEMPLATE']['content'] = 'Message could not be sent.';
    
}
         

            $GLOBALS['TEMPLATE']['content'] = '<p><strong>Nowe hasło ' .
                'wysłano na podany adres.</strong></p>';

            // zapisanie nowego has�a
            $rekruter->haslo = sha1($password);
              $rekruter->adresEmail = ($_POST['username']);
            $rekruter->save();
        }
        else{
            
            $GLOBALS['TEMPLATE']['content'] = '<p><strong>Przepraszamy, ' .
                'podane konto nie istnieje.</strong></p> <p>Prosimy podać ' .
                'inny adres e-mail.</p>';
            $GLOBALS['TEMPLATE']['content'] .= $form;
        
        }

}


include '../templates/template-page.php';
?>
