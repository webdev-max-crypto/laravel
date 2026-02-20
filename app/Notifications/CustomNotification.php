<?php
namespace App\Models;

use Illuminate\Notifications\DatabaseNotification as BaseNotification;

class CustomNotification extends BaseNotification
{
    protected $table = 'notifications'; // your custom table
}