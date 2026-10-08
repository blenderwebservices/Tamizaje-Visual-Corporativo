<?php

namespace App\Filament\Resources\ScreeningResource\Pages;

use App\Filament\Resources\ScreeningResource;
use App\Models\Screening;
use App\Services\SpotVisionImporter;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Carbon;

class CreateScreening extends CreateRecord
{
    protected static string $resource = ScreeningResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['extraction_method'] = 'manual';

        if (empty($data['barcode_code'])) {
            $data['barcode_code'] = 'MANUAL_'.now()->format('Ymd_His');
        }

        if (empty($data['subject_code'])) {
            $data['subject_code'] = 'MANUAL';
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        /** @var Screening $screening */
        $screening = $this->record;

        if (empty($screening->whatsapp_message_body)) {
            $screening->update([
                'whatsapp_message_body' => $screening->generateWhatsAppMessage(),
            ]);
        }

        if ($screening->client) {
            $examDate = $screening->exam_date ? Carbon::parse($screening->exam_date) : now();
            app(SpotVisionImporter::class)->scheduleRetargetingCampaigns($screening->client, $examDate);
        }
    }
}
