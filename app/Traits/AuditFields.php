<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

trait AuditFields
{
    use  SoftDeletes;

    /**
     * Boot the trait.
     * This registers the Model Events automatically.
     */
    public static function bootAuditFields(): void
    {
        static::creating(function ($model) {
            $userId = Auth::id();

            // Set Audit User
            if (!$model->CreatedBy && $userId) {
                $model->CreatedBy = $userId;
            }
            if (!$model->ModifiedBy && $userId) {
                $model->ModifiedBy = $userId;
            }
        });

        // 2. On Update
        static::updating(function ($model) {
            $userId = Auth::id();
            if ($userId) {
                $model->ModifiedBy = $userId;
            }
        });

        // 3. On Soft Delete
        static::deleted(function ($model) {
            // Laravel has already set 'DeletedDate'.
            // We now sync the legacy fields.
            $model->DeletedBy = Auth::id();

            // saveQuietly prevents triggering the 'updating' event loop
            $model->saveQuietly();
        });

        // 4. On Restore
        static::restored(function ($model) {
            $model->DeletedBy = null;
            $model->saveQuietly();
        });
    }

    /**
     * Initialize the trait.
     * This runs when the model is instantiated, setting up keys and config.
     */
    public function initializeAuditFields(): void
    {
        $this->primaryKey = 'Id';

        $this->timestamps = true;
    }

    /**
     * Override Timestamp Column Names.
     * We use methods instead of constants because constants in Traits
     * cannot override Model constants in PHP.
     */
    public function getCreatedAtColumn(): string
    {
        return 'CreatedDate';
    }

    public function getUpdatedAtColumn(): string
    {
        return 'ModifiedDate';
    }

    public function getDeletedAtColumn(): string
    {
        return 'DeletedDate';
    }
}
