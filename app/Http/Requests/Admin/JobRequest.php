<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class JobRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title'           => 'required|max:255',
            'status'          => 'required|max:1',
            'position_id'     => 'required|numeric',
            'sector_id'       => 'required|numeric',
            'number_of_day'   => 'required|numeric',
            'post_date'       => 'required|date_format:Y-m-d',
            'close_date'      => 'required|date_format:Y-m-d',
            'salary_from'     => 'required|numeric',
            'job_des'         => 'required|string',
            'job_requirement' => 'required|string',
            'job_res'         => 'required|string',
            'image'           => 'required',
        ];
    }
    public function messages()
    {
        return [
            'title.required' => 'The job title is required.',
            'title.max' => 'The job title may not be greater than 255 characters.',

            'status.required' => 'The job status is required.',
            'status.max' => 'The job status must be a single character.',

            'position_id.required' => 'The position is required.',
            'position_id.numeric' => 'The position must be a valid number.',

            'sector_id.required' => 'The sector is required.',
            'sector_id.numeric' => 'The sector must be a valid number.',

            'number_of_day.required' => 'The number of days is required.',
            'number_of_day.numeric' => 'The number of days must be a valid number.',

            'post_date.required' => 'The post date is required.',
            'post_date.date_format' => 'The post date must be in the format Y-m-d (e.g., 2024-09-15).',

            'close_date.required' => 'The close date is required.',
            'close_date.date_format' => 'The close date must be in the format Y-m-d (e.g., 2024-09-15).',

            'salary_from.required' => 'The salary is required.',
            'salary_from.numeric' => 'The salary must be a valid number.',

            'job_des.required' => 'The job description is required.',
            'job_des.string' => 'The job description must be a valid string.',

            'job_requirement.required' => 'The job requirement is required.',
            'job_requirement.string' => 'The job requirement must be a valid string.',

            'job_res.required' => 'The job responsibilities are required.',
            'job_res.string' => 'The job responsibilities must be a valid string.',

            'image.required' => 'An image is required for the job post.',
        ];
    }
}
