<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class UserData extends Data
{
    public function __construct(
        public string $name,
        public string $email,
        public ?string $gender,
        public ?string $birthday,
        public ?string $bio,
        public ?string $password,
    ) {}
}
