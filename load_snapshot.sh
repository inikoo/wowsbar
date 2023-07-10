pg_restore -j 15  -U aiku -c -d wowsbar  "devops/devel/snapshots/$1.dump"
echo "$1"
