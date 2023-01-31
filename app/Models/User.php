<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // 关联的表名
    protected $table = 'student';

    // 表的主键
    protected $primaryKey = 'id';

    // 主键的类型
    protected $keyType = "int";

    /**
     * 指示模型是否主动维护时间戳。 updated_at ,created_at
     * 可以自定义维护字段的名字
     * const CREATED_AT = 'creation_date';
     * const UPDATED_AT = 'updated_date';
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    // 隐藏属性那些是不可看的
//    protected $hidden = [
//        'password',
//        'remember_token',
//    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    // 任意属性的类型转换
//    protected $casts = [
//        'email_verified_at' => 'datetime',
//    ];

// 为了方便测试目前用户验证用户名和密码都是使用的id
    /**
     * Passport find by username
     * @param $id
     * @return mixed
     */
    public function findForPassport($id)
    {
        return $this->find(['id' => $id]);
    }

    /**
     * Passport custom password valid
     * @param $id
     * @return bool
     */
    public function validateForPassportPasswordGrant($id)
    {
        return $this->$id == $id;
    }
}
