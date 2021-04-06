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

   	<div class="container" style="padding-top: 100px;">
   		<div class="row">
   			<div class="col-12" style="height:100px;">
   				<form method="GET" class="form-horizontal" align="middle">
					<table >
     					<div class="form-group" >
	 						<label class="control-label"><font size="4px" >Search for :</font></label>
	          				<select name="search_a_z" style="width: 80px;">
                    <option selected class="disable"></option>
						        <?php
						        	for ($i=0; $i < 26; $i++) { 
						        		if(@$_GET['search_a_z'] == chr($i+65)){
						        			echo '
						        				<option selected value="'.chr($i+65).'">'.chr($i+65).'</option>
						        				';
						        		}
						        		else{
						        			echo '
						        				<option value="'.chr($i+65).'">'.chr($i+65).'</option>
						        				';
						        		}
 		  							}
						        ?>
	         				</select>
                  <select name="version" style="width: 150px;">
                    <option selected class="disable" value="ALL">ALL</option>
                    <?php
                      $sql_version_connect = "SELECT * FROM version ORDER BY version_id DESC";
                      $sql_version_query = mysqli_query($mysql_connect,$sql_version_connect);
                      $sql_version_rowcount = mysqli_num_rows(@$sql_version_query);
                      for ($i=0; $i < $sql_version_rowcount; $i++) { 
                        $sql_version_row = mysqli_fetch_assoc($sql_version_query);
                        if (@$_GET['version'] == $sql_version_row['version_id']) {
                          echo '<option selected class="disable" value="'.$sql_version_row['version_id'].'">'.$sql_version_row['version_number'].' (TW '.$sql_version_row['version_date'].')</option>';
                        }
                        else{
                          echo '<option class="disable" value="'.$sql_version_row['version_id'].'">'.$sql_version_row['version_number'].' (TW '.$sql_version_row['version_date'].')</option>';
                        }
                      }
                      
                    ?>
                  </select>
	         				
	         				<input class="btn btn-primary" type="submit" style="margin-left: 20px;width:100px;" value="Submit">
	         				<input class="btn btn-danger" type="button" style="margin-left: 20px;width:100px;" value="Delete" onclick="location.href='HOME.php'" >
        				</div>
					</table>
				</form>
   			</div>

   			<!--<div class="col-3" style="height:100px;text-align:center;line-height:100px;">
   				<font size="6px">目前版本:11.07</font>
   			</div>-->
   		</div>
   	</div>

   	<div class="container-fluid" style="padding-top: 50px;">
   		<div class="row">
   			<div class="col-12" style="height:100px;">
   				<?php
   				if (@$_GET['search_a_z'] != ""|| @$_GET['version'] != "") {
            if ($_GET['version'] == "ALL") {
              $sql_champion_connect = "SELECT * FROM champion WHERE champion_name LIKE '".$_GET['search_a_z']."%'";
            }
            else{
   					  $sql_champion_connect = "SELECT * FROM champion as C LEFT JOIN skill as S on C.champion_id = S.champion_id LEFT JOIN record as R on S.skill_id = R.skill_id WHERE champion_name LIKE '".$_GET['search_a_z']."%' AND version_id = '".@$_GET['version']."' GROUP BY champion_name";
            }
   				  
          }
   				else{
   					$sql_champion_connect = "SELECT * FROM champion";
   				}

				  $sql_champion_query = mysqli_query($mysql_connect,$sql_champion_connect);
				  $sql_champion_rowcount = mysqli_num_rows(@$sql_champion_query);
   				for ($i=0; $i < $sql_champion_rowcount; $i++) { 
					$sql_champion_row = mysqli_fetch_assoc($sql_champion_query);
          
					echo '
						<a href="ChampionDetail.php?champion_name='.$sql_champion_row['champion_name'].'">
						<img src="champion/'.preg_replace('/\s(?=)/', '', $sql_champion_row['champion_name']).'/'.preg_replace('/\s(?=)/', '', $sql_champion_row['champion_name']).'.png" width="100" height="100" class="img-thumbnail" alt="'.$sql_champion_row['champion_name'].'" title="'.$sql_champion_row['champion_name'].'">
						</a>
						';
				}
   				
   				?>
   			</div>
   		</div>
   	</div>



</body>
</html>

