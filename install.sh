#
# Author: Raul Perusquia <raul@inikoo.com>
# Created: Sat, 08 Jul 2023 17:28:57 Malaysia Time, Kuala Lumpur, Malaysia
# Copyright (c) 2023, Raul A Perusquia Flores
#

DB=wowsbar

echo -e "✨ Resetting database ${ITALIC}${DB}${NONE}"
dropdb --force --if-exists ${DB}
createdb --template=template0 --lc-collate="${DB_COLLATE}" --lc-ctype="${DB_COLLATE}"  ${DB}
echo -e "✨ Installing dependencies"
composer install
npm install
echo "🌱 Migrating and seeding database"
php artisan migrate --path=database/migrations/landlord
php artisan migrate --path=database/migrations/tenant
php artisan db:seed
pg_dump -Fc -f "devops/devel/snapshots/fresh.dump" ${DB}
echo "🌱 create devel tenant"
php artisan tenant:create wow devels@aw-advantage.com Devs aiku hello GB GBP
pg_dump -Fc -f "devops/devel/snapshots/tenant.dump" ${DB}

