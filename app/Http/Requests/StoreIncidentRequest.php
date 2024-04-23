<?php

namespace App\Http\Requests;

use App\Enums\Incident\Gender;
use App\Enums\Incident\Injury;
use App\Enums\Incident\MembershipType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreIncidentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $membershipType = MembershipType::tryFrom($this->membership_type);
        $membershipTypePublic = in_array($membershipType, [
            MembershipType::YouthPublic,
            MembershipType::AdultPublic,
        ]);

        return [
            'date' => ['required', 'date'],
            'time' => ['required', 'date_format:H:i'],
            'location_name' => ['required', 'string'],
            'location_description' => ['required', 'string'],

            'injured' => ['required', 'in:yes,no'],

            'injured_name' => ['required_if_accepted:injured', 'string'],
            'injured_dob' => ['required_if_accepted:injured', 'date'],
            'injured_gender' => ['required_if_accepted:injured', Rule::enum(Gender::class)],

            'membership_type' => ['required', Rule::enum(MembershipType::class)],
            'group_name' => [Rule::requiredIf(! $membershipTypePublic), 'string'],
            'contact_name' => ['required', 'string'],
            'contact_phone' => [Rule::requiredIf($membershipTypePublic), 'string'],
            'contact_address' => [Rule::requiredIf($membershipTypePublic), 'string'],

            'injuries' => ['required_if_accepted:injured', 'array'],
            'injuries.*' => ['required_if_accepted:injured', Rule::enum(Injury::class)],
            'emergency_services' => ['required_if_accepted:injured', 'in:yes,no'],
            'hospital' => ['required_if_accepted:injured', 'in:yes,no'],

            'damaged' => ['required', 'in:yes,no'],

            'details' => ['required', 'string'],
            'first_aid' => ['required', 'string'],

            'reporter_name' => ['required', 'string'],
            'reporter_email' => ['required', 'email'],
            'reporter_phone' => ['required'],
        ];
    }
}
