<?php

use App\Stubs\Migrations\HasSoftDeletes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    use HasSoftDeletes;
    public function up(): void
    {
        Schema::create('shipper_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique()->collation('und_ns');
            $table->string('label')->nullable();
            $table->unsignedSmallInteger('shipper_id');
            $table->foreign('shipper_id')->references('id')->on('shippers');
            $table->unsignedInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->json('credentials');
            $table->jsonb('data');
            $table->timestampsTz();
            $this->softDeletes($table);

        });

    }

    public function down(): void
    {
        Schema::dropIfExists('shipper_accounts');
    }
};
