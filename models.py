from django.db import models


class Discriminator(models.Model):
	name = models.CharField(max_length = 200)
	definition = models.CharField(max_length = 1000)

	def __unicode__(self):
		return self.name

class Presentation(models.Model):
	name = models.CharField(max_length = 200)
	notes = models.CharField(max_length = 1000, blank = True)
	risk_limit = models.IntegerField()
	minimum_triage_category = models.IntegerField()
	age_group = models.IntegerField(choices = ((1, "Adult"), (2, "Child"), (3, "Adult/Child")))

	def __unicode__(self):
		return self.name

class PresentationDiscriminators(models.Model):
	presentation = models.ForeignKey(Presentation)
	discriminator = models.ForeignKey(Discriminator)
	triage_category = models.IntegerField()

	def __unicode__(self):
		return (self.presentation.name + "-:-" +self. discriminator.name)

