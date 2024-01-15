<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 20 Dec 2023 13:42:18 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Actions\Helpers\Html\GetImageFromHtml;
use App\Actions\Helpers\Tag\GetTagOptions;
use App\Actions\Leads\Prospect\GetProspect;
use App\Actions\Leads\Prospect\Mailshots\GetMailshotRecipeProspects;
use App\Actions\Leads\Prospect\SearchProspects;
use App\Actions\Mail\EmailTemplate\GetEmailTemplateCompiledLayout;
use App\Actions\Mail\EmailTemplate\GetOutboxEmailTemplates;
use App\Actions\Mail\EmailTemplate\GetSeededEmailTemplates;
use App\Actions\Mail\Mailshot\GetMailshotMergeTags;
use Illuminate\Support\Facades\Route;

Route::get('tags', GetTagOptions::class)->name('tags');
Route::get('prospects', SearchProspects::class)->name('prospects.search');
Route::get('prospects/{prospect:id}', GetProspect::class)->name('prospects.show');

Route::get('email/templates/seeded', GetSeededEmailTemplates::class)->name('email_templates.seeded');
Route::get('email/templates/outboxes/{outbox:id}', GetOutboxEmailTemplates::class)->name('email_templates.outbox');
Route::get('email/templates/{emailTemplate:id}/compiled_layout', GetEmailTemplateCompiledLayout::class)->name('email_templates.show.compiled_layout');
Route::get('/mailshot/{mailshot:id}/merge-tags', GetMailshotMergeTags::class)->name('mailshot.merge-tags');

Route::get('/html/render', GetImageFromHtml::class)->name('html.render');
Route::get('/mailshot/{mailshot:id}/recipe-prospects', GetMailshotRecipeProspects::class)->name('mailshot.recipe-prospects');
