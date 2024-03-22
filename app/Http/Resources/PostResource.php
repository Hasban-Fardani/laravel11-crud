<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    // define properties
    public $success;
    public $message;
    public $resource;
    
    /**
     * Create a new resource instance.
     * 
     * @param bool $success
     * @param string $message
     * @param mixed $resource
     */
    public function __construct(int $success, string $message, $resource)
    {
        parent::__construct($resource);
        $this->success = $success;
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
            'success' => $this->success,
            'message' => $this->message,
            'data' => $this->resource
        ];
    }
}
