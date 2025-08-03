<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;
use Carbon\Carbon;

/**
 * @property int $id
 * @property string|null $employee_id
 * @property string $first_name
 * @property string|null $middle_name
 * @property string $last_name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string $remember_token
 * @property string $role
 * @property string $status
 * @property string|null $email_verification_token
 * @property int $email_verification_attempts
 * @property \Illuminate\Support\Carbon|null $last_verification_email_sent_at
 * @property \Illuminate\Support\Carbon|null $email_verification_expires_at
 * @property unsignedBigInteger|null $facility_id
 * @property string|null $position
 * @property string|null $license_number
 * @property \Illuminate\Support\Carbon|null $license_expiry_date
 * @property string|null $department
 * @property string|null $phone_number
 * @property string|null $alternate_phone
 * @property text|null $address
 * @property unsignedBigInteger|null $barangay_id
 * @property string|null $profile_photo
 * @property \Illuminate\Support\Carbon|null $date_of_birth
 * @property string|null $sex
 * @property bool $two_factor_enabled
 * @property string|null $two_factor_secret
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $last_login_at
 * @property \Illuminate\Support\Carbon|null $last_activity_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @method bool isAdmin()
 * @method bool isParentGuardian()
 * @method bool isNutritionist()
 * @method bool isApproved()
 * @method bool isPending()
 */
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'employee_id',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'password',
        'role',
        'status',
        'email_verification_token',
        'email_verification_attempts',
        'last_verification_email_sent_at',
        'email_verification_expires_at',
        'facility_id',
        'position',
        'license_number',
        'license_expiry_date',
        'department',
        'phone_number',
        'alternate_phone',
        'address',
        'barangay_id',
        'profile_photo',
        'date_of_birth',
        'sex',
        'two_factor_enabled',
        'two_factor_secret',
        'is_active',
        'last_login_at',
        'last_activity_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verification_token',
        'two_factor_secret',
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
            'last_verification_email_sent_at' => 'datetime',
            'email_verification_expires_at' => 'datetime',
            'license_expiry_date' => 'date',
            'date_of_birth' => 'date',
            'last_login_at' => 'datetime',
            'last_activity_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_enabled' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'email_verification_attempts' => 0,
        'is_active' => true,
        'status' => 'email_pending',
        'role' => 'parent_guardian',
    ];

    /**
     * Boot the model and add event listeners.
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate employee ID before creating
        static::creating(function ($user) {
            if (empty($user->employee_id)) {
                $user->employee_id = self::generateEmployeeId($user->role);
            }
        });
    }

    /**
     * Generate a unique employee ID
     */
    public static function generateEmployeeId(string $role = 'parent_guardian'): string
    {
        do {
            // Format: PREFIX-YYYY-XXXX
            // PREFIX: PAR (Parent), NUT (Nutritionist), ADM (Admin)
            $year = date('Y');
            $random = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
            
            $prefix = match($role) {
                'parent_guardian' => 'PAR',
                'nutritionist' => 'NUT',
                'admin' => 'ADM',
                default => 'USR'
            };
            
            $employeeId = "{$prefix}-{$year}-{$random}";
        } while (self::where('employee_id', $employeeId)->exists());

        return $employeeId;
    }

    /**
     * Get the user's full name.
     */
    public function getFullNameAttribute(): string
    {
        $name = $this->first_name;
        if ($this->middle_name) {
            $name .= ' ' . $this->middle_name;
        }
        $name .= ' ' . $this->last_name;
        return $name;
    }

    /**
     * Get the user's display name.
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Get the user's employee ID with role prefix
     */
    public function getFormattedEmployeeIdAttribute(): string
    {
        if (!$this->employee_id) {
            return 'N/A';
        }
        
        $roleLabel = match($this->role) {
            'parent_guardian' => 'Parent',
            'nutritionist' => 'Nutritionist',
            'admin' => 'Administrator',
            default => 'User'
        };
        
        return "{$this->employee_id} ({$roleLabel})";
    }

    /**
     * Check if user has an employee ID
     */
    public function hasEmployeeId(): bool
    {
        return !empty($this->employee_id);
    }

    /**
     * Check if user is an admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is a parent/guardian
     */
    public function isParentGuardian(): bool
    {
        return $this->role === 'parent_guardian';
    }
    
    /**
     * Check if user is a nutritionist
     */
    public function isNutritionist(): bool
    {
        return $this->role === 'nutritionist';
    }

    /**
     * Check if user is approved
     */
    public function isApproved(): bool
    {
        return $this->getAttribute('status') === 'approved';
    }

    /**
     * Check if user is pending approval
     */
    public function isPending(): bool
    {
        return $this->getAttribute('status') === 'pending';
    }

    /**
     * Check if user is active
     */
    public function isActive(): bool
    {
        return $this->getAttribute('is_active') === true;
    }

    /**
     * Generate email verification token
     */
    public function generateEmailVerificationToken(): string
    {
        $token = Str::random(60);
        $currentAttempts = $this->getAttribute('email_verification_attempts') ?? 0;
        
        $this->forceFill([
            'email_verification_token' => $token,
            'email_verification_expires_at' => now()->addMinutes(60),
            'last_verification_email_sent_at' => now(),
            'email_verification_attempts' => $currentAttempts + 1,
        ])->save();
        
        return $token;
    }

    /**
     * Mark email as verified
     */
    public function markEmailAsVerified(): bool
    {
        $this->forceFill([
            'email_verified_at' => now(),
            'email_verification_token' => null,
            'email_verification_expires_at' => null,
            'status' => $this->role === 'nutritionist' ? 'pending' : 'approved',
        ])->save();

        return true;
    }

    /**
     * Check if verification token is expired
     */
    public function isVerificationTokenExpired(): bool
    {
        $expiresAt = $this->getAttribute('email_verification_expires_at');
        if (!$expiresAt) {
            return false;
        }
        
        return now()->isAfter($expiresAt);
    }

    /**
     * Check if user can request new verification email (rate limiting)
     */
    public function canRequestVerificationEmail(): bool
    {
        $lastSent = $this->getAttribute('last_verification_email_sent_at');
        if (!$lastSent) {
            return true;
        }
        
        // Allow new request after 1 minute
        return now()->isAfter(Carbon::parse($lastSent)->addMinutes(1));
    }

    /**
     * Check if verification attempts exceeded limit
     */
    public function hasExceededVerificationAttempts(): bool
    {
        $attempts = $this->getAttribute('email_verification_attempts') ?? 0;
        return $attempts >= 5;
    }

    /**
     * Determine if the user has verified their email address.
     */
    public function hasVerifiedEmail(): bool
    {
        return !is_null($this->getAttribute('email_verified_at'));
    }

    /**
     * Get the email address that should be used for verification.
     */
    public function getEmailForVerification(): string
    {
        return $this->getAttribute('email');
    }

    /**
     * Send the email verification notification.
     */
    public function sendEmailVerificationNotification(): void
    {
        $token = $this->generateEmailVerificationToken();
        $this->notify(new \App\Notifications\VerifyEmailNotification($token));
    }

    /**
     * Get the children (patients) for the parent user
     */
    public function children()
    {
        return $this->hasMany(Patient::class, 'parent_id');
    }

    /**
     * Get the facility relationship
     */
    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    /**
     * Get the barangay relationship
     */
    public function barangay()
    {
        return $this->belongsTo(Barangay::class);
    }

    /**
     * Update last login timestamp
     */
    public function updateLastLogin(): void
    {
        $this->update([
            'last_login_at' => now(),
            'last_activity_at' => now(),
        ]);
    }

    /**
     * Update last activity timestamp
     */
    public function updateLastActivity(): void
    {
        $this->update(['last_activity_at' => now()]);
    }
}
