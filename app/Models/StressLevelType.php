<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\StressLevelSession;

class StressLevelType extends Model
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

    public function stressLevelSessions() {
        return $this->hasMany(StressLevelSession::class);
    }
}
