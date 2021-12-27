<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use SoftDeletes, CascadeSoftDeletes;

    // protected $cascadeDeletes = ['services'];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'surname',
        'nickname',
        'email',
        'description',
        'date_birth',
        'role',
        'privilege_id',
        'password',
        'profile_photo_path'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function privilege()
    {
        return $this->belongsTo(Privilege::class);
    }

    public function services()
    {
        return $this->hasMany(InventoryService::class);
    }

    public function updateProfilePhoto(UploadedFile $photo)
    {
        $image_path =  '/storage/' . $photo->store('profile-photos', 'public');
        Storage::delete('public/' . ltrim($this->profile_photo_path, '/storage'));

        $this->update([
            'profile_photo_path' => $image_path
        ]);
    }

    public function deleteProfilePhoto()
    {
        Storage::delete('public/' . ltrim($this->profile_photo_path, '/storage'));

        $this->update([
            'profile_photo_path' => null
        ]);
    }
}
