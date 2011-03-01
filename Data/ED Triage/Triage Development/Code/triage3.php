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

//Get Posted Answers, Store them in our session
if (isset($_POST)){
foreach ($_POST as $key => $value){
$_SESSION[$key] = mysql_real_escape_string($value);
}
}

//Get Next Question
//Get We Don't Have Paitent ID Yet
if (!isset($_SESSION["patient_id"])){
//We'll Get PatientID
$title="ID";
$prompt="Please Enter an Identifier for the Patient";
$definition="ironically, since this is hosted on a shared public server, you really shouldnt enter identifiable information";
$form='<label for = "patient_id">Patient ID:</label><input type="text" name="patient_id" id="patient_id" />';
}
elseif (!isset($_SESSION["age"])){
//Get Age
$title="Age";
$prompt="What Age Group Are You In?";
$definition="Is the patient an adult or a child? This will impact on possible presentation charts";
$form='<labels>Child:</label><input type="radio" name="age" value="child">
<labels>Adult:</label><input type="radio" name="age" value="adult">';
}
elseif (!isset($_SESSION["presentation"])){
$title="Presentation";
$prompt="What Is the Patient's Main Presentation";
$definition="This will determine which Presentational Chart we will be using";
$form='<select name="$presentation">';
$presentations = array("chest pain", "abdominal pain", "headache"); //Shoudl really get this from a database
foreach ($presentations as $presentation){
$form = $form."<option value=\"$presentation\">$presentation</option>";
};
$form = $form."</select>";
//Get Presentation
}
elseif (!isset($_SESSION["discriminators"])){
//Get Discriminator Array
///$discriminators = mysql_query("SELECT $presentation from TriageCategories WHERE TriageCategory!=0");
//Loop Through Array Until We Find an Unanswered Question
foreach (($_SESSION[$discriminators]) as $discriminator){
if (!isset($_SESSION[$discriminator])){
//Ask Discriminator
break;//once weve asked one question - break loop
}
}
}
elseif (!isset($_SESSION[$triage_category])){
}

else{//We have all the knowledge we need, Store answers, Present Advice
mysql_query("INSERT INTO Encounters (SessionId, EncounterId, PatientId) VALUES ('session_id()', null, '$_SESSION[$patient_id]' ");
mysql_query("INSERT INTO Patients(PatientId, Age) VALUES ('$_SESSION[$patient_id]', '$_POST[$age]')"); //Patient table is really just a placeholder currently


}
/*
$title=
$prompt=
$definition=
$form=
*/

mysql_close($con);
?>

</head>

<body>
<?php
foreach ($_SESSION as $key => $value){
print $key;
print $value;
}
?>
<h1><?php echo $title;?></h1>
<h2><?php echo $prompt;?></h2>
<p><?php echo $definition;?></p>
<form action="triage3.php" method="post">
<?php echo $form;?>

<input type="button" value="Previous" />
<input type="submit" value="Next" />
</form>
</body>

</html>