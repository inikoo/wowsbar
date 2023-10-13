<?php

use App\Actions\Organisation\OrganisationUser\UI\ShowResetPasswordUsers;
use App\Actions\Organisation\OrganisationUser\UpdateOrganisationUser;

Route::get('reset/password', ShowResetPasswordUsers::class)->name('reset.password');
Route::patch('reset/password', [UpdateOrganisationUser::class, 'inLoggedUser'])->name('update.password');
