<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 27 Sep 2023 19:54:57 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Enums\HumanResources\Employee\EmployeeStateEnum;
use App\Enums\HumanResources\Employee\EmployeeTypeEnum;
use App\Stubs\Migrations\HasContact;
use App\Stubs\Migrations\HasSoftDeletes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    use HasContact;
    use HasSoftDeletes;
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('slug')->unique()->collation('und_ns');
            $table->string('alias')->collation('und_ns');
            $table->string('work_email')->nullable()->collation('und_ns');
            $table=$this->contactFields(table:$table, withCompany: false, withPersonalDetails: true);
            $table->string('worker_number')->nullable()->collation('und_ns');
            $table->string('job_title')->nullable()->collation('und_ns');
            $table->string('job_position')->nullable()->collation('und_ns');
            $table->unsignedSmallInteger('workplace_id')->nullable()->index();
            $table->foreign('workplace_id')->references('id')->on('workplaces');
            $table->string('type')->default(EmployeeTypeEnum::EMPLOYEE->value);
            $table->string('state')->default(EmployeeStateEnum::WORKING->value);
            $table->date('employment_start_at')->nullable();
            $table->date('employment_end_at')->nullable();
            $table->string('emergency_contact', 1024)->nullable()->collation('und_ns');
            $table->jsonb('salary')->nullable();
            $table->jsonb('working_hours')->nullable();
            $table->decimal('week_working_hours', 4)->default(0);
            $table->jsonb('data');
            $table->jsonb('job_position_scopes');
            $table->jsonb('errors');
            $table->timestampsTz();
            $this->softDeletes($table);
        });
        DB::statement("CREATE INDEX ON employees (lower('worker_number')) ");
        DB::statement("CREATE INDEX ON employees (lower('alias')) ");

        //DB::statement('CREATE INDEX ON employees USING gin (contact_name gin_trgm_ops) ');
    }


    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
