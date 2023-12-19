<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 20 Dec 2023 13:42:18 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Actions\Helpers\Tag\GetTagOptions;
use App\Actions\Leads\Prospect\GetProspectOptions;
use App\Actions\Mail\EmailTemplate\GetEmailTemplateCompiledLayout;
use App\Actions\Mail\EmailTemplate\GetEmailSeededTemplates;
use Illuminate\Support\Facades\Route;

Route::get('tags', GetTagOptions::class)->name('tags');
Route::get('prospects', GetProspectOptions::class)->name('prospects');
Route::get('email/templates/seeded', GetEmailSeededTemplates::class)->name('email_templates.seeded');
//Route::get('email/templates/seeded', GetEmailSeededTemplates::class)->name('email_templates.seeded');

Route::get('email/templates/{emailTemplate:id}/compiled_layout', GetEmailTemplateCompiledLayout::class)->name('email_templates.show.compiled_layout');
