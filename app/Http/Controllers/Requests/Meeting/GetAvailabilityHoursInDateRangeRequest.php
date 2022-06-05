<?php

namespace App\Http\Requests\Meeting;

use App\Http\Requests\JsonRequest;
use App\Rules\MultipleByValueRule;

class GetAvailabilityHoursInDateRangeRequest extends JsonRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $work_hours_in_minutes = config('settings.work_hours_in_minutes', 540);
        $meeting_minimum_duration_in_minutes = config('settings.meeting_minimum_duration_in_minutes', 30);
        return [
            'participants' => 'required|array',
            'participants.*' => 'required| string|max:255| exists:users,uuid',
            'meeting_duration' => ['required', 'min:' . $meeting_minimum_duration_in_minutes, 'max:' . $work_hours_in_minutes, 'integer', new MultipleByValueRule()],
            'earliest_date' => 'required|date',
            'latest_date' => 'required|date|after:earliest_date',
            //'preferred_hours' => 'nullable:'
        ];

    }

}
