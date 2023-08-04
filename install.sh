#
# Author: Raul Perusquia <raul@inikoo.com>
# Created: Sat, 08 Jul 2023 17:28:57 Malaysia Time, Kuala Lumpur, Malaysia
# Copyright (c) 2023, Raul A Perusquia Flores
#

DB=wowsbar
BACKUP_DB=wowsbar_backup_elasticsearch

echo -e "🧼 Cleaning storage"
rm -rf public/tenants
rm -rf storage/app/tenants
echo -e "✨ Resetting databases ${ITALIC}${DB}${NONE}"
dropdb --force --if-exists ${DB}
createdb --template=template0 --lc-collate="${DB_COLLATE}" --lc-ctype="${DB_COLLATE}" ${DB}
dropdb --force --if-exists ${BACKUP_DB}
createdb --template=template0 --lc-collate="${DB_COLLATE}" --lc-ctype="${DB_COLLATE}" ${BACKUP_DB}
echo -e "✨ Resetting elasticsearch"
php artisan es:refresh
#echo -e "✨ Resetting firebase"
#php artisan firebase:flush
echo -e "✨ Installing dependencies"
composer install
npm install
echo "🌱 Migrating and seeding database"
php artisan migrate --database=backup --path=database/migrations/backup
php artisan migrate --path=database/migrations/landlord
php artisan migrate --path=database/migrations/tenant
php artisan db:seed
pg_dump -Fc -f "devops/devel/snapshots/fresh.dump" ${DB}
echo "🌱 create devel tenant"
php artisan tenant:create aiku devels@aw-advantage.com Devs aiku hello GB GBP
php artisan tenant:create test1 dev@aw-advantage.com Devs test1 hello GB GBP
pg_dump -Fc -f "devops/devel/snapshots/tenant.dump" ${DB}
echo "🌱 create test website with a banner"
php artisan website:create aiku hello.com hello 'My website 😸'
php artisan content-block:create aiku hello banner test1 'My first banner 🫡'
pg_dump -Fc -f "devops/devel/snapshots/portfolio.dump" ${DB}
