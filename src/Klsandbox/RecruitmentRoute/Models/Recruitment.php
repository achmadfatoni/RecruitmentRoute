<?php

namespace Klsandbox\RecruitmentRoute\Models;

use Illuminate\Database\Eloquent\Model;
use Klsandbox\RoleModel\Role;

/* Klsandbox\RecruitmentRoute\Models\Recruitment
*
*/

/**
 * Klsandbox\RecruitmentRoute\Models\Recruitment
 *
 * @property integer $id
 * @property string $name
 * @property string $phone_number
 * @property integer $user_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @method static \Illuminate\Database\Query\Builder|\Klsandbox\RecruitmentRoute\Models\Recruitment whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Klsandbox\RecruitmentRoute\Models\Recruitment whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Klsandbox\RecruitmentRoute\Models\Recruitment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Klsandbox\RecruitmentRoute\Models\Recruitment whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Klsandbox\RecruitmentRoute\Models\Recruitment wherePhoneNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\Klsandbox\RecruitmentRoute\Models\Recruitment whereUserId($value)
 * @mixin \Eloquent
 */
class Recruitment extends Model
{
    protected $table = 'recruitments';
    public $timestamps = true;
    protected $fillable = [
        'name', 'phone_number', 'user_id', 'role_id'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
