<?php

namespace App\Models;

use Illuminate\Notifications\DatabaseNotification;

class CustomDatabaseNotification extends DatabaseNotification
{
    /**
     * Laravel default `read_at` column ko override kar ke `is_read` use karenge
     */
    const UPDATED_AT = 'updated_at';
    const CREATED_AT = 'created_at';

    public function markAsRead()
    {
        return $this->forceFill(['is_read' => now()])->save();
    }

    public function markAsUnread()
    {
        return $this->forceFill(['is_read' => null])->save();
    }

    public function read()
    {
        return !is_null($this->is_read);
    }

    public function unread()
    {
        return is_null($this->is_read);
    }
}