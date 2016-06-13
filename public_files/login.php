<?php
session_start();
// do��czenie kodu wsp�u�ytkowanego
include '../lib/common.php';
include '../lib/db.php';
include '../lib/functions.php';
include '../lib/User.php';
include '../lib/rec.php';
 
// rozpocz�cie lub do��czenie do sesji
 
header('Cache-control: private');
 
// logowanie, je�li ustawiono zmienn� login
if (isset($_GET['login']))
    
{
    $userType=$_POST['user'];
    if (isset($_POST['usermail']) && isset($_POST['password']) )
    {
         
        if (User::validateEmailAddr($_POST['usermail']) && $userType == 'user')
        {
            $user=User::getByUsermail($_POST['usermail']);
            $userT=1;
        } 
       elseif (Rekruter::validateEmailAddr($_POST['usermail']) && $userType == 'recruiter'){
            $rekruter=Rekruter::getByUsermail($_POST['usermail']);
            $userT=2;
        } 
        else{
            new User();
        }
        //$user = (User::validateEmailAddr($_POST['usermail'])) ?
         //   User::getByUsermail($_POST['usermail']) : new User();
 
        if ($user->userId && $user->password == sha1($_POST['password']) && $userT==1)
        {
            // zapisanie warto�ci w sesji, aby m�c �ledzi� u�ytkownika
            // i przekierowa� go do strony g��wnej
            $_SESSION['access'] = TRUE;
            $_SESSION['userId'] = $user->userId;
            $_SESSION['usermail'] = $user->usermail;
            
            header('Location: index.php');
        }
        elseif($rekruter->rId && $rekruter->haslo == sha1($_POST['password']) && $userT==2){
            $_SESSION['access'] = TRUE;
            $_SESSION['rId'] = $rekruter->rId;
            $_SESSION['usermail'] = $rekruter->usermail;
           header('Location: index.php');
           
        }
        else
        {
            // nieprawid�owy u�ytkownik i (lub) has�o
            $_SESSION['access'] = FALSE;
            $_SESSION['usermail'] = null;
              header('Location: login_failed.php');
            
            
            
   
      
     
        } 
    }
    // brak danych uwierzytelniaj�cych
    else
    {
        $_SESSION['access'] = FALSE;
        $_SESSION['usermail'] = null;
            header('Location: login_failed.php');
    }
    exit();
}
 
// wylogowanie, je�li ustawiono zmienn� logout
// (wyczyszczenie danych sesji prowadzi do wylogowania u�ytkownika)
/*else if (isset($_GET['logout']))
{
    if (isset($_COOKIE[session_name()]))
    {
        setcookie(session_name(), '', time() - 42000, '/');
    }
 
    $_SESSION = array();
    session_unset();
    session_destroy();
}*/
 
// wygenerowanie formularza logowania
ob_start();
?>
<div class="container">
  <div class="col-sm-6 col-xs-12"><form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?login"
 method="post">
 <div class="form-group"><label for="usermail">Nazwa użytkownika</label>
     <input type="text" name="usermail" id="usermail" class="form-control"/></div>
   <div class="form-group"><label for="password">Hasło</label>
   <input type="password" name="password" id="password" class="form-control"/></div>
   <div class="form-group"><input type="radio" name="user" value="user" checked="checked" />Poszukujący pracy
       <input type="radio" name="user" value="recruiter"/>Rekruter</div>
          <div class="form-group"><input type="submit" value="Zaloguj" class="btn btn-default"/><a href="forgotpass.php"> Przypomnij hasło</a></div>
      </form></div></div>
<?php
$GLOBALS['TEMPLATE']['content'] = ob_get_clean();


$GLOBALS['TEMPLATE']['content'] .= $form;
 
// wy�wietlenie strony
include '../templates/template-page.php';
?>
