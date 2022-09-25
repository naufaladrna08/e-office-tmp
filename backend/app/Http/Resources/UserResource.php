<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class UserResource extends JsonResource {
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
   */
  public function toArray($request) {
    return [
      'id' => $this->id,
      'name' => $this->name,
      'username' => $this->username,
      'email' => $this->email,
      'created_at' => Carbon::parse($this->created_at)->toDayDateTimeString()
    ];
  }
}
