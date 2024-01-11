<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 04 Aug 2023 02:22:40 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Exceptions;

use App\Actions\UI\Customer\GetFirstLoadProps;
use App\Http\Resources\UI\LoggedUserResource;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];


    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            if (app()->bound('sentry')) {
                app('sentry')->captureException($e);
            }
        });
    }

    public function render($request, Throwable $e): Response|JsonResponse|\Symfony\Component\HttpFoundation\Response|RedirectResponse
    {
        $response = parent::render($request, $e);

        if (!app()->environment(['local', 'testing'])
            && in_array($response->status(), [500, 503, 404, 403, 422])
            && !(!$request->inertia() && $request->expectsJson())
        ) {
            $errorData = match ($response->status()) {
                403 => [
                    'status'      => $response->status(),
                    'title'       => __('Forbidden'),
                    'description' => __('Sorry, you are forbidden from accessing this page.')
                ],
                404 => [
                    'status'      => $response->status(),
                    'title'       => __('Page Not Found'),
                    'description' => __('Sorry, the page you are looking for could not be found.')
                ],
                422 => [
                    'status'      => $response->status(),
                    'title'       => __('Unprocessable request'),
                    'description' => __('Sorry, is impossible to process this page.')
                ],
                503 => [
                    'status'      => $response->status(),
                    'title'       => __('Service Unavailable'),
                    'description' => __('Sorry, we are doing some maintenance. Please check back soon.')
                ],
                default => $this->getExceptionInfo()
            };

            if (Auth::check()) {
                $user = $request->user();

                $errorData = match (class_basename($user)) {
                    'User' => array_merge(
                        GetFirstLoadProps::run($request->get('customerUser')),
                        $errorData,
                        [
                            'auth' => [
                                'user' => $request->user() ? new LoggedUserResource($user) : null,
                            ],
                        ]
                    ),
                    default => []
                };
            }

            $host = Request::getHost();

            if ($host == config('app.delivery_domain')) {
                Inertia::setRootView('app-delivery');
                $app='delivery';
            } elseif ($host == config('app.domain')) {
                Inertia::setRootView('app-organisation');
                $app='org';
            } else {
                $path = Request::path();
                if (preg_match('/^app\//', $path)) {
                    Inertia::setRootView('app-customer');
                    $app='customer';
                } else {
                    Inertia::setRootView('app-public');
                    $app='public';
                }
            }


            return Inertia::render(
                $this->getInertiaPage($e, $app),
                $errorData
            )
                ->toResponse($request)
                ->setStatusCode($response->status());
        } elseif ($response->status() === 419) {
            return back()->with([
                'message' => 'The page expired, please try again.',
            ]);
        }

        return $response;
    }

    public function getExceptionInfo(): array
    {
        return [
            'status'      => 500,
            'title'       => __('Server Error'),
            'description' => __('Whoops, something went wrong on our servers.')
        ];
    }

    public function getInertiaPage(Throwable $e, string $app): string
    {
        if (get_class($e) == 'Exceptions\NoCustomer' and $app=='customer') {
            return 'Utils/CustomerNotFound';
        }

        $page='Utils/Error';

        if($app=='org' or $app=='customer') {
            $page= Auth::check() ? 'Utils/ErrorInApp' : 'Utils/Error';
        }

        return $page;

    }

}
