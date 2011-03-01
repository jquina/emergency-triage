<?php session_start(); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html>

<head>

<title>Manchester Triage System</title>

<meta name="description" content="Online Clinical Decision Support Software for Manchester Triage System" />

<?php

//Initialise Database
$con = mysql_connect("localhost","docsnote_triage","guinness1759");
mysql_select_db("docsnote_triage", $con);

foreach ($_POST as $key => $value){
$$key = mysql_real_escape_string($value);
}

if (isset($_SESSION['current_answer_title'])){

	if ($_SESSION['current_answer_title']="Patient ID"){
	mysql_query("INSERT INTO Encounters (SessionId, EncounterId, PatientId) VALUES ('session_id()', null, '$_POST[answer])'");
	$_SESSION[‘encounter_id’] = mysql_query("SELECT EncounterId FROM Encounters WHERE SessionId='session_id()'");		//set session's encounter_id
	$title="Age";
	$prompt="What age range are you in?";
	$definition="What age group are you in - We're really interested in whether youll need adult or child charts";
	$form=	'<label for="child">Child</label><input type="radio" name = "age" id = "child" value = "Child"/>
			 <label for="adult">Adult</label><input type="radio" name = "age" id = "adult" value = "Adult" />';
	}
	elseif ($_SESSION['current_answer_title']="Age"){
	mysql_query("INSERT INTO Patients(PatientId, Age) VALUES (null, $_POST[answer])"); //Patient table is really just a placeholder currently
	$_SESSION['current_answer_title']="Presentation";
	$title="Presentation";
	$prompt="What was the main problem that caused you to come to the hospital?";
	$definition="What was the main problem that caused the patient to come to the hospital?";
	$form=	'<label for="child">Child</label><input type="radio" name = "presentation" id = "child" value = "Child"/>
			 <label for="adult">Adult</label><input type="radio" name = "presentation" id = "adult" value = "Adult" />
			 ';
	}
	elseif ($_SESSION['current_answer_title']="Presentation"){//if the answer is a presentation , we can proceed to get the list of discriminators to ask
	mysql_query("UPDATE Encounters SET Presentation = '$_POST[answer]' WHERE SessionId='session_id()'");
	$_SESSION["presentation"]=$answer_title;
	$_SESSION['current_answer_title']="Discriminator";
	$title="Discriminator";
	$prompt="What is the first discriminator";
	$definition="This is predominantly a placeholder - Ironically, while were still on a public server, no identifiable details should be answered";
	$form='<input type="text" id="answer" name="answer" />
	<input type="hidden" name = "answer_title" value = "Discriminator"/>';
	}
	else{ 
	mysql_query("INSERT INTO Answers(EncounterId, DiscriminatorId, Answer) VALUES ('$_SESSION[encounter_id]', '$_POST[answer_title]', '$_POST[answer]'");
	}
}

else{//if theres nothing in POST ask first question
$_SESSION['current_answer_title'] = "Patient ID";
$title="Patient ID";
$prompt="What is the patient ID";
$definition="This is predominantly a placeholder - Ironically, while were still on a public server, no identifiable details should be answered";
$form='<input type="text" id="answer" name="answer" />
}
mysql_close($con);
?>

</head>

<body>
<h1><?php echo $title?></h1>
<h2><?php echo $prompt?></h2>
<p><?php echo $definition?></p>
<form action="triage2.php" method="POST">
<?php echo $form?>

<input type="button" value="Previous" />
<input type="submit" value="Next" />
</form>
</body>

</html>