from django.contrib import admin
from django.urls import include, path
from django.contrib.auth.views import login, logout
from chat.views import index


urlpatterns = [
    path('admin/', admin.site.urls),
    path('', index),
    path('users/', include('django.contrib.auth.urls')),
    path('users/', include('users.urls')),
    path('accounts/login/', login),
    path('accounts/logout/', logout),
]
