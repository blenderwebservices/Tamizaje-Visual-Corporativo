<?php

use App\Http\Controllers\PublicScreeningController;
use Illuminate\Support\Facades\Route;

// Portada / Resumen del Sistema y Acceso
Route::get('/', function () {
    return view('welcome');
});

// Fase 1: Formulario de Registro Móvil y Pase de Tamizaje
Route::get('/registro', [PublicScreeningController::class, 'showRegistrationForm'])->name('registration.form');
Route::post('/registro', [PublicScreeningController::class, 'submitRegistration'])
    ->middleware('throttle:20,1')
    ->name('registration.submit');
Route::get('/registro/pase/{uuid}', [PublicScreeningController::class, 'showRegistrationPass'])->name('registration.pass');

// Fase 3: Reporte Digital del Paciente y Descarga Segura de PDF
Route::get('/reporte/{uuid}', [PublicScreeningController::class, 'showDigitalReport'])->name('report.show');
Route::get('/reporte/{uuid}/pdf', [PublicScreeningController::class, 'downloadPdf'])->name('report.download');

// Reporte 2: Prescripción Óptica y Graduación Oficial (Enjoy Vision)
Route::get('/graduacion/{uuid}', [PublicScreeningController::class, 'showGraduationReport'])->name('graduation.show');

