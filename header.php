<?php
session_start();
try
				{$db= new PDO('mysql:host=localhost;dbname=site','root','');
				$db->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
				$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
				}
			catch(Exception $e){
			
				die('une erreur est survennue');
				
			}	
?>
<!DOCTYPE html>
<html>
<head>
</head>
<header>
<li><a href="index1.php"></a></li>
<li><a href="service.php"></a></li></br>


</header>
</html>
