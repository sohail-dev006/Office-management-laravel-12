<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Hash;

class EmployeeResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'designation' => $this->designation,
            'email' => $this->email,
            'password'   => Hash::make($request->password),
            'address' => $this->address,
            'phone' => $this->phone,
            'gender' => $this->gender,
            'dob' => $this->dob,
            'join_date' => $this->join_date,
            'job_type' => $this->job_type,
            'city' => $this->city,
            'salary' => $this->salary,
            'status' => $this->status,
            'age' => $this->age,
            'created_at' => $this->created_at ? $this->created_at->toDateTimeString() : null,
            'updated_at' => $this->updated_at ? $this->updated_at->toDateTimeString() : null,
        ];
    }
}
