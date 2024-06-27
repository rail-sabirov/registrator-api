<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

use App\Models\VerificationCode;
use Illuminate\Support\Facades\Mail as Mail;
use App\Mail\VerificationEmail;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;


class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use HasFactory, Notifiable, MustVerifyEmailTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

     /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
 
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     * Тут можно передать дополнительные данные в JWT token, например идентификатор пользователя или его email     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
    

    public function getUsers()
    {
        return $this->all();
    }

    // Связываем класс User с таблицей VerificationCode, один к одному
    public function verificationCode()
    {
        return $this->hasOne(VerificationCode::class);
    }


    /**
     * Generate verification code and send email
     */
    public function generateVerificationCode()
    {
        $code = mt_rand(10000000, 99999999);
        VerificationCode::create([
            'user_id' => $this->id,
            'code' => $code
        ]);

        Mail::to($this->email)
            ->send(new VerificationEmail($code));
    }
}
