<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\PhysicalActivitySession;
class PhysicalActivityType extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'intensity',
        'description',
    ];

    public function physicalActivitySessions() {
        return $this->hasMany(PhysicalActivitySession::class);
    }
}
