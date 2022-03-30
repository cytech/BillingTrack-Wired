<?php
/**
 * *
 *  * This file is part of BillingTrack.
 *  *
 *  *
 *  * For the full copyright and license information, please view the LICENSE
 *  * file that was distributed with this source code.
 *
 *
 */

namespace BT\Modules\Scheduler\Requests;

use BT\Support\DateFormatter;
use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
{

	protected $rules = [
        'employee_name'      => 'required_without:employee_id',
        'employee_id'  => 'required_without:employee_name',
        'start_date' => 'required',
        'end_date'   => 'required|after:start_date',
		'reminder_date'     => 'sometimes|required',
		'reminder_location'     => 'sometimes|required',
		'reminder_text'     => 'sometimes|required',
		'until' => 'sometimes|required_without:count',
		'count' => 'sometimes|required_without:until'
	];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
	public function rules() {

		if ( $this->method() == 'POST' ) {
			if ( $this->reminder_date && is_array( $this->reminder_date ) ) {
				$this->rules['reminder_date.*'] = 'distinct';

			}
		    return $this->rules;
	    } else {
			return [];
		}
	}

	public function messages()
	{
		return [
			'employee_name.required' => 'A title is required',
			'start_date.required'  => 'A Start DateTime is required',
			'end_date.required'  => 'An End DateTime is required',
			'end_date.after'  => 'An End DateTime must be greater than Start DateTime',
			'reminder_date.*.distinct'  => 'Do not select identical Multiple Reminder Dates.',
			'until.required_without'    => 'Must enter either UNTIL or COUNT',
			'count.required_without'    => 'Must enter either UNTIL or COUNT',
		];
	}
}
