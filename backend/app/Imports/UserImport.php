<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;

class UserImport implements ToModel {
  /**
  * @param array $row
  *
  * @return \Illuminate\Database\Eloquent\Model|null
  */
  public function model(array $row) {
    $user = new User([
      'id' => $row[0],
      'name' => $row[1],
      'email' => $row[2], 
      'username' => $row[3],
      'password' => '$2y$10$m.giH5z1gDgUWiWEscHWnOIRhfTOazFIp6dLryGmBYbv4wt8wgeF6', // 12341234
      'code_divisi' => $row[4], 
      'code_jabatan' => $row[5], 
      'last_action' => 'REGISTERED',
      'profile_photo_id' => 1, 
      'cover_photo_id' => 0,
    ]);

    $user->assignRole('user');

    return $user;
  }
}
