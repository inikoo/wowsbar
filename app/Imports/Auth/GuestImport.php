<?php

namespace App\Imports\Auth;

use App\Actions\Helpers\ExcelUpload\ExcelUploadRecord\UpdateImportExcelUploadStatus;
use App\Actions\Organisation\Guest\StoreGuest;
use App\Enums\Organisation\Guest\GuestTypeEnum;
use App\Models\Auth\Guest;
use App\Models\Helpers\Upload;
use App\Models\Helpers\UploadRecord;
use App\Rules\AlphaDashDot;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class GuestImport implements ToCollection, WithHeadingRow, SkipsOnFailure, WithValidation
{
    use SkipsFailures;

    public Upload $guestUpload;
    public function __construct(Upload $guestUpload)
    {
        $this->guestUpload = $guestUpload;
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection): void
    {
        $totalImported = 1;

        foreach ($collection as $guest) {
            try {
                $guest = UploadRecord::create([
                    'excel_upload_id' => $this->guestUpload->id,
                    'data'            => json_encode($guest)
                ]);

                StoreGuest::run(json_decode($guest->data, true));
                UpdateImportExcelUploadStatus::run($guest, count($collection), $totalImported++, Guest::class);
            } catch (\Exception $e) {
                $totalImported--;
            }
        }
    }

    public function rules(): array
    {
        return [
            'type'         => ['required', Rule::in(GuestTypeEnum::values())],
            'username'     => ['required', new AlphaDashDot(), 'unique:App\Models\Auth\OrganisationUser,username', Rule::notIn(['export', 'create'])],
            'company_name' => ['nullable', 'string', 'max:255'],
            'contact_name' => ['required', 'string', 'max:255'],
            'phone'        => ['nullable'],
            'email'        => ['nullable', 'email']
        ];
    }
}
