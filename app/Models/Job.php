<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;


class Job extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'jobs';
    protected $fillable = [
                    'post_date',
                    'close_date',
                    'user_id',
                    'company_id',
                    'country_id',
                    'job_category_id',
                    'location_id',
                    'title',
                    'term',
                    'salary_from',
                    'salary_to',
                    'year_experience',
                    'number_of_hire',
                    'job_des',
                    'job_res',
                    'job_requirement',
                    'image',
                    'slug',
                    'status',
                    'sex',
                    'job_level',
                    'job_type',
                    'hr_name',
                    'hr_phone_number',
                    'hr_email',
                    'is_negotiate',
                    'is_premium',
    ];
    protected $appends = ['image_url','PostDateFor'];
    public function getImageUrlAttribute()
    {
        if ($this->image != null) {
            return url('file_manager' . $this->image);
        }
        return null;
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function getPostDateForAttribute()
    {
        return $this->post_date;
    }

    // public function categoryBlogs()
    // {
    //     return $this->hasMany(CategoryBlog::class, 'blog_id', 'id');
    // }

    // public function category()
    // {
    //     return $this->belongsToMany(Category::class, 'category_blogs', 'blog_id', 'category_id');
    // }

    // public function numView()
    // {
    //     return $this->hasOne(NumView::class, 'blog_id', 'id');
    // }
    // public function company()
    // {
    //     return $this->belongsTo(Company::class, 'company_id', 'id');
    // }
    // public function country()
    // {
    //     return $this->belongsTo(Country::class, 'country_id', 'id');
    // }
    // public function jobCategory()
    // {
    //     return $this->belongsTo(JobCategory::class, 'job_category_id', 'id');
    // }
    // public function location()
    // {
    //     return $this->belongsTo(Location::class, 'location_id', 'id');
    // }

    // public function savedJob()
    // {
    //     return $this->hasMany(SavedJob::class, 'job_id', 'id');
    // }

}
