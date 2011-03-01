from django.conf.urls.defaults import *


urlpatterns = patterns('triage.views',
	(r'^$', 'index'),
)
