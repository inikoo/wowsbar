<?php

use App\Enums\Portfolio\ContentBlock\ContentBlockStateEnum;
use App\Enums\Web\WebBlockType\WebBlockTypeSlugEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tenant_content_block_stats', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onUpdate('cascade')->onDelete('cascade');


            $table->unsignedSmallInteger('number_content_blocks')->default(0);

            foreach (ContentBlockStateEnum::cases() as $state) {
                $table->unsignedSmallInteger('number_content_blocks_state_' . Str::replace('-', '_', $state->snake()))->default(0);
            }

            foreach (WebBlockTypeSlugEnum::cases() as $type) {
                $table->unsignedSmallInteger('number_content_blocks_type_' .
                    Str::replace('-', '_', $type->snake()))->default(0);
                foreach (ContentBlockStateEnum::cases() as $state) {
                    $table->unsignedSmallInteger(
                        'number_content_blocks_type_'.
                        Str::replace('-', '_', $type->snake()).
                        '_state_' .
                        Str::replace('-', '_', $state->snake())
                    )->default(0);
                }

            }



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenant_content_block_stats');
    }
};
