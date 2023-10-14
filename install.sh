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
php artisan migrate
php artisan db:seed
php artisan telescope:clear
pg_dump -Fc -f "devops/devel/snapshots/fresh.dump" ${DB}
echo "🏢 create organisation and guests"
php artisan org:create wowsbar Wowsbar ID GBP
php artisan org:create-guest 'Mr Aiku' aiku external_administrator -e aiku@inikoo.com
php artisan guest:import -g wowsbar/data-sets/guests

echo "🌱 create shop/website"
php artisan shop:create awa 'aw-advantage' 'digital-marketing'
php artisan shop:new-website awa 'awa.test'
php artisan website:launch awa
pg_dump -Fc -f "devops/devel/snapshots/website.dump" ${DB}
echo "🌱 create catalogue"
php artisan department:import -g wowsbar/data-sets/departments
php artisan product:import -g wowsbar/data-sets/products
pg_dump -Fc -f "devops/devel/snapshots/catalogue.dump" ${DB}

echo "🌱 create customers"
php artisan customer:import -g wowsbar/data-sets/customers
php artisan shop:new-customer awa aiku@inikoo.com -C 'Aiku' -P hello -N 'Mr Aiku'
php artisan shop:new-customer awa devs@aw-advantage.com -C 'aw-advantage' -P hello -N 'Mr Dev'
#php artisan customer:new-user aiku -P hello -N 'Mary'
#php artisan customer:new-user aw-advantage  -P hello -N 'Zoe'
pg_dump -Fc -f "devops/devel/snapshots/customers.dump" ${DB}
php artisan customer-website:import -g wowsbar/data-sets/customer-websites

echo "🌱 create test website with a banner"
#php artisan customer:new-portfolio-website aiku http://hello.com 'My website 😸'
#php artisan customer:new-banner aiku mw -N 'My first banner 🫡'
php artisan customer:new-banner aiku mw
pg_dump -Fc -f "devops/devel/snapshots/portfolio.dump" ${DB}
echo "🌱 Importing HR"
php artisan workplace:create "Beach bar" hq
php artisan employee:import -g wowsbar/data-sets/employees
pg_dump -Fc -f "devops/devel/snapshots/hr.dump" ${DB}


#php artisan shop:import-prospects awa database/seeders/uploads/local/prospects.xlsx
echo "🛃 Organisation prospects imported"


