<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'department_id', 'batch_name'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id')->withDefault();
    }
    public function result()
    {
        return $this->hasOne(Result::class)->withDefault();
    }

    /**
     * Scope a query to only include list
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeList($query)
    {
        return $query->select('id', 'name')->get();
    }
}
