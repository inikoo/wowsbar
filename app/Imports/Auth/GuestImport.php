<?php

namespace App\Imports\Auth;

use App\Actions\Helpers\Uploads\ImportExcelUploads;
use App\Actions\Organisation\Guest\StoreGuest;
use App\Enums\Organisation\Guest\GuestTypeEnum;
use App\Models\Auth\Guest;
use App\Models\Media\ExcelUpload;
use App\Models\Media\ExcelUploadRecord;
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

    public ExcelUpload $guestUpload;
    public function __construct(ExcelUpload $guestUpload)
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
                $guest = ExcelUploadRecord::create([
                    'excel_upload_id' => $this->guestUpload->id,
                    'data'            => json_encode($guest)
                ]);

                StoreGuest::run(json_decode($guest->data, true));
                ImportExcelUploads::run($guest, count($collection), $totalImported++, Guest::class);
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
