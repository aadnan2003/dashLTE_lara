<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    // protected $table = 'xyz';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    // protected $primaryKey = 'id';

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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //Append Attribute
    public function fullMobile(): Attribute
    {
        return new Attribute(get: fn () => $this->mobile != "" ? $this->mobile : "No-Mobile");
    }

    /**
     * Relations:
     * 1) One-To-One: hasOne
     *      - Inverse: One-To-One => belongsTo
     * 2) One-To-Many: hasMany
     *      - Inverse: One-To-One => belongsTo
     * 3) Many-To-Many: belongsToMany
     *      - ----
     */

    /**
     * Create Relations in Model
     * 1) Create new function
     * 2) Function name preferred to be related to relation type
     *      - One-To-Many
     *          - Example: User hasMany Category - user Categories
     *              - Name: Plural = categories
     *      - One-To-One
     *          - Example: User hasOne Category - user category
     *              - Name: Singular = category
     */

    public function categories()
    {
        return $this->hasMany(Category::class, 'user_id', 'id');
    }
}
