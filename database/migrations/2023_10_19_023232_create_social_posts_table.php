<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 24 Oct 2023 16:03:24 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Enums\Portfolio\PortfolioSocialAccount\PortfolioSocialAccountPostStatusEnum;
use App\Enums\Portfolio\PortfolioSocialAccount\PortfolioSocialAccountPostTypeEnum;
use App\Stubs\Migrations\HasSoftDeletes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    use HasSoftDeletes;

    public function up(): void
    {
        Schema::create('social_posts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('task_id')->index();
            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
            $table->string('slug')->unique()->collation('und_ns');
            $table->dateTimeTz('start_at');
            $table->dateTimeTz('end_at')->nullable();
            $table->unsignedInteger('duration')->default(0)->comment('days');
            $table->unsignedSmallInteger('portfolio_social_account_id')->index();
            $table->foreign('portfolio_social_account_id')->references('id')->on('portfolio_social_accounts')->onDelete('cascade');
            $table->string('type')->default(PortfolioSocialAccountPostTypeEnum::POST->value)->index();
            $table->string('status')->default(PortfolioSocialAccountPostStatusEnum::CREATING->value)->index();
            $table->string('description')->nullable();
            $table->string('notes')->nullable();
            $table->timestampsTz();
            $this->softDeletes($table);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_posts');
    }
};
