<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 02 Nov 2023 15:07:11 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\SourceFetch\Aurora;

use App\Actions\Helpers\Fetch\StoreFetch;
use App\Actions\Helpers\Fetch\UpdateFetch;
use App\Actions\Helpers\Query\HydrateModelTypeQueries;
use App\Enums\Helpers\Fetch\FetchTypeEnum;
use App\Models\Helpers\Fetch;
use App\Services\AuroraService;
use App\Services\SourceService;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Lorisleiva\Actions\Concerns\AsAction;
use Symfony\Component\Console\Helper\ProgressBar;

class FetchAction
{
    use AsAction;


    protected int $counter = 0;

    protected ?ProgressBar $progressBar;
    protected ?int $auShop;
    protected array $with;
    protected bool $onlyNew = false;

    protected int $hydrateDelay = 0;
    protected int $number_stores;
    protected int $number_updates;
    protected int $number_no_changes;
    protected int $number_errors;

    protected Fetch $fetch;

    public function __construct()
    {
        $this->progressBar       = null;
        $this->auShop            = null;
        $this->with              = [];
        $this->number_stores     = 0;
        $this->number_updates    = 0;
        $this->number_no_changes = 0;
        $this->number_errors     = 0;
    }

    public function handle(SourceService $source, int $sourceId): ?Model
    {
        return null;
    }

    public function getModelsQuery(): ?Builder
    {
        return null;
    }

    public function fetchAll(SourceService $source, Command $command = null): void
    {
        $this->getModelsQuery()->chunk(10000, function ($chunkedData) use ($command, $source) {
            foreach ($chunkedData as $auroraData) {
                if ($command && $command->getOutput()->isDebug()) {
                    $command->line("Fetching: ".$auroraData->{'source_id'});
                }
                $model = $this->handle($source, $auroraData->{'source_id'});
                unset($model);
                $this->progressBar?->advance();
            }
        });
    }

    public function count(): ?int
    {
        return null;
    }

    public function reset(): void
    {
    }

    public function getSource(): AuroraService
    {
        return new AuroraService();
    }

    public function asCommand(Command $command): int
    {
        $this->hydrateDelay = 120;


        if ($command->getName() == 'fetch:prospects' and $command->option('shop')) {
            $this->auShop = $command->option('shop');
        }

        if ($command->getName() == 'fetch:prospects' and $command->option('only_new')) {
            $this->onlyNew = (bool)$command->option('only_new');
        }


        try {
            $source = $this->getSource();
        } catch (Exception $exception) {
            $command->error($exception->getMessage());

            return 1;
        }

        $source->initialisation($command->argument('au_database'));

        if ($command->option('reset')) {
            $this->reset();
        }

        $this->fetch = StoreFetch::run(
            [
                'type' => $this->getFetchType($command),
                'data' => [
                    'command'   => $command->getName(),
                    'arguments' => $command->arguments(),
                    'options'   => $command->options(),
                ]
            ]
        );


        $command->info('');

        if ($command->option('source_id')) {
            UpdateFetch::run($this->fetch, ['number_items' => 1]);
            $this->handle($source, $command->option('source_id'));
        } else {
            $numberItems = $this->count() ?? 0;
            UpdateFetch::run($this->fetch, ['number_items' => $numberItems]);
            if (!$command->option('quiet') and !$command->getOutput()->isDebug()) {
                $info = '✊ '.$command->getName();
                if ($this->auShop) {
                    $info .= ' shop:'.$this->auShop;
                }
                $command->line($info);

                $this->progressBar = $command->getOutput()->createProgressBar($numberItems);
                $this->progressBar->setFormat('debug');
                $this->progressBar->start();
            } else {
                $command->line('Steps '.number_format($numberItems));
            }

            $this->fetchAll($source, $command);
            $this->progressBar?->finish();
        }

        if ($command->getName() == 'fetch:prospects') {
            HydrateModelTypeQueries::run('Prospect');
        }
        UpdateFetch::run($this->fetch, ['finished_at' => now()]);

        return 0;
    }


    private function getFetchType(Command $command): ?FetchTypeEnum
    {
        return match ($command->getName()) {
            'fetch:prospects' => FetchTypeEnum::PROSPECTS,
            default           => null
        };
    }

}
