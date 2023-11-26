<?php

namespace App\Contracts;


Interface DataBaseConnectionInterface{

    public function connect();
    public function getConnection();
}