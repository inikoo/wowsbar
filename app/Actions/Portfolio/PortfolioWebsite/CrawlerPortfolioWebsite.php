<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\PortfolioWebsite;

use App\Enums\Portfolio\PortfolioWebpage\PortfolioWebpageStatusEnum;
use App\Models\Portfolio\PortfolioWebpage;
use App\Models\Portfolio\PortfolioWebsite;
use DOMDocument;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Console\Command;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\AsCommand;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Spatie\Crawler\Crawler;
use Spatie\Crawler\CrawlObservers\CrawlObserver;

class CrawlerPortfolioWebsite extends CrawlObserver
{
    use WithAttributes;
    use AsCommand;
    use AsAction;

    public string $commandSignature = 'portfolio-website:crawler {url}';
    public ?string $content         = null;

    public function handle(string $url): int
    {
        try {
            Crawler::create()
                ->acceptNofollowLinks()
                ->ignoreRobots()
                ->setCrawlObserver($this)
                ->startCrawling($url);

            return 0;
        } catch (\Exception $e) {
            echo $e->getMessage() . "\n";

            return 1;
        }
    }

    public function asController(PortfolioWebsite $portfolioWebsite, ActionRequest $request): int
    {
        $request->validate();

        return $this->handle($portfolioWebsite->url);
    }

    public function asCommand(Command $command): int
    {
        $this->handle($command->argument('url'));

        return 0;
    }

    public function crawled(UriInterface $url, ResponseInterface $response, UriInterface $foundOnUrl = null, string $linkText = null): void
    {
        $doc = new DOMDocument();
        if (!blank($response->getBody())) {
            @$doc->loadHTML($response->getBody());
            $title = $doc->getElementsByTagName("title")[0]->nodeValue;
            $html  = $doc->saveHTML();

            PortfolioWebpage::create([
                'title'  => $title,
                'url'    => $url,
                'layout' => $html
            ]);

            echo "🫡 " . $title . " success crawled \n";
        }
    }

    public function crawlFailed(UriInterface $url, RequestException $requestException, UriInterface $foundOnUrl = null, string $linkText = null): void
    {
        PortfolioWebpage::create([
            'title'   => '',
            'url'     => $url,
            'layout'  => [],
            'status'  => PortfolioWebpageStatusEnum::FAILED,
            'message' => $requestException->getMessage()
        ]);
    }
}
