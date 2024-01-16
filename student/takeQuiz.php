
<?php
session_start();
require_once "MyUtils.class.php";
$my = new MyUtils();
$title = "Take Quiz";
$style = "home_page.css";
$page = "Take Quiz";
$role = "";
$eventId = "";
$style1 = "professor.css";
$id = "";
if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

$role = "";
$role = $_SESSION['role'];

require_once "DB.class.php";
$db1 = new DB();
$id = "";
if (isset($_GET['id'])) {
    $id = $_GET['id'];
}
$owner = $_GET['owner'];
$_SESSION['owner'] = $owner;
$role1 = $_SESSION['role'];
$link = "";
$data1 = $db1->viewMyQuiz($role);

if (isset($_GET['id'])) {

    foreach ($data1 as $quiz) {
        $quizname = $quiz->quizname;
        $qid = $quiz->quizid;
        $link .= '<li>
            <a href="studentShowQuestions.php?id=' . $qid . '&owner=' . $owner . '">
            <span><img src="../assets/images/quiz.png" alt="icon" /></span>  
            <span>' . $quizname . '</span>
            </a>
            </li>';
    }
}

echo $my->quiz_header($title, $style, $style1, $page, $owner, $link, $role1);
?>

<?php


require_once "DB.class.php";
$db1 = new DB();
$id = "";
if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

    echo '<h2 id = "inform"></h2> <div id="clockdiv"></div>';

$time = $db1->viewMyQuiz1($id);


$bigString = "";
$timer = "";
$checktimer  ="";
   
if (isset($_GET['id'])) {

    foreach ($time as $timecheck) {

        $timer .= $timecheck->timelimit;
        $checktimer = $timecheck->timelimit;
    }

}
    


echo '<script>
    function countdown( elementName, minutes, seconds )
    {
        var element, endTime, hours, mins, msLeft, time;

        function twoDigits( n )
        {
            return (n <= 9 ? "0" + n : n);
        }

        function updateTimer()
        {
            msLeft = endTime - (+new Date);
            if ( msLeft < 1000 ) {
                element.innerHTML = "Time is up!";
                document.getElementById("myForm").click();

            } else {
                time = new Date( msLeft );
                hours = time.getUTCHours();
                mins = time.getUTCMinutes();
                element.innerHTML = (hours ? hours + ":" + twoDigits( mins ) : mins) + ":" + twoDigits( time.getUTCSeconds() );
                setTimeout( updateTimer, time.getUTCMilliseconds() + 500 );
            }
        }

        element = document.getElementById( elementName );
        endTime = (+new Date) + 1000 * (60*minutes + seconds) + 500;
        updateTimer();
    }

    countdown( "clockdiv", '.$checktimer.', 0 );

    

    </script> ';


?>

<?php


require_once "DB.class.php";
$db = new DB();
$id = "";
if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

$data = $db->viewMyQuestion($id);

$bigString = "";

$count = 0;
if (isset($_GET['id'])) {
    foreach ($data as $quiz) {
        $count += 1;
        $_SESSION['userRole'] = $quiz->quizid;
        $bigString .= $quiz->takeQuiz($count);
    }

}



echo ' <div class="questionform">
	<form  method="POST">'.
	$bigString. '<input id="myForm" type="submit" name="submit" class="button" value="submit">
		<br>

    </form>
    </div>';



if (isset($_POST['submit'])) {
		
		$vail = true;
		$sumbitAnswers = [];
		for ($x = 1; $x <= $count; $x++){
			$answer = 'answer' . $x;
			if (isset($_POST[$answer])){
				array_push($sumbitAnswers,$_POST[$answer]);
			}
			else{
				array_push($sumbitAnswers, "N");
	
			}

		}
		
		
		if ($vail){
		
		require_once "DB.class.php";
		$db = new DB();
		if (isset($_GET['id'])) {
    		$id = $_GET['id'];
		}

		$data = $db->viewMyQuestion($id);

		$answers = [];
		if (isset($_GET['id'])) {
    		foreach ($data as $quiz) {
       		$eachanswer = $quiz->answer;
       		array_push($answers, $eachanswer );
       		}
    	}

    	$correct = 0;
		for ($x = 0; $x < $count; $x++){
			if ($sumbitAnswers[$x] == $answers[$x]){
                $correct += 1;
            } 

        }
			
			
		$sumbitAnswers_together = implode(", ", $sumbitAnswers);
		$answers_together = implode(", ", $answers);
		
		$grade = number_format(($correct/$count)*100, 2, '.', '');
		
		
		
		$db->insertEachQuiz($id, $owner, $sumbitAnswers_together, "Y", $answers_together, $grade);
		
		echo "<script>if(confirm('Your grade is ". $grade ."')){document.location.href='studentShowQuestions.php?id=$id&owener=$owner'}else{document.location.href='studentShowQuestions.php?id=$id&owener=$owner'};</script>";
		
		
		}

		
		
		
}
?>




<?php
//check if user login to admin
if (!isset($_SESSION['userRole']) && !isset($_GET['id'])) {
    header("Location: login.php?success=2");
    exit();
}
?>

<?php
echo $my->html_footer();
?>