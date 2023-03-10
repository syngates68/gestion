<?php
ob_start();
?>

<html>
<head> 
    <meta charset='UTF-8'>
</head>
<body>
    <table width="640px">
        <tr>
            <td width="200"></td>   
            <td colspan="3" width="100"><img src="https://zupimages.net/up/22/11/10u3.png"></td>   
            <td width="200"></td>    
        </tr>
        <tr>
            <td colspan="5" height="20"></td>
        </tr>
        <tr>
            <td colspan="5" align="center"><h1 style="font-size: 37px; font-weight: 800;">Réinitialisation de votre mot de passe</h1></td>   
        </tr>
        <tr>
            <td colspan="5" height="20"></td>
        </tr>
        <tr>
            <td colspan="5" align="center"><?= $user->first_name(); ?>,</td>   
        </tr>
        <tr>
            <td colspan="5" height="20"></td>
        </tr>
        <tr>
            <td colspan="5" align="center">Vous avez fait une demande concernant la réinitialisation de mot de passe de votre compte. Pour cela, veuillez cliquer sur le bouton ci-dessous.</td>   
        </tr>
        <tr>
            <td colspan="5" height="20"></td>
        </tr>
        <tr>
            <td rowspan="2" width="100"></td>    
            <td rowspan="2" width="100"></td>    
            <td rowspan="2" bgcolor="#0070C9" align="center"><a href="localhost/test/reset_password.php?user=<?= $user->id(); ?>" style="text-decoration: none; font-size: 13px; color: #FFF;">&nbsp;Réinitialiser mon mot de passe&nbsp;</a></td>    
            <td rowspan="2" width="100"></td>  
            <td rowspan="2" width="100"></td>  
        </tr>
        <tr>
            <td colspan="5" height="40"></td>
        </tr>
    </table>
</body>
</html>

<?php
return ob_get_clean();