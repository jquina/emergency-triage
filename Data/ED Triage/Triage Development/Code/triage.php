<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html>

<head>

<title>Manchester Triage System</title>

<meta name="description" content="Online Clinical Decision Support Software for Manchester Triage System" />

<?php

//Initialise Database and Session
$con = mysql_connect("localhost","triage","guinness1759");
mysql_select_db("triage", $con);

session_start();

//Put Form Data into Answers Database
if (isset($_POST)){
//Process Previous Answers
//Get Next Question
//Should add a session variable of what we asked last(ie. most recently), instead of reading all these databases

if(!isset($_SESSION[‘encounter_id’])){ //if we dont have a session id - start at the beginning
mysql_query("INSERT INTO Encounters (Id, SessionId) VALUES (null, session_id())");
$encounter_id = mysql_query("SELECT EncounterId FROM Encounters WHERE SessionId='session_id()'");
$_SESSION[‘encounter_id’] = $encounter_id;
$title = "Patient ID"
$prompt = "What Is The Patient's Identification Number"
$definition = "Ironically, for security reasons, you shouldnt insert any form of identification here"
$form = ""
}
$encounter_id = $_SESSION[‘encounter_id’];

$patient_id = mysql_query("SELECT PatientId FROM Encounters WHERE EncounterId='$_SESSION[‘encounter_id’]'");

$age_range = mysql_query("SELECT Age FROM Patients WHERE PatientId = $patient_id");
elseif(empty($age_range)){
$title = "Presentation"
$prompt = "What Was The Main Problem That Caused You To Come To Hospital"
$definition = "Pretty Self Explanatory, Pick the Closest Presentation"
$form = ""
//Insert into Patients
}

$presentation_id = mysql_query("SELECT PresentationId FROM Encounters WHERE EncounterId='$_SESSION[‘encounter_id’]'");
elseif(empty($presentation_id)){
//Get Presentation ID
$title = "Presentation"
$prompt = "What Was The Main Problem That Caused You To Come To Hospital"
$definition = "Pretty Self Explanatory, Pick the Closest Presentation"
$form = ""
}

$triage_category = mysql_query("SELECT TriageCategory FROM Encounters WHERE EncounterId='$_SESSION[‘encounter_id’]'");

elseif(empty($triage_category)){
//Get Triage Category
$discriminators = mysql_query("SELECT DiscriminatorId FROM TriageCategory WHERE PresentationId='$presentation_id' ORDER BY TriageCategory, DiscriminatorId");
foreach ($discriminators as $discriminator_id){
$discriminator_answer = mysql_query("SELECT Answer FROM Answers WHERE EncounterId='$encounter_id' AND DiscriminatorId='$discriminator_id'");
if (empty($discriminator_answer)){
$discriminator_details = mysql_query("SELECT * FROM DiscriminatorDefinitions WHERE DiscriminatorId='$discriminator_id'");
$title = $discriminator_details["Keyword"]
$prompt = $discriminator_details["Question"]
$definition = $discriminator_details["Definition"]
break;
}
}
}

elseif(!empty($triage_category)){
//display appropriate advice
$title = $triage_category
$prompt = $triage_prompt
$definition = $triage_advice //might add in location, time should be seen in etc. 
}

mysql_close($con);

function YesNoForm(){
//Generate Form with Yes and No Radio - Buttons / Real Buttons (would be lovely to have user customisation)
return "<label for="yes">Yes</label><input type="radio" name = "yes_no" id = "yes" value = "Yes"/>
<label for="no">No</label><input type="radio" name = "yes_no" id = "no" value = "No" />"
}

?>

</head>

<body>
<h1>Question Title</h1>
<h2>Question Prompt</h2>
<p>Question Definition D</p>
<form action="triage.php" method="POST">
Form Elements (probably from php functions)

<input type="button" value="Previous" />
<input type="submit" value="Next" />
</form>
</body>

</html>