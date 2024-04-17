<?php

namespace App\Controller;

use App\Model\OrderModel;


class Order
{
  private $orderModel;

  public function __construct()
  {
    $this->orderModel = new OrderModel();
  }
  public function display()
  {
    $data = $this->getOrder();
    require_once('../src/views/admin/order/index.php');
  }
  public function getOrder()
  {
    $data = $this->orderModel->getOrder();
    return $data;
  }
  public function stateOrder()
  {
    if ($_SERVER["REQUEST_METHOD"] == "PUT") {
    }
    $this->display();
  }
}
