<?php

use App\Enums\Portfolio\PortfolioSocialAccount\PortfolioSocialAccountPostStatusEnum;
use App\Enums\Portfolio\PortfolioSocialAccount\PortfolioSocialAccountPostTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('social_posts', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('task_id');
            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');

            $table->string('slug');
            $table->dateTimeTz('start_at');
            $table->dateTimeTz('end_at')->nullable();
            $table->string('duration')->default(0);

            $table->unsignedBigInteger('portfolio_social_account_id');
            $table->foreign('portfolio_social_account_id')->references('id')->on('portfolio_social_accounts')->onDelete('cascade');

            $table->string('type')->default(PortfolioSocialAccountPostTypeEnum::POST->value);
            $table->string('status')->default(PortfolioSocialAccountPostStatusEnum::CREATING->value);

            $table->string('description')->nullable();
            $table->string('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portfolio_social_account_posts');
    }
};
