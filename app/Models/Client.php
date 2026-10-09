<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'company_id',
        'user_id',
        'client_code',
        'subject_code',
        'first_name',
        'last_name',
        'full_name',
        'phone',
        'email',
        'birth_date',
        'age',
        'gender',
        'terms_accepted',
        'terms_accepted_at',
        'crm_stage',
        'purchase_notes',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'age' => 'integer',
        'terms_accepted' => 'boolean',
        'terms_accepted_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Client $client) {
            if (empty($client->uuid)) {
                $client->uuid = (string) Str::uuid();
            }
            if (empty($client->client_code)) {
                $client->client_code = 'CLI-' . strtoupper(Str::random(6));
            }
            if (empty($client->full_name)) {
                $client->full_name = trim(($client->first_name ?? '') . ' ' . ($client->last_name ?? ''));
            }
            if (empty($client->user_id) && auth()->check()) {
                $client->user_id = auth()->id();
            }
            if (empty($client->company_id) && auth()->check() && auth()->user()->company_id) {
                $client->company_id = auth()->user()->company_id;
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function screenings(): HasMany
    {
        return $this->hasMany(Screening::class)->latest();
    }

    public function latestScreening()
    {
        return $this->hasOne(Screening::class)->latestOfMany();
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class)->latest();
    }

    public function retargetingLogs(): HasMany
    {
        return $this->hasMany(RetargetingLog::class)->latest();
    }

    /**
     * Sanitiza el número telefónico asegurando sólo dígitos y prefijo de país (por defecto México +52 si son 10 dígitos)
     */
    public static function sanitizePhone(?string $phone): ?string
    {
        if (empty($phone)) {
            return null;
        }

        // Remover cualquier caracter que no sea dígito ni signo +
        $cleaned = preg_replace('/[^0-9]/', '', $phone);

        // Si son 10 dígitos (estándar en México), anteponer código país 52
        if (strlen($cleaned) === 10) {
            $cleaned = '52' . $cleaned;
        }

        return $cleaned;
    }

    /**
     * Normaliza cadenas para comparaciones fonéticas y sin acentos
     */
    public static function normalizeString(?string $text): string
    {
        if (empty($text)) {
            return '';
        }
        $str = mb_strtolower(trim($text), 'UTF-8');
        $str = str_replace(
            ['á', 'é', 'í', 'ó', 'ú', 'ü', 'ñ'],
            ['a', 'e', 'i', 'o', 'u', 'u', 'n'],
            $str
        );
        return preg_replace('/\s+/', ' ', $str);
    }

    /**
     * Motor de deduplicación: Busca si ya existe un cliente por código o nombre + fecha/edad
     */
    public static function findMatch(
        ?string $fullName,
        ?string $subjectCode = null,
        $birthDate = null,
        ?int $age = null
    ): ?self {
        // 1. Coincidencia por Código de Sujeto si existe
        if (!empty($subjectCode)) {
            $matchBySubject = self::where('subject_code', trim($subjectCode))->first();
            if ($matchBySubject) {
                return $matchBySubject;
            }
        }

        if (empty($fullName)) {
            return null;
        }

        $normalizedTarget = self::normalizeString($fullName);

        // 2. Buscar candidatos cuyo nombre coincida
        $candidates = self::all()->filter(function ($client) use ($normalizedTarget) {
            return self::normalizeString($client->full_name) === $normalizedTarget;
        });

        if ($candidates->isEmpty()) {
            return null;
        }

        // Si solo hay un cliente con ese nombre y no hay ambigüedad
        if ($candidates->count() === 1) {
            $candidate = $candidates->first();

            // Si ambos tienen fecha de nacimiento y difieren en más de un año, no es la misma persona
            if (!empty($birthDate) && !empty($candidate->birth_date)) {
                $targetCarbon = $birthDate instanceof Carbon ? $birthDate : Carbon::parse($birthDate);
                if (abs($targetCarbon->diffInYears($candidate->birth_date)) > 1) {
                    return null; // Homónimo con fecha distinta!
                }
            }

            return $candidate;
        }

        // 3. Hay nombres duplicados -> Discriminar por fecha de nacimiento o edad
        $targetDate = null;
        if (!empty($birthDate)) {
            try {
                $targetDate = $birthDate instanceof Carbon ? $birthDate : Carbon::parse($birthDate);
            } catch (\Exception $e) {
                $targetDate = null;
            }
        }

        foreach ($candidates as $cand) {
            // Comparación por fecha exacta o año de nacimiento
            if ($targetDate && $cand->birth_date) {
                if ($targetDate->isSameDay($cand->birth_date) || $targetDate->year === $cand->birth_date->year) {
                    return $cand;
                }
            }

            // Comparación por edad (tolerancia de 1 año)
            if ($age !== null && $cand->age !== null) {
                if (abs($cand->age - $age) <= 1) {
                    return $cand;
                }
            }
        }

        // Si no se pudo discriminar con certeza entre duplicados, no forzar match erróneo
        return null;
    }
}
