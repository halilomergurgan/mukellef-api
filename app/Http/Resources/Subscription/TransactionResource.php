<?php

namespace App\Http\Resources\Subscription;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user->id,
            'username' => $this->user->name,
            'subscription_id' => $this->subscription_id,
            'renewal_at' => $this->subscription->renewal_at,
            'created_at' => Carbon::parse($this->created_at)->format('d-m-Y H:i:s'),
        ];
    }
}
