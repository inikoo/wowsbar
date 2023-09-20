<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 08 Jul 2023 17:13:41 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http;

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CheckWebsiteState;
use App\Http\Middleware\DetectWebsite;
use App\Http\Middleware\EncryptCookies;
use App\Http\Middleware\HandleDeliveryInertiaRequests;
use App\Http\Middleware\HandleCustomerInertiaRequests;
use App\Http\Middleware\HandleOrgInertiaRequests;
use App\Http\Middleware\HandlePublicInertiaRequests;
use App\Http\Middleware\LogUserFirebaseMiddleware;
use App\Http\Middleware\LogUserRequestMiddleware;
use App\Http\Middleware\OrgAuthenticate;
use App\Http\Middleware\PreventRequestsDuringMaintenance;
use App\Http\Middleware\PublicAuthenticate;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\SetCustomerID;
use App\Http\Middleware\TrimStrings;
use App\Http\Middleware\TrustProxies;
use App\Http\Middleware\ValidateSignature;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Auth\Middleware\RequirePassword;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Foundation\Http\Middleware\ValidatePostSize;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Illuminate\Http\Middleware\HandleCors;
use Illuminate\Http\Middleware\SetCacheHeaders;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

class Kernel extends HttpKernel
{
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        TrustProxies::class,
        HandleCors::class,
        PreventRequestsDuringMaintenance::class,
        ValidatePostSize::class,
        TrimStrings::class,
        ConvertEmptyStringsToNull::class,
    ];


    protected $middlewareGroups = [

        'delivery' => [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class,
            HandleDeliveryInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ],
        'org-web' => [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class,
            HandleOrgInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
            LogUserFirebaseMiddleware::class,
            LogUserRequestMiddleware::class
        ],
        'public' => [
            DetectWebsite::class,
            CheckWebsiteState::class,
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class,
            HandlePublicInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ],

        'customer' => [
            DetectWebsite::class,
            CheckWebsiteState::class,
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class,
            SetCustomerID::class,
            HandleCustomerInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
            LogUserFirebaseMiddleware::class,
            LogUserRequestMiddleware::class
        ],



        'webhooks-api' => [
            EnsureFrontendRequestsAreStateful::class,
            SubstituteBindings::class,
        ],

        'customer-api' => [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            ThrottleRequests::class.':api',
            SubstituteBindings::class,
        ],



    ];


    protected $middlewareAliases = [
        'auth'             => Authenticate::class,
        'org-auth'         => OrgAuthenticate::class,
        'public-auth'      => PublicAuthenticate::class,
        'auth.basic'       => AuthenticateWithBasicAuth::class,
        'auth.session'     => AuthenticateSession::class,
        'cache.headers'    => SetCacheHeaders::class,
        'can'              => Authorize::class,
        'guest'            => RedirectIfAuthenticated::class,
        'password.confirm' => RequirePassword::class,
        'precognitive'     => HandlePrecognitiveRequests::class,
        'signed'           => ValidateSignature::class,
        'throttle'         => ThrottleRequests::class,
        'verified'         => EnsureEmailIsVerified::class,
    ];
}
