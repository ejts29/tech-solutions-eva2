<?php

// app\Models\Proyecto.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Proyecto extends Model
{
    use HasFactory;

    protected $table = 'proyectos';

    protected $fillable = [
        'nombre',
        'fecha_inicio',
        'estado',
        'responsable',
        'monto',
        'created_by',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'monto'        => 'decimal:2',
    ];

    // Mapas estado <-> etiqueta
    public const ESTADO_CANON = [
        'Pendiente'    => 'pendiente',
        'En Progreso'  => 'en_progreso',
        'Completado'   => 'completado',
        'Pausado'      => 'pausado',
        'Cancelado'    => 'cancelado', // ver Nota al final
    ];

    public const ESTADO_LABEL = [
        'pendiente'    => 'Pendiente',
        'en_progreso'  => 'En Progreso',
        'completado'   => 'Completado',
        'pausado'      => 'Pausado',
        'cancelado'    => 'Cancelado',
    ];

    // ---- Normaliza ANTES de guardar en BD
    public function setEstadoAttribute($value): void
    {
        $value = is_string($value) ? trim($value) : $value;

        if (isset(self::ESTADO_CANON[$value])) {
            $value = self::ESTADO_CANON[$value];
        } elseif (is_string($value)) {
            $value = strtolower(str_replace(' ', '_', $value));
        }

        $this->attributes['estado'] = $value;
    }

    // ---- Devuelve etiqueta BONITA al leer (para que tus vistas sigan igual)
    public function getEstadoAttribute($value)
    {
        return self::ESTADO_LABEL[$value] ?? $value;
    }

    public function creador()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    // (Opcional) obtener el valor crudo guardado en BD cuando lo necesites
    public function getEstadoRawAttribute()
    {
        return $this->getRawOriginal('estado');
    }
}