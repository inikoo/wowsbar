<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 30 Oct 2023 16:11:55 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Mail\Mailshot;

use App\Enums\Mail\MailshotStateEnum;
use App\Models\Mail\Mailshot;
use App\Models\Market\Shop;
use Illuminate\Http\RedirectResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\AsCommand;

class SendMailshot
{
    use AsCommand;
    use AsAction;

    public function handle(Mailshot $mailshot, array $modelData): Mailshot
    {
        if (!$mailshot->start_sending_at) {
            data_set($modelData, 'start_sending_at', now());
        }
        data_set($modelData, 'state', MailshotStateEnum::SENDING);

        $mailshot->update($modelData);

        ProcessSendMailshot::dispatch($mailshot);

        return $mailshot;
    }

    public function htmlResponse(Mailshot $mailshot): RedirectResponse
    {

        /** @var Shop $scope */
        $scope=$mailshot->scope;

        return redirect()->route(
            'org.crm.shop.prospects.mailshots.show',
            array_merge(
                [
                    $scope->slug,
                    $mailshot->slug
                ],
                [
                    '_query' => [
                        'tab' => 'showcase'
                    ]
                ]
            )
        );
    }

    public function asController(Mailshot $mailshot, ActionRequest $request): Mailshot
    {
        if ($mailshot->state == MailshotStateEnum::IN_PROCESS) {
            $mailshot = SetMailshotAsReady::make()->action($mailshot, [
                'publisher_id' => $request->user()->id,
            ]);
        }

        $request->validate();

        return $this->handle($mailshot, $request->validated());
    }

}
