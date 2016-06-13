<?php
session_start();

include '../lib/common.php';
include '../lib/db.php';
include '../lib/functions.php';
include '../lib/User.php';
include '../lib/rec.php';
 

 
header('Cache-control: private');
 

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
      
 
        if ($user->userId && $user->password == sha1($_POST['password']) && $userT==1)
        {

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
          
            $_SESSION['access'] = FALSE;
            $_SESSION['usermail'] = null;
    header('Location: login_failed.php');
            
            
   
      
     
        } 
    }

    else
    {
        $_SESSION['access'] = FALSE;
        $_SESSION['usermail'] = null;
            header('Location: login_failed.php');
    }
    exit();
}
 

ob_start();
?>
<div class="container">
  <div class="col-sm-6 col-xs-12">
   Wprowadzono nieprawidłowy adres e-mail lub hasło.<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?login"
 method="post">
 <div class="form-group"><label for="usermail">Nazwa użytkownika</label>
     <input type="text" name="usermail" id="usermail" class="form-control"/></div>
   <div class="form-group"><label for="password">Hasło</label>
   <input type="password" name="password" id="password" class="form-control"/></div>
   <div class="form-group"><input type="radio" name="user" value="user" checked="checked" />Poszukujący pracy
       <input type="radio" name="user" value="recruiter" "/>Rekruter</div>
          <div class="form-group"><input type="submit" value="Zaloguj" class="btn btn-default"/><a href="forgotpass.php"> Przypomnij hasło</a></div>
      </form></div></div>
<?php
$GLOBALS['TEMPLATE']['content'] = ob_get_clean();


$GLOBALS['TEMPLATE']['content'] .= $form;
 

include '../templates/template-page.php';
?>
