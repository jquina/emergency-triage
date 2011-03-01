##IMPORTING STUFF
from string import Template

from django.shortcuts import render_to_response
from django.http import HttpResponse, HttpResponseRedirect
from django.template import RequestContext

from triage.models import Discriminator, Presentation, PresentationDiscriminators


##CONSTANTS
TRIAGE_COLORS = ('red', 'orange', 'yellow', 'green', 'blue')

def index(request):
#	request.session.flush()
#	return HttpResponse("Hello")
	
        
##  PROCESS POST FORM  ##
        if request.method=='POST': 
                if "quit_flag" in request.POST:                             #If we need to restart 
                        request.session.flush()                         #flush session
                else:
                        for k, v in request.POST.iteritems():
                                request.session[k] = v
                                if v=="Yes":
                                        request.session["discriminant"] = k
                                        discriminant = PresentationDiscriminators.objects.get(presentation__name = request.session["Presentation"], discriminator__name = request.session["discriminant"])
                                        request.session['triage_category'] = discriminant.triage_category

                        if (("Presentation" in request.session.keys()) and ("discriminator_list" not in request.session.keys())):
                                x = PresentationDiscriminators.objects.filter(presentation__name = request.session["Presentation"]).order_by("triage_category")
                                discriminator_list=[]
                                for record in x:
                                        discriminator_list.append(record.discriminator.name)
                                request.session["discriminator_list"] = discriminator_list

                        if (("discriminators_finished" in request.session.keys()) and ("triage_category" not in request.session.keys())):     
                                request.session['discriminant'] = "Minimum"
                                presentation = Presentation.objects.get(name = request.session["Presentation"])
                                request.session["triage_category"] = presentation.minimum_triage_category
                return HttpResponseRedirect("")
        
##  GET  DISPLAY  ##
        else:
                if "triage_category" in request.session.keys():     
                        triage_color = get_triage_color(request.session["triage_category"])
                        return render_to_response("triage_result.html", {"triage_color":triage_color, "triage_category": request.session['triage_category'], "presentation": request.session["Presentation"], "discriminant": request.session["discriminant"]}, RequestContext(request))
                
        ##AGE
                elif "Age" not in request.session.keys():
                        prompt = "For the sake of arguement we'll call over 16 an adult"
                        choices = ["Adult", "Child"]
                        return render_to_response("triage_question.html", {"choices": choices, "prompt": prompt, "discriminator":"Age"}, RequestContext(request))

        ##PRESENTATION
                elif "Presentation" not in request.session.keys():                        
                        if request.session["Age"]=="Child":
                                age_number_list = (2,3)
                        elif request.session["Age"]=="Adult":
                                age_number_list = (1,3)
                        else:
                                age_number_list = (1,2,3)

                        prompt="Which of these presentations is closest to the current presentation"

                        choices=[]  
                        presentations = Presentation.objects.filter(age_group__in = age_number_list)
                        for presentation in presentations:
                                choices.append(presentation)

                        return render_to_response("triage_question.html", {"discriminator": "Presentation", "choices": choices, "prompt": prompt}, RequestContext(request))
                
        ##DISCRIMINATORS
     

                elif "discriminators_finished" not in request.session.keys():
                        list_length = len(request.session["discriminator_list"])
                        for count, discriminator in enumerate(request.session["discriminator_list"]):
                                if discriminator not in request.session.keys():
                                        discriminator_queryset = Discriminator.objects.get(name=discriminator)
                                        prompt=discriminator_queryset.definition
                                        choices=["Yes", "No"]
                                        if count==(list_length - 1):
                                                request.session["discriminators_finished"] = "True"
                                        return render_to_response("triage_question.html", {"discriminator": discriminator, "prompt": prompt, "choices": choices}, RequestContext(request))
                        
                else:
                        return render_to_response("triage_question.html", {}, RequestContext(request))
                        return HttpResponse("Hello World")


##  FUNCTIONS  ##

def get_triage_color(triage_category):
        return TRIAGE_COLORS[int(triage_category)-1]
