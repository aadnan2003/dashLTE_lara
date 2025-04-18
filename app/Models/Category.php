<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

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
     *          - Example: User belongs Category - user category
     *              - Name: Singular = category
     */

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
