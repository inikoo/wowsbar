git pull
git fetch . main:production
git push origin production
time vendor/bin/dep deploy
