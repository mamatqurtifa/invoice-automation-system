<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_photo_path',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    
    /**
     * Get the URL of the user's profile photo.
     *
     * @return string
     */
    public function profilePhotoUrl()
    {
        if ($this->profile_photo_path) {
            return Storage::url($this->profile_photo_path);
        }
        
        // Generate default avatar using first letter of name
        $name = trim($this->name);
        $initials = strtoupper($name[0] ?? 'A');
        $bgcolor = $this->stringToColor($this->email);
        
        return 'https://ui-avatars.com/api/?name=' . urlencode($initials) . '&color=ffffff&background=' . $bgcolor;
    }
    
    /**
     * Convert a string to a hex color code without the #
     *
     * @param string $string
     * @return string
     */
    protected function stringToColor($string)
    {
        // Generate a color based on the email
        $hash = md5($string);
        return substr($hash, 0, 6); // Take the first 6 characters for the color
    }

    public function projects() {
        return $this->hasMany( Project::class );
    }

    // Add this relationship to the User model

    public function notifications() {
        return $this->hasMany( Notification::class );
    }

    public function unreadNotificationsCount() {
        return $this->notifications()->whereNull( 'read_at' )->count();
    }
}