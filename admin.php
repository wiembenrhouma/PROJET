<?php
session_start();
?>
<link rel="stylesheet" type="text/css" href="css2.css">
<h2>bienvennue, <?php echo $_SESSION['username'];?></h2>
<br/>
<a href="?action=add"><h2>ajouter un produit</h2></a></br>
<a href="?action=modifyanddelete"><h2>modifier / supprimer un produit</h2></a></br>
<a href="?action=add_category"><h2>ajouter une categorie</h2></a></br>
<a href="?action=modifyanddelete_category"><h2>modifier / supprimer une categorie</h2></a></br>

<?php
try
				{$db= new PDO('mysql:host=localhost;dbname=site','root','');
				$db->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
				$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
				}
			catch(Exception $e){
				
				die('une erreur est survennue');
				
			}	
if(isset($_SESSION['username'])){
	if(isset($_GET['action'])){
	if($_GET['action']=='add'){
		
		
		if(isset($_POST['submit'])){
			$id=$_POST['id'];
			$title=$_POST['title'];
			$description=$_POST['description'];
			$price=$_POST['price'];
			
			
			
			$img = $_FILES['img']['name'];
			$img_tmp =  $_FILES['img']['tmp_name'];
			if(!empty($img_tmp)){
				$image = explode('.',$img);
				$image_ext = end($image);
				if(in_array(strtolower($image_ext),array('png','jpg','jpeg'))===false){
					echo'veuiller rentrer une img:png,jpg,jpeg';
				}else{
					$image_size = getimagesize($img_tmp);
					if($image_size['mime']=='image/jpeg'){
						$image_src = imagecreatefromjpeg($img_tmp);
					}else if ($image_size['mime']=='image/png'){
						$image_src = imagecreatefrompng($img_tmp);
					}else{
						$image_src = false;
						echo'veuillez rentrer une img valide';
					}
					if($image_src!==false){
						$image_width=200;
						  if($image_size[0]==$image_width){
							  $image_finale = $image_src;
						  }else{
							  $new_width[0]=$image_width;
							  $new_height[1] =200;
							  $image_finale=imagecreatetruecolor($new_width[0],$new_height[1]);
		imagecopyresampled($image_finale,$image_src,0,0,0,0,$new_width[0],$new_height[1],$image_size[0],$image_size[1]);
						  }
						  imagejpeg($image_finale,'imgs/'.$title.'.jpg');
					}
				}
				
			}else{
				echo'veuillez entrer une img';
			}
			
			
			
			
		if($id&&$title&&$description&&$price){
				
		$category = $_POST['category'];	
		$insert = $db->prepare("INSERT INTO produit VALUES('$id','$title','$description','$price','$category')");
		$insert->execute();
		}else{
			echo'veuillez remplir champs';
		}
		}		
?>
<link rel="stylesheet" type="text/css" href="css2.css">
<form action="" method="post" enctype="multipart/form-data">

<p>id</p><input type="text" name="id"/></br>
<p>titre du produit:</p><input type="text" name="title"/></br>
<p>description du produit:</p><textarea name="description"></textarea></br>
<p>prix:</p><input type="text" name="price"/></br></br>
<p>image:</p></br>
<input type="file" name="img"/></br></br>
<p>categorie:</p><select name="category"></br>


<?php $select=$db->query("SELECT * FROM category");
	while($s = $select->fetch(PDO::FETCH_OBJ)){

       ?>
	 
	<option><?php echo $s->name; ?></option>
	<?php
	}
?>	
</select></br></br>
<input type="submit" name="submit"/>
</form>
<?php
    }else if ($_GET['action']=='modifyanddelete'){
		$select = $db->prepare("SELECT * FROM produit");
		$select->execute();
		 
		 while($s=$select->fetch(PDO::FETCH_OBJ)){
			 echo $s->title;
			 ?>
			
			 <a href="?action=modify&amp;id=<?php echo $s->id;?>"><p >modifier</a>
			 <a href="?action=delete&amp;id=<?php echo $s->id;?>"><p>suprimer</a><br/><br/>
			 <?php
			 
		 }

	}else if ($_GET['action']=='modify'){
		
		$id=$_GET['id'];
		$select = $db->prepare("SELECT *FROM produit WHERE id=$id");
		$select->execute();
		
		$data = $select->fetch(PDO::FETCH_OBJ);
		?>

		<form action="" method="post">

<p>titre du produit:</p><input value="<?php echo $data->title;?>" type="text" name="title"/>
<p>description du produit:</p><textarea name="description"><?php echo $data->description;?></textarea>
<p>prix</p><input value="<?php echo $data->price; ?>" type="text"  name="price"/></br></br>
<input type="file" name="img"/></br></br>
<input type="submit" name="submit" value="modifier"/>
</form>
	<?php
	if(isset($_POST['submit'])){
	   
			$title=$_POST['title'];
			$description=$_POST['description'];
			$price=$_POST['price'];
$update= $db->prepare("UPDATE produit SET title='$title',description='$description',price='$price' WHERE id=$id");
$update->execute();
header('location: admin.php?action=modifyanddelete');
	}
	
	
	
	
	}else if ($_GET['action']=='delete'){
		$id=$_GET['id'];
	    $delete = $db->prepare("DELETE FROM produit WHERE id=$id");
		$delete->execute();
		
	}else if ($_GET['action']=='add_category'){
		if(isset($_POST['submit'])){
			$name = $_POST['name'];
			$id = $_POST['id'];
			if($name){
				$insert = $db->prepare("INSERT INTO category VALUES('$id','$name')");
		        $insert->execute();
				
			}else{
				echo'veuillez remplir tous les champs';
				
			}
		}
		
		?>
		<form action="" method="post">
		<p>id de la categorie:</p><input type="text" name="id"/></br></br>
		<p>tittre de la categorie:</p><input type="text" name="name"/></br></br>
		
		<input type="submit" name="submit" value="ajouter"/>
		</form>
		
		<?php
		

	}else if($_GET['action']=='modifyanddelete_category'){
		$select = $db->prepare("SELECT * FROM category");
		$select->execute();
		 
		 while($s=$select->fetch(PDO::FETCH_OBJ)){
			 		
			echo $s->name;
			//echo "<p><center> $s->name</center></p>";

			 ?>
			 <a href="?action=modify_category&amp;id=<?php echo $s->id;?>"><p>modifier</a>
			 <a href="?action=delete_category&amp;id=<?php echo $s->id;?>"><p>suprimer</a><br/><br/>
			 <?php
		 }
		 
	}else if($_GET['action']=='modify_category'){	
		$id=$_GET['id'];
		$select = $db->prepare("SELECT *FROM category WHERE id=$id");
		$select->execute();
		
		$data = $select->fetch(PDO::FETCH_OBJ);
		?>
		<form action="" method="post">

<h1>titre du categorie:</h1><input value="<?php echo $data->name;?>" type="text" name="title"/>
<input type="submit" name="submit" value="modifier"/>
</form>
	<?php
	if(isset($_POST['submit'])){
	   
			$title=$_POST['title'];
			
$update= $db->prepare("UPDATE category SET name='$title' WHERE id=$id");
$update->execute();
header('location: admin.php?action=modifyanddelete_category');
	}


}else if($_GET['action']=='delete_category'){
		$id=$_GET['id'];
	    $delete = $db->prepare("DELETE FROM category WHERE id=$id");
		$delete->execute();
		header('location: admin.php?action=modifyanddelete_category');
		
	}else{
		die('une erreur s est produite');
	}
	
}else{
	
}
}else{
	header('location: ../index.php');
}

?>
<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/png" href="assets/images/icon/favicon.ico">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/themify-icons.css">
    <link rel="stylesheet" href="assets/css/metisMenu.css">
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/css/slicknav.min.css">
    <!-- amchart css -->
    <link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
    <!-- others css -->
    <link rel="stylesheet" href="assets/css/typography.css">
    <link rel="stylesheet" href="assets/css/default-css.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <!-- modernizr css -->
    <script src="assets/js/vendor/modernizr-2.8.3.min.js"></script>
</head>

<body>
    <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
    <!-- preloader area start -->
    <div id="preloader">
        <div class="loader"></div>
    </div>
    <!-- preloader area end -->
    <!-- page container area start -->
    <div class="page-container">
        <!-- sidebar menu area start -->
        <div class="sidebar-menu">
            <div class="sidebar-header">
                <div class="logo">
                    <a href="index.html"><img src="assets/images/icon/logo1.jpg" alt="logo"></a>
                </div>
            </div>
            <div class="main-menu">
                <div class="menu-inner">
                    <nav>
                        <ul class="metismenu" id="menu">
                            <li class="active">
                                <a href="javascript:void(0)" aria-expanded="true"><i class="ti-dashboard"></i><span>dashboard</span></a>
                                <ul class="collapse">
                                    <li class="active"><a href="admin.php">produits/categories</a></li>
                                    <li><a href="index2.html">Ecommerce dashboard</a></li>
                                    <li><a href="index3.html">SEO dashboard</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="javascript:void(0)" aria-expanded="true"><i class="ti-layout-sidebar-left"></i><span>
                                        administration
                                    </span>
                            </li>
                          
                            <li>
                                <a href="javascript:void(0)" aria-expanded="true"><i class="ti-palette"></i><span>description
                                
                            </li>
                            <li>
                                <a href="javascript:void(0)" aria-expanded="true"><i class="ti-slice"></i><span>commandes</span></a>
                                
                            </li>
                            <li>
                                <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-table"></i>
                                    <span>reclamations</span></a>
                                
                            </li>
                            <li><a href="maps.html"><i class="ti-map-alt"></i> <span>randez-vous</span></a></li>
                            <li><a href="invoice.html"><i class="ti-receipt"></i> <span>Invoice Summary</span></a></li>
                            <li>
                                <a href="javascript:void(0)" aria-expanded="true"><i class="ti-layers-alt"></i> <span>Pages</span></a>
                                <ul class="collapse">
                                    <li><a href="login.html">Login</a></li>
                                    <li><a href="login2.html">Login 2</a></li>
                                    <li><a href="login3.html">Login 3</a></li>
                                    <li><a href="register.html">Register</a></li>
                                    <li><a href="register2.html">Register 2</a></li>
                                    <li><a href="register3.html">Register 3</a></li>
                                    <li><a href="register4.html">Register 4</a></li>
                                    <li><a href="screenlock.html">Lock Screen</a></li>
                                    <li><a href="screenlock2.html">Lock Screen 2</a></li>
                                    <li><a href="reset-pass.html">reset password</a></li>
                                    <li><a href="pricing.html">Pricing</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-exclamation-triangle"></i>
                                    <span>Error</span></a>
                                <ul class="collapse">
                                    <li><a href="404.html">Error 404</a></li>
                                    <li><a href="403.html">Error 403</a></li>
                                    <li><a href="500.html">Error 500</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-align-left"></i> <span>Multi
                                        level menu</span></a>
                                <ul class="collapse">
                                    <li><a href="#">Item level (1)</a></li>
                                    <li><a href="#">Item level (1)</a></li>
                                    <li><a href="#" aria-expanded="true">Item level (1)</a>
                                        <ul class="collapse">
                                            <li><a href="#">Item level (2)</a></li>
                                            <li><a href="#">Item level (2)</a></li>
                                            <li><a href="#">Item level (2)</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="#">Item level (1)</a></li>
                                </ul>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <!-- sidebar menu area end -->
        <!-- main content area start -->
        <div class="main-content">
            <!-- header area start -->
            <div class="header-area">
               
                    </div>
                   
            <!-- header area end -->
           
            <div class="main-content-inner">
               
               
                <!-- row area end -->
                <div class="row mt-5">
                   
                <!-- row area start-->
            </div>
        </div>
       
    
    <!-- page container area end -->
    <!-- offset area start -->
    <div class="offset-area">
        <div class="offset-close"><i class="ti-close"></i></div>
        <ul class="nav offset-menu-tab">
            <li><a class="active" data-toggle="tab" href="#activity">Activity</a></li>
            <li><a data-toggle="tab" href="#settings">Settings</a></li>
        </ul>
        <div class="offset-content tab-content">
            <div id="activity" class="tab-pane fade in show active">
                <div class="recent-activity">
                    <div class="timeline-task">
                        <div class="icon bg1">
                            <i class="fa fa-envelope"></i>
                        </div>
                        <div class="tm-title">
                            <h4>Rashed sent you an email</h4>
                            <span class="time"><i class="ti-time"></i>09:35</span>
                        </div>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse distinctio itaque at.
                        </p>
                    </div>
                    <div class="timeline-task">
                        <div class="icon bg2">
                            <i class="fa fa-check"></i>
                        </div>
                        <div class="tm-title">
                            <h4>Added</h4>
                            <span class="time"><i class="ti-time"></i>7 Minutes Ago</span>
                        </div>
                        <p>Lorem ipsum dolor sit amet consectetur.
                        </p>
                    </div>
                    <div class="timeline-task">
                        <div class="icon bg2">
                            <i class="fa fa-exclamation-triangle"></i>
                        </div>
                        <div class="tm-title">
                            <h4>You missed you Password!</h4>
                            <span class="time"><i class="ti-time"></i>09:20 Am</span>
                        </div>
                    </div>
                    <div class="timeline-task">
                        <div class="icon bg3">
                            <i class="fa fa-bomb"></i>
                        </div>
                        <div class="tm-title">
                            <h4>Member waiting for you Attention</h4>
                            <span class="time"><i class="ti-time"></i>09:35</span>
                        </div>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse distinctio itaque at.
                        </p>
                    </div>
                    <div class="timeline-task">
                        <div class="icon bg3">
                            <i class="ti-signal"></i>
                        </div>
                        <div class="tm-title">
                            <h4>You Added Kaji Patha few minutes ago</h4>
                            <span class="time"><i class="ti-time"></i>01 minutes ago</span>
                        </div>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse distinctio itaque at.
                        </p>
                    </div>
                    <div class="timeline-task">
                        <div class="icon bg1">
                            <i class="fa fa-envelope"></i>
                        </div>
                        <div class="tm-title">
                            <h4>Ratul Hamba sent you an email</h4>
                            <span class="time"><i class="ti-time"></i>09:35</span>
                        </div>
                        <p>Hello sir , where are you, i am egerly waiting for you.
                        </p>
                    </div>
                    <div class="timeline-task">
                        <div class="icon bg2">
                            <i class="fa fa-exclamation-triangle"></i>
                        </div>
                        <div class="tm-title">
                            <h4>Rashed sent you an email</h4>
                            <span class="time"><i class="ti-time"></i>09:35</span>
                        </div>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse distinctio itaque at.
                        </p>
                    </div>
                    <div class="timeline-task">
                        <div class="icon bg2">
                            <i class="fa fa-exclamation-triangle"></i>
                        </div>
                        <div class="tm-title">
                            <h4>Rashed sent you an email</h4>
                            <span class="time"><i class="ti-time"></i>09:35</span>
                        </div>
                    </div>
                    <div class="timeline-task">
                        <div class="icon bg3">
                            <i class="fa fa-bomb"></i>
                        </div>
                        <div class="tm-title">
                            <h4>Rashed sent you an email</h4>
                            <span class="time"><i class="ti-time"></i>09:35</span>
                        </div>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse distinctio itaque at.
                        </p>
                    </div>
                    <div class="timeline-task">
                        <div class="icon bg3">
                            <i class="ti-signal"></i>
                        </div>
                        <div class="tm-title">
                            <h4>Rashed sent you an email</h4>
                            <span class="time"><i class="ti-time"></i>09:35</span>
                        </div>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse distinctio itaque at.
                        </p>
                    </div>
                </div>
            </div>
            <div id="settings" class="tab-pane fade">
                <div class="offset-settings">
                    <h4>General Settings</h4>
                    <div class="settings-list">
                        <div class="s-settings">
                            <div class="s-sw-title">
                                <h5>Notifications</h5>
                                <div class="s-swtich">
                                    <input type="checkbox" id="switch1" />
                                    <label for="switch1">Toggle</label>
                                </div>
                            </div>
                            <p>Keep it 'On' When you want to get all the notification.</p>
                        </div>
                        <div class="s-settings">
                            <div class="s-sw-title">
                                <h5>Show recent activity</h5>
                                <div class="s-swtich">
                                    <input type="checkbox" id="switch2" />
                                    <label for="switch2">Toggle</label>
                                </div>
                            </div>
                            <p>The for attribute is necessary to bind our custom checkbox with the input.</p>
                        </div>
                        <div class="s-settings">
                            <div class="s-sw-title">
                                <h5>Show your emails</h5>
                                <div class="s-swtich">
                                    <input type="checkbox" id="switch3" />
                                    <label for="switch3">Toggle</label>
                                </div>
                            </div>
                            <p>Show email so that easily find you.</p>
                        </div>
                        <div class="s-settings">
                            <div class="s-sw-title">
                                <h5>Show Task statistics</h5>
                                <div class="s-swtich">
                                    <input type="checkbox" id="switch4" />
                                    <label for="switch4">Toggle</label>
                                </div>
                            </div>
                            <p>The for attribute is necessary to bind our custom checkbox with the input.</p>
                        </div>
                        <div class="s-settings">
                            <div class="s-sw-title">
                                <h5>Notifications</h5>
                                <div class="s-swtich">
                                    <input type="checkbox" id="switch5" />
                                    <label for="switch5">Toggle</label>
                                </div>
                            </div>
                            <p>Use checkboxes when looking for yes or no answers.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- offset area end -->
    <!-- jquery latest version -->
    <script src="assets/js/vendor/jquery-2.2.4.min.js"></script>
    <!-- bootstrap 4 js -->
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/owl.carousel.min.js"></script>
    <script src="assets/js/metisMenu.min.js"></script>
    <script src="assets/js/jquery.slimscroll.min.js"></script>
    <script src="assets/js/jquery.slicknav.min.js"></script>

    <!-- start chart js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>
    <!-- start highcharts js -->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <!-- start zingchart js -->
    <script src="https://cdn.zingchart.com/zingchart.min.js"></script>
    <script>
    zingchart.MODULESDIR = "https://cdn.zingchart.com/modules/";
    ZC.LICENSE = ["569d52cefae586f634c54f86dc99e6a9", "ee6b7db5b51705a13dc2339db3edaf6d"];
    </script>
    <!-- all line chart activation -->
    <script src="assets/js/line-chart.js"></script>
    <!-- all pie chart -->
    <script src="assets/js/pie-chart.js"></script>
    <!-- others plugins -->
    <script src="assets/js/plugins.js"></script>
    <script src="assets/js/scripts.js"></script>
</body>

</html>


