<?php

namespace App\Models;

use App\Jobs\AuthJobs\ResetPasswordJobs;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\NewAccessToken;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'gender',
        'dob',
        'last_name',
        'email_verified_at'

    ];

    protected $guard_name = 'sanctum';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'erp_remember_token'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


     /**
     * static boot di bawah ini membuat suatu value di insert scara otomatis tanpa harus di insert melalui insert command
     * jadi walaupun kita kita memasukan default value ke dalam sepefik cloumn maka, funcsi di bawah ini akan memasukan secara
     * otomatis (seperti initate value before insert)
     */
    protected static function boot() {
        parent::boot();

        static::creating(function($model){
            if(empty($model->uuid)) {
                $model->uuid = Str::uuid();
            }
        });
    }

    
    public function sendPasswordResetNotification($token)
    {

        $url = config('app.URL_SPA').'/reset-password?token='.$token.'&email='.$this->email;

        ResetPasswordJobs::dispatch($this,$url);

    }
    public function createToken(string $name, array $abilities = ['*'])
    {
        $token = $this->tokens()->create([
            'name' => $name,
            'token' => hash('sha256', $plainTextToken = Str::random(256)),
            'abilities' => $abilities,
        ]);

        return new NewAccessToken($token, $token->getKey().'|'.$plainTextToken);
    }
}
