from django.shortcuts import render
from django.urls import reverse_lazy
from django.views import generic
from .forms import SignUpForm

class SignUp(generic.CreateView):
    form_class = SignUpForm
    success_url = reverse_lazy('accounts/login/')
    template_name = 'signup.html'
