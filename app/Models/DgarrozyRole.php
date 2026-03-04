<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DgarrozyRole extends Model
{
    protected $table = 'dgarrozy_role';
    protected $fillable = ['code', 'name'];

    public function accounts()
    {
        return $this->hasMany(DgarrozyAccount::class, 'role_id');
    }
}
