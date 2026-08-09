<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Models\AuditLog;

trait Auditable
{
    public static function bootAuditable()
    {
        // Created event
        static::created(function (Model $model) {
            self::logAction($model, 'created', null, $model->getAttributes());
        });

        // Updated event
        static::updated(function (Model $model) {
            $oldValues = $model->getOriginal();
            $newValues = $model->getChanges();
            
            // Only log if there are actual changes
            if (!empty($newValues)) {
                self::logAction($model, 'updated', $oldValues, $newValues);
            }
        });

        // Deleted event
        static::deleted(function (Model $model) {
            self::logAction($model, 'deleted', $model->getOriginal(), null);
        });
    }

    /**
     * Log an action manually
     */
    public static function logAudit($description, $event = 'custom', $oldValues = null, $newValues = null, $properties = null)
    {
        // This allows manual logging from controllers/services
        // Implementation handled by the service or called directly
    }

    private static function logAction(Model $model, string $event, ?array $oldValues, ?array $newValues)
    {
        $hiddenFields = ['password', 'remember_token'];
        
        // Filter out hidden fields
        if ($oldValues) {
            $oldValues = array_diff_key($oldValues, array_flip($hiddenFields));
        }
        if ($newValues) {
            $newValues = array_diff_key($newValues, array_flip($hiddenFields));
        }

        AuditLog::create([
            'log_name' => strtolower(class_basename($model)),
            'description' => null, // Will use formatted description
            'subject_type' => get_class($model),
            'subject_id' => $model->getKey(),
            'causer_type' => Auth::check() ? get_class(Auth::user()) : null,
            'causer_id' => Auth::id(),
            'properties' => [],
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'event' => $event,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
