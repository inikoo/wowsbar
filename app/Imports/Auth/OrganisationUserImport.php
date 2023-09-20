<?php

namespace App\Imports\Auth;

use App\Actions\Helpers\Uploads\ImportExcelUploads;
use App\Actions\Helpers\Uploads\UpdateExcelUploads;
use App\Actions\Organisation\Auth\OrganisationUser\StoreOrganisationUser;
use App\Actions\Organisation\Guest\StoreGuest;
use App\Enums\Organisation\Guest\GuestTypeEnum;
use App\Models\Auth\Guest;
use App\Models\Auth\OrganisationUser;
use App\Models\Media\ExcelUpload;
use App\Models\Media\ExcelUploadRecord;
use App\Rules\AlphaDashDot;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class OrganisationUserImport implements ToCollection, WithHeadingRow, SkipsOnFailure, WithValidation
{
    use SkipsFailures;

    public ExcelUpload $organisationUserUpload;
    public function __construct(ExcelUpload $organisationUserUpload)
    {
        $this->organisationUserUpload = $organisationUserUpload;
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection): void
    {
        $totalImported = 1;

        UpdateExcelUploads::run($this->organisationUserUpload, ['number_rows' => count($collection)]);

        foreach ($collection as $organisationUser) {
            try {
                $organisationUser = ExcelUploadRecord::create([
                    'excel_upload_id' => $this->organisationUserUpload->id,
                    'data'            => json_encode($organisationUser)
                ]);

                StoreOrganisationUser::run(json_decode($organisationUser->data, true));
                ImportExcelUploads::dispatch($organisationUser, count($collection), $totalImported++, OrganisationUser::class);
            } catch (\Exception $e) {
                $totalImported--;
            }
        }
    }

    public function rules(): array
    {
        return [
            'username' => ['required', new AlphaDashDot(), 'unique:org_users,username', Rule::notIn(['export', 'create'])],
            'password' => ['required', app()->isLocal() || app()->environment('testing') ? null : Password::min(8)->uncompromised()],
            'email'    => ['sometimes', 'required', 'email', 'unique:org_users,email']
        ];
    }
}
