<?php

namespace App\Controller;


class Promotion
{
  /**
   * Display the promotion page
   * @return void
   */
  public function display()
  {
    $result = 'ok';
    require_once('../src/views/admin/promotion/index.php');
    return;
  }

  public function add()
  {
    require_once('../src/views/admin/promotion/add.php');
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
      # code...
    }
  }

  public function remove()
  {
    echo 'ok';
  }

  public function update($id)
  {
    $query = urldecode($id);
  }
}
