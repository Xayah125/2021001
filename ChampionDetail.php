<?php
session_start();
$mysql_connect = mysqli_connect('127.0.0.1','root','','lol_update');
	if (!$mysql_connect){
		die('Could not connect: ' . mysqli_error($mysql_connect));
	}
	mysqli_query($mysql_connect,"SET NAMES 'utf8'");
?>
<!doctype html>
<html lang="en">
<head>
	<!-- Required meta tags -->
 	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Bootstrap CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">

	<title>LOL技能改版記錄</title>

</head>
<body onload="startTime()">
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark" width="100%">
    	<div class="container-fluid">
      		<a class="navbar-brand" style="margin-left: 2%" style="font-size: 20px;" href="HOME.php"> 
        	LOL技能改版記錄
      		</a>
      	</div>
   	</nav>

	<?php
	$sql_skill_connect = "SELECT skill_id,skill_name FROM skill as S LEFT JOIN champion as C on S.champion_id = C.champion_id WHERE champion_name = '".$_GET['champion_name']."'";
	$sql_skill_query = mysqli_query($mysql_connect,$sql_skill_connect);
	$sql_skill_rowcount = mysqli_num_rows(@$sql_skill_query);
	$champion_name = preg_replace('/\s(?=)/', '', $_GET['champion_name']);
	?>
		
	<div class="container-fluid" style="padding-top: 20px;">
   		<div class="row">
   			<div class="col-12" style="height:100px;">
   				<?php
   				echo '<h3><img src="champion/'.$champion_name.'/'.$champion_name.'.png" width="120" height="120" class="img-thumbnail" alt="'.$_GET['champion_name'].'" title="'.$_GET['champion_name'].'">'.$_GET['champion_name'].'</h3>
   					<a href="https://tw.op.gg/champion/'.$champion_name.'/statistics/">Champion analysis (OPGG)</a><br>
   					<a href="https://tw.op.gg/aram/'.$champion_name.'/statistics">Champion analysis of ARAM (OPGG)</a>';?>

   				<hr>
   				
   				<form method="GET" class="form-horizontal" align="middle">
					<table >
     					<div class="form-group" >
     						<input type="hidden" name="champion_name" value="<?php echo $_GET['champion_name']?>">
	 						<label class="control-label"><font size="4px" >Search for :</font></label>
	          				<select name="search_skill" style="width: 200px;">
						        <?php
						        	for ($i=0; $i < $sql_skill_rowcount; $i++) { 
										$sql_skill_row = mysqli_fetch_assoc($sql_skill_query);
			
										
										$skill_img = explode( "] ",$sql_skill_row['skill_name']); 
						        		if(@$_GET['search_skill'] == $sql_skill_row['skill_name']	){
						        			echo '
						        				<option selected value="'.$sql_skill_row['skill_name'].'">'.$sql_skill_row['skill_name'].'</option>';
						        		}
						        		else{
						        			echo '
						        				<option value="'.$sql_skill_row['skill_name'].'">'.$sql_skill_row['skill_name'].'</option>';
						        		}
 		  							}
						        ?>
	         				</select>
	         				<input class="btn btn-primary" type="submit" style="margin-left: 20px;width:100px;" value="Submit">
	         				<input class="btn btn-danger" type="button" style="margin-left: 20px;width:100px;" value="Delete" onclick="location.href='ChampionDetail.php?champion_name=<?php echo $_GET['champion_name'];?>'" >
        				</div>
					</table>
				</form>
				<hr>
   			</div>

   			<!--<div class="col-3" style="height:100px;text-align:center;line-height:100px;">
   				<font size="6px">目前版本:11.07</font>
   			</div>-->
   		</div>
   	</div>


   	<div class="container" style="padding-top: 200px;">
   		<div class="row">
   			<div class="col-12" style="height:100px;">
		   		<?php 
		   		if (@$_GET['search_skill'] != "") {
		   			# code...
		   		
					$sql_record_connect = "SELECT * FROM record as R LEFT JOIN version as V on R.version_id = V.version_id LEFT JOIN skill as S on R.skill_id = S.skill_id WHERE skill_name = '".$_GET['search_skill']."'";
					$sql_record_query = mysqli_query($mysql_connect,$sql_record_connect);
					$sql_record_rowcount = mysqli_num_rows(@$sql_record_query);
					$skill_img = explode( "] ",$_GET['search_skill']);
					echo '
						<img src="champion/'.$champion_name.'/'.$skill_img[1].'.png" width="80" height="80" class="img-thumbnail" alt="'.$_GET['champion_name'].'" title="'.$_GET['champion_name'].'">'.$_GET['search_skill'].'<br><hr style="color:orange;height:5px;">';
						
					for ($i=0; $i < $sql_record_rowcount; $i++) { 
						$sql_record_row = mysqli_fetch_assoc($sql_record_query);
						if (strpos($sql_record_row['record_item_statement'], "⇒" ) == TRUE) {
							$record_item_statement = explode( "⇒",$sql_record_row['record_item_statement']);

							if ($sql_record_row['record_status']== "BUFF") {
								$record_item_statement[1] = '<font color="red">'.$record_item_statement[1].'</font>';
							}
							elseif ($sql_record_row['record_status']== "NERF") {
								$record_item_statement[1] = '<font color="blue">'.$record_item_statement[1].'</font>';
							}
							echo '版本'.$sql_record_row['version_number'].' (臺服更新於'.$sql_record_row['version_date'].')<br><br>'.$sql_record_row['record_item'].'<br>'.$record_item_statement[0].'=>'.$record_item_statement[1].'<br><hr style="color:orange;height:5px;"><br>';
						}
						else{
							echo '版本'.$sql_record_row['version_number'].' (臺服更新於'.$sql_record_row['version_date'].')<br><br>'.$sql_record_row['record_item'].'<br>'.$sql_record_row['record_item_statement'].'<br><hr style="color:orange;height:5px;"><br>';
						}
							
					}
				}
				?>
			</div>
		</div>
	</div>
</body>
</html>

