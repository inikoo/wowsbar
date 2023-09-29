<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 08:26:02 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Imports\Leads;

use App\Actions\Helpers\ExcelUpload\ExcelUploadRecord\UpdateImportExcelUploadStatus;
use App\Actions\Leads\Prospect\StoreProspect;
use App\Models\CRM\Customer;
use App\Models\Helpers\Upload;
use App\Models\Helpers\UploadRecord;
use App\Models\Leads\Prospect;
use App\Models\Market\Shop;
use App\Models\Portfolio\PortfolioWebsite;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProspectImport implements ToCollection, WithHeadingRow, SkipsOnFailure, WithValidation
{
    use SkipsFailures;

    public Upload $prospectUpload;
    private Shop|Customer|PortfolioWebsite $scope;

    public function __construct(Shop|Customer|PortfolioWebsite $scope, Upload $prospectUpload)
    {
        $this->scope          =$scope;
        $this->prospectUpload = $prospectUpload;
    }



    public function collection(Collection $collection): void
    {
        $totalImported = 1;

        foreach ($collection as $prospect) {
            try {
                $prospect = UploadRecord::create([
                    'excel_upload_id' => $this->prospectUpload->id,
                    'data'            => json_encode(Arr::except($prospect, 'code'))
                ]);

                StoreProspect::run($this->scope, json_decode($prospect->data, true));
                UpdateImportExcelUploadStatus::run($prospect, count($collection), $totalImported++, Prospect::class);
            } catch (Exception $e) {
                $totalImported--;
            }
        }
    }

    public function rules(): array
    {
        return [
            'contact_name'    => ['required', 'nullable', 'string', 'max:255'],
            'company_name'    => ['required', 'nullable', 'string', 'max:255'],
            'email'           => ['required', 'nullable', 'email'],
            'phone'           => ['required', 'nullable'],
            'contact_website' => ['required', 'nullable'],
        ];
    }
}
