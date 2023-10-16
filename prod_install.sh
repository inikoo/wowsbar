#! /bin/bash
git fetch . main:production
git push origin production
#  time dep install:clean-supervisor
time vendor/bin/dep install
