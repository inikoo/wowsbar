#
# Author: Raul Perusquia <raul@inikoo.com>
# Created: Sat, 08 Jul 2023 17:28:57 Malaysia Time, Kuala Lumpur, Malaysia
# Copyright (c) 2023, Raul A Perusquia Flores
#

DB=wowsbar
BACKUP_DB=wowsbar_backup

echo -e "🧼 Cleaning storage"
rm -rf public/tenants
rm -rf storage/app/tenants
rm -rf storage/app/media
echo -e "✨ Resetting databases ${ITALIC}${DB}${NONE}"
dropdb --force --if-exists ${DB}
createdb --template=template0 --lc-collate="${DB_COLLATE}" --lc-ctype="${DB_COLLATE}" ${DB}
dropdb --force --if-exists ${BACKUP_DB}
createdb --template=template0 --lc-collate="${DB_COLLATE}" --lc-ctype="${DB_COLLATE}" ${BACKUP_DB}
echo -e "✨ Resetting elasticsearch"
php artisan es:refresh
echo -e "✨ Resetting firebase"
php artisan firebase:flush
echo "Public assets link 🔗"
php artisan storage:link
echo "Clear horizon 🧼"
php artisan horizon:clear
php artisan horizon:terminate
echo "Clear cache 🧼"
php artisan cache:clear
redis-cli KEYS "wowsbar_database_*" | xargs redis-cli DEL
echo "🌱 Migrating and seeding database"
php artisan migrate --database=backup --path=database/migrations/backup
php artisan migrate --path=database/migrations/landlord
php artisan migrate --path=database/migrations/tenant
php artisan db:seed
php artisan telescope:clear
pg_dump -Fc -f "devops/devel/snapshots/fresh.dump" ${DB}
echo "🏢 create organisation"
php artisan org:create wowsbar Wowsbar ID GBP
php artisan org:create-guest aiku aiku external_administrator
echo "🌱 create shop/website"
php artisan shop:create awa 'aw-advantage' 'digital-marketing'
php artisan shop:new-website awa 'awa.test'
php artisan website:change-state awa launch



echo "🌱 create customer"
php artisan shop:new-customer awa aiku@inikoo.com -C 'Aiku'
php artisan shop:new-customer awa devs@aw-advantage.com -C 'aw-advantage'
php artisan customer:new-user aiku -u aiku -P hello -N 'Mary'
php artisan customer:new-user aw-advantage -u aiku2 -P hello -N 'Zoe'
pg_dump -Fc -f "devops/devel/snapshots/customers.dump" ${DB}
echo "🌱 create test website with a banner"
php artisan customer:new-portfolio-website aiku hello.com hello 'My website 😸'
php artisan customer:new-banner aiku test1 'My first banner 🫡' hello
php artisan customer:new-banner aiku test2 'My first banner without website 🫡'
pg_dump -Fc -f "devops/devel/snapshots/portfolio.dump" ${DB}
php artisan workplace:create "Beach bar" hq
php artisan employee:import employees.xlsx
echo "🌱 All the employees are imported"
php artisan shop:import-prospects awa prospects.xlsx
echo "🛃 Organisation prospects imported"
