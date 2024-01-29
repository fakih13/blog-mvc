<?php

namespace App\Controller;


class Index
{
    public function home(){
        require_once('../src/views/index.php');
    }

    public function admin(){
        require_once('../src/views/admin/index.php');
    }
}
