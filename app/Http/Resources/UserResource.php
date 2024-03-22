<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public $resource;
    public $token;
    public $message;
    public function __construct($message, $token, $resource)
    {
        parent::__construct($resource);
        $this->token = $token;
        $this->message = $message;
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user' => $this->resource,
            'token' => $this->token,
            'message' => $this->message
        ];
    }
}
