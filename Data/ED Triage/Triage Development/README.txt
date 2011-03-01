Tasks: 
If value in $_POST
	store value

If no Patient ID
	Ask Patient ID (set encounter id at same time)
If no Age
	Ask Age
If no Presentation
	Ask Presentation

If no Discrimnator Array
	Get Discriminator List
	Else 
	Ask Next Discriminator
	If Discriminator Answer = Yes
		Apply Relevant Triage Category
If No Discriminators Left to be Asked
	Apply Minimum Triage Category

Advise Triage Category, Time Limits and Location

Functions:
HTML Template


-----------------------------------------------------------------

Tables:
Encounters:			Encounter ID, Patient ID, Presentation ID, Triage Category, Discriminant_ID

Patients:			Patient ID, Age

Answers:			*Encounter ID, *Discrimator ID, Answer

###

Triage Category:		Presentation ID, Discriminator ID, Triage Category

Discriminator Definitions:	Discriminator ID, Discriminator Keyword, Discriminator Definition
Presentation Definitions:	Presentation ID, Presentation Keyword, Presentation Definition

-----------------------------------------------------------------

Further:
User Login/ Customisation
Data Warehouse for Data Not Currently in Session