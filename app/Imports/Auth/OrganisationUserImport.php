<?php

namespace App\Imports\Auth;

use App\Actions\Helpers\ExcelUpload\ExcelUploadRecord\UpdateImportExcelUploadStatus;
use App\Actions\SysAdmin\OrganisationUser\StoreOrganisationUser;
use App\Models\Auth\OrganisationUser;
use App\Models\Helpers\Upload;
use App\Models\Helpers\UploadRecord;
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

    public Upload $organisationUserUpload;
    public function __construct(Upload $organisationUserUpload)
    {
        $this->organisationUserUpload = $organisationUserUpload;
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection): void
    {
        $totalImported = 1;

        foreach ($collection as $organisationUser) {
            try {
                $organisationUser = UploadRecord::create([
                    'excel_upload_id' => $this->organisationUserUpload->id,
                    'data'            => json_encode($organisationUser)
                ]);

                StoreOrganisationUser::run(json_decode($organisationUser->data, true));
                UpdateImportExcelUploadStatus::run($organisationUser, count($collection), $totalImported++, OrganisationUser::class);
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
