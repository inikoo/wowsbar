<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 07 Dec 2023 15:20:45 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Auth\OrganisationUser;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];


    public function boot(): void
    {

        Auth::viaRequest('websockets-auth', function () {

            $id=Session::get('login_org_'.sha1('Illuminate\Auth\SessionGuard'));
            if (!is_null($id)) {
                return OrganisationUser::find($id);
            }
            //... todo: try other guards

            return false;




        });


    }
}
