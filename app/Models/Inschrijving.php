<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inschrijving extends Model
{
    use HasFactory;
    protected $table = 'register';

//    public function azure(): \Illuminate\Database\Eloquent\Relations\BelongsTo
//    {
//        return $this->belongsTo
//        (
//            AzureUser::class,
//            'officeID',
//            'id',
//            'users'
//        );
//    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo
        (
            Transaction::class,
            'paymentId',
            'id',
            'transaction'
        );
    }
}
