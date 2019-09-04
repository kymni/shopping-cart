<?php 
declare(strict_types=1);
session_start(); // start a new session
class Shoppingcart {
  public $products = [
    [ "name" => "Sledgehammer", "price" => 125.75 ],
    [ "name" => "Axe", "price" => 190.50 ],
    [ "name" => "Bandsaw", "price" => 562.131 ],
    [ "name" => "Chisel", "price" => 12.9 ],
    [ "name" => "Hacksaw", "price" => 18.45 ],
  ];
  public $cart = array(); // initialize array of cart items

  public function getCart(){ //method to retrieve items in cart
    return $this->cart;
  }
  public function addToCart($item){ // method to add item to cart
    if (array_key_exists($item, $this->cart)) {
      $this->cart[$item] += 1; // if cart item already exists increment its count by 1 
    } else {
      $this->cart[$item] = 1; // else initialize item with a count of 1 in the cart
    }
  }

  public function removeFromCart($item){ // method to remove cart item
    if (array_key_exists($item, $this->cart)) {
      if ($this->cart[$item] > 1) {
        $this->cart[$item] -= 1; // if count of items in cart is more than 1, decrement its count by 1
      } else {
        unset($this->cart[$item]); // if only one item of this type left in cart, remove item from cart
      }
    }
  }

  public function getProducts(){ //method to retrieve products
    return $this->products;
  }

  public function getProductPrice($product): float { // get product price
    foreach ($this->products as $prod) {
        if ($prod["name"] == $product) {
            return $prod["price"];
        }
    }
    return null;
  }
}

// initialize cart object as a session variable
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = new Shoppingcart();
}

// add or remove cart item depending on action submitted
if (!empty($_POST) && isset($_POST["action"]) && isset($_POST["item"])) {
  if ($_POST["action"] == "add") {
    $_SESSION['cart']->addToCart($_POST["item"]);
  } elseif ($_POST["action"] == "remove") {
    $_SESSION['cart']->removeFromCart($_POST["item"]);
  }
  unset($_POST["action"]);
  unset($_POST["item"]);
}
?>

<style>
table {
  float: left;
  margin-right: 50px;
}
th {
  text-align: left;
}
form {
  margin: auto;
}
</style>
<table>
  <thead>
    <tr><th colspan=3>Products</th></tr>
    <tr><th>Item</th><th>Price</th><th></th></tr>
  </thead>
  <tbody>
<?php
$products = $_SESSION['cart']->getProducts();
foreach ($products as $product):?>
    <tr>
      <td><?=$product["name"]?></td>
      <td><?=number_format(round($product["price"], 2), 2)?></td>
      <td>
        <form id="add<?=$product["name"]?>" action="" method="post">
          <input type="hidden" name="action" value="add"/>
          <input type="hidden" name="item" value="<?=$product["name"]?>"/>
          <button onclick='document.getElementById("add<?=$product["name"]?>").submit()'>Add to cart</button>
        </form>
      </td>
    </tr>
<?php endforeach;?>
  </tbody>
</table>
<table>
  <thead>
    <tr><th colspan=4>Cart</th></tr>
    <tr><th>Item</th><th>Price</th><th>Quantity</th><th>Total</th><th></th></tr>
  </thead>
  <tbody>
<?php 
$cart_items = $_SESSION['cart']->getCart();
$total_price = 0;
foreach ($cart_items as $item => $count):
  $item_price = $_SESSION['cart']->getProductPrice($item);
  $total_item_price = number_format(round($item_price * $count, 2), 2);
  $total_price += $total_item_price;?>
    <tr>
      <td><?=$item?></td>
      <td><?=$item_price?></td>
      <td align="middle"><?=$count?></td>
      <td><?=$total_item_price?></td>
      <td>
        <form id="remove<?=$item?>" action="" method="post">
          <input type="hidden" name="action" value="remove"/>
          <input type="hidden" name="item" value="<?=$item?>"/>
          <button onclick='document.getElementById("remove<?=$item?>").submit()'>Remove from cart</button>
        </form>
      </td>
    </tr>
<?php endforeach;?>
    <tr>
      <td colspan=3><b>Total price</b></td>
      <td><?=number_format(round($total_price, 2), 2)?></td>
    </tr>
  </tbody>
</table>
