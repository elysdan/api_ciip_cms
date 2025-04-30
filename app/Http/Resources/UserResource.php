<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //return parent::toArray($request);
        return [
            'id' => $this->id,
            'username' => $this->username,
            'name' => $this->name,
            'surname' => $this->surname,
            'email' => $this->email,
            'email_verified' => $this->email_verified_at !== null,
            'role' => new RoleResource($this->whenLoaded('role')),
            'photo_url' => $this->photo_path ? Storage::url($this->photo_path) : null,
            'status' => $this->status,
            'last_login_at' => $this->last_login_at?->diffForHumans(),
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
