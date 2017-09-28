<?php 
ini_set('max_execution_time', 30);
error_reporting(0);
$con = mysql_connect("localhost","root","");
$db = mysql_select_db("mewlycom_hercules", $con);
$dateUsa = new DateTime("now", new DateTimeZone('US/Pacific'));
$Usatime = $dateUsa->format('h:i A');
$serTimeusa	= strtotime($Usatime);
$curr_date = $dateUsa->format('Y-m-d');
$date1 = $dateUsa->format('m-d-Y');

/*--------start table transfer code-------------*/
$con = new mysqli('localhost', 'root','','mewlycom_hercules_update');

//$currDate1= date("Y-m-d");
//$currDate = date('Y-m-d',strtotime($currDate1 . "-1 days"));
$currDate="2017-07-17";

$q = "SELECT * FROM zo_user_goals group by zo_user_id";
$result = $con->query($q);
if($result->num_rows > 0) 
{ 
   // output data of each row
    while($row = $result->fetch_assoc())
	{
       $id = $row["zo_user_id"];
		$goalId = $row["zo_goal_id"];
		$score	= $row["user_score"];
		$doneGoalCount = $row["user_done_goal_count"];
		
		$q1 = "SELECT * FROM zo_user_goals WHERE zo_user_id = ".$id."";
		$result1 = $con->query($q1);
		if($result1->num_rows > 0) 
		{
			$allScore1 = '';
			$allGoal1 = '';
			$allCount1= '';
			$allPoint1= '';
			while($row1= $result1->fetch_assoc())
			{
				$id1 = $row1["zo_user_id"];
				$goalId1 = $row1["zo_goal_id"];
				$score1	= $row1["user_score"];
				$doneGoalCount1 = $row1["user_done_goal_count"];
				$count1	= $row1["goal_count"];
				$point1 = $row1["goal_point"];
				
				if($score1=='')
					$score1 = 0;
				if($doneGoalCount1=='')
					$doneGoalCount1 = 0;
				if($count1=='')
					$count1 = 0;
				if($point1=='')
					$point1 = 0;
					
				
				if($allScore1!='')
					$allScore1.= ";;;;".$goalId1.":::".$score1;	
				else
					$allScore1.= $goalId1.":::".$score1;

				if($allGoal1!='')
					$allGoal1.= ";;;;".$goalId1.":::".$doneGoalCount1;	
				else
					$allGoal1.= $goalId1.":::".$doneGoalCount1;
					
				if($allPoint1!='')
					$allPoint1.= ";;;;".$goalId1.":::".$point1;	
				else
					$allPoint1.= $goalId1.":::".$point1;

				if($allCount1!='')
					$allCount1.= ";;;;".$goalId1.":::".$count1;	
				else
					$allCount1.= $goalId1.":::".$count1;		
			} 
			
		$q3 ="UPDATE zo_week_details SET week_user_score = '".$allScore1."', 
			week_user_goal_done= '".$allGoal1."',week_goal_point = '".$allPoint1."',
			week_goal_count = '".$allCount1."' WHERE zo_user_id = '".$id1."' 
			AND ('".$currDate."' BETWEEN week_start_date AND 
			week_end_date)";
			$con->query($q3);
		}
	}
}

?>