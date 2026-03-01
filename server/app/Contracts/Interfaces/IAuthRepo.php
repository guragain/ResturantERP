<?php

namespace App\Contracts\Interfaces;

use App\Models\User;
use LoginResultDTO;

interface IAuthRepo
{
    public function register(array $data):User;
    public function login(array $credentials):?LoginResultDTO;
}