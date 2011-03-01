#note sql values start at 1

oldfile = open('triage_presentation_discriminator.csv', 'r')
newfile = open('triage_presentation_discriminator_optimised.csv', 'w')

new_row_index = 1
old_row_index = 1
for line in oldfile:
	old_column_index = 1
	value_list = line.split(",") #split using commas
	for value in value_list:
		if value != "0":
			newline = str(new_row_index) + "," + str(old_column_index) + "," + str(old_row_index) + "," + str(value) + "\n"
			newfile.write(newline)
			new_row_index+=1
		old_column_index+=1

	old_row_index+=1

raw_input("Press Enter")
	
