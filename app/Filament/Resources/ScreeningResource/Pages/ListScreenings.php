<?php

namespace App\Filament\Resources\ScreeningResource\Pages;

use App\Filament\Resources\ScreeningResource;
use App\Models\Company;
use App\Services\SpotVisionImporter;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Http\UploadedFile;

class ListScreenings extends ListRecords
{
    protected static string $resource = ScreeningResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // 1. Carga Individual de PDF de SpotVision
            Actions\Action::make('importPdf')
                ->label('Importar PDF SpotVision')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('primary')
                ->modalHeading('Cargar Estudio SpotVision Screener (PDF)')
                ->modalDescription('El documento se analizará extrayendo datos del paciente, código, refracción OD/OS y generando el análisis rápido.')
                ->form([
                    Forms\Components\FileUpload::make('pdf_file')
                        ->label('Archivo PDF del Examen')
                        ->acceptedFileTypes(['application/pdf'])
                        ->maxSize(15360) // 15 MB
                        ->disk('local')
                        ->directory('temp_uploads')
                        ->required(),
                    Forms\Components\Select::make('company_id')
                        ->label('Empresa / Jornada')
                        ->options(Company::pluck('name', 'id'))
                        ->searchable()
                        ->placeholder('Selecciona la empresa correspondiente'),
                    Forms\Components\TextInput::make('manual_client_name')
                        ->label('Nombre del Paciente (Opcional)')
                        ->helperText('Dejar vacío para extraer automáticamente del documento o nombre del archivo.'),
                ])
                ->action(function (array $data, SpotVisionImporter $importer) {
                    try {
                        $relativeFilePath = $data['pdf_file'];
                        $fullPath = storage_path('app/' . $relativeFilePath);

                        $screening = $importer->importPdf(
                            $fullPath,
                            $data['company_id'] ? (int)$data['company_id'] : null,
                            $data['manual_client_name'] ?? null
                        );

                        Notification::make()
                            ->title('Estudio SpotVision Importado con Éxito')
                            ->body("Paciente: {$screening->client->full_name} | Resultado: " . strtoupper($screening->screening_status))
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Error al importar el estudio')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),

            // 2. Importación Masiva (Lote de PDFs)
            Actions\Action::make('importBatch')
                ->label('Carga Masiva (Lote)')
                ->icon('heroicon-o-document-duplicate')
                ->color('info')
                ->modalHeading('Importación Masiva de Múltiples PDFs')
                ->modalDescription('Sube varios archivos PDF exportados de la memoria USB del SpotVision al mismo tiempo.')
                ->form([
                    Forms\Components\FileUpload::make('pdf_files')
                        ->label('Seleccionar Archivos PDF')
                        ->multiple()
                        ->acceptedFileTypes(['application/pdf'])
                        ->maxSize(15360)
                        ->disk('local')
                        ->directory('temp_uploads')
                        ->required(),
                    Forms\Components\Select::make('company_id')
                        ->label('Empresa / Jornada')
                        ->options(Company::pluck('name', 'id'))
                        ->searchable()
                        ->placeholder('Empresa correspondiente al lote'),
                ])
                ->action(function (array $data, SpotVisionImporter $importer) {
                    $files = (array)$data['pdf_files'];
                    $importedCount = 0;
                    $errors = [];

                    foreach ($files as $filePath) {
                        try {
                            $fullPath = storage_path('app/' . $filePath);
                            $importer->importPdf(
                                $fullPath,
                                $data['company_id'] ? (int)$data['company_id'] : null
                            );
                            $importedCount++;
                        } catch (\Exception $e) {
                            $errors[] = basename($filePath) . ': ' . $e->getMessage();
                        }
                    }

                    if ($importedCount > 0) {
                        Notification::make()
                            ->title("Se importaron {$importedCount} estudios con éxito.")
                            ->success()
                            ->send();
                    }

                    if (!empty($errors)) {
                        Notification::make()
                            ->title('Algunos archivos tuvieron observaciones')
                            ->body(implode("\n", array_slice($errors, 0, 3)))
                            ->warning()
                            ->send();
                    }
                }),

            // 3. Escaneo Directo de Memoria USB o Carpeta Local
            Actions\Action::make('scanUsb')
                ->label('Escanear Memoria USB')
                ->icon('heroicon-o-circle-stack')
                ->color('warning')
                ->modalHeading('Importar desde Carpeta o Memoria USB Conectada')
                ->modalDescription('Indica la ruta de la memoria USB o directorio donde se guardaron los PDFs del SpotVision.')
                ->form([
                    Forms\Components\TextInput::make('usb_path')
                        ->label('Ruta del Directorio / USB')
                        ->default(base_path('docs'))
                        ->helperText('Ejemplo en Mac: /Volumes/SPOTVISION o docs/')
                        ->required(),
                    Forms\Components\Select::make('company_id')
                        ->label('Empresa / Jornada')
                        ->options(Company::pluck('name', 'id'))
                        ->searchable(),
                ])
                ->action(function (array $data, SpotVisionImporter $importer) {
                    try {
                        $results = $importer->importFromDirectory(
                            $data['usb_path'],
                            $data['company_id'] ? (int)$data['company_id'] : null
                        );

                        Notification::make()
                            ->title("Escaneo de USB completado")
                            ->body("Se procesaron {$results['imported']} de {$results['total']} archivos PDF encontrados.")
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Error al escanear directorio')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),

            Actions\CreateAction::make()
                ->label('Nuevo Estudio Manual'),
        ];
    }
}
