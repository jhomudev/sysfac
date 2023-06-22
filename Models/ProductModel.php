<?php

require_once "MainModel.php";

class ProductModel extends MainModel
{

  // Funcion de obtener productos
  protected static function getProductsModel(array $filters = []): array
  {
    $words = $filters['words'];
    $category_id = $filters['category_id'];
    $is_active = $filters['is_active'];

    if (empty($words)) $products = MainModel::connect()->prepare("SELECT p.product_id,p.link_image, p.file_image, p.name, p.price_sale,p.unit, p.sale_for, p.category_id,p.inventary_min, p.is_active,c.name AS category FROM products p INNER JOIN categories c ON p.category_id = c.cat_id ORDER BY p.product_id DESC");
    if (!empty($words)) {
      $words = "%$words%";
      $products = MainModel::connect()->prepare("SELECT p.product_id,p.link_image,p.file_image,p.name, p.price_sale,p.unit, p.sale_for, p.category_id,p.inventary_min, p.is_active,c.name AS category FROM products p INNER JOIN categories c ON p.category_id = c.cat_id WHERE p.name LIKE :words ORDER BY p.product_id DESC");
      $products->bindParam(":words", $words, PDO::PARAM_STR);
    }
    if (empty($words) && (!empty($category_id) || $is_active != "")) {
      $sentence = "";

      foreach ($filters as $column => $value) {
        if ($value != "") {
          if ($sentence == "") $sentence .= "p.$column=" . $value;
          else $sentence .= " AND p.$column=" . $value;
        }
      }
      $products = MainModel::connect()->prepare("SELECT p.product_id,p.link_image,p.file_image,p.name, p.price_sale,p.unit, p.sale_for, p.category_id,p.inventary_min, p.is_active,c.name AS category FROM products p INNER JOIN categories c ON p.category_id = c.cat_id WHERE $sentence ORDER BY p.product_id DESC");
    }

    $products->execute();

    $products = $products->fetchAll();

    return $products;
  }

  // Funcion de obtener productos
  protected static function getDataProductModel(mixed $product_id_or_name): array
  {
    $query = "SELECT * FROM products WHERE product_id=:product_id OR name=:name";
    $product = MainModel::connect()->prepare($query);
    $product->bindParam(":product_id", $product_id_or_name);
    $product->bindParam(":name", $product_id_or_name);

    $product->execute();

    return $product->fetch();
  }

  // Funcion para crear producto
  protected static function createProductModel(array $data): bool
  {
    if (!empty($data['file_image'])) {
      $statement = MainModel::connect()->prepare("INSERT INTO 
      products(file_image, name,inventary_min,price_sale,unit,sale_for,category_id,is_active,created_at) 
      VALUES(:file_image, :name,:inventary_min,:price_sale,:unit,:sale_for,:category_id,:is_active,:created_at)");

      $statement->bindParam(":file_image", $data['file_image'], PDO::PARAM_LOB);
    } else if (!empty($data['link_image'])) {
      $statement = MainModel::connect()->prepare("INSERT INTO 
      products(link_image, name,inventary_min,price_sale,unit,sale_for,category_id,is_active,created_at) 
      VALUES(:link_image, :name,:inventary_min,:price_sale,:unit,:sale_for,:category_id,:is_active,:created_at)");

      $statement->bindParam(":link_image", $data['link_image'], PDO::PARAM_STR);
    }

    if (empty($data['link_image']) && empty($data['file_image'])) {
      $statement = MainModel::connect()->prepare("INSERT INTO 
      products(file_image,link_image, name,inventary_min,price_sale,unit,sale_for,category_id,is_active,created_at) 
      VALUES(:file_image,:link_image, :name,:inventary_min,:price_sale,:unit,:sale_for,:category_id,:is_active,:created_at)");

      $statement->bindValue(":link_image", null, PDO::PARAM_NULL);
      $statement->bindValue(":file_image", null, PDO::PARAM_NULL);
    }

    $statement->bindParam(":name", $data['name'], PDO::PARAM_STR);
    $statement->bindParam(":inventary_min", $data['inventary_min'], PDO::PARAM_INT);
    $statement->bindParam(":price_sale", $data['price_sale'], PDO::PARAM_STR);
    $statement->bindParam(":unit", $data['unit'], PDO::PARAM_STR);
    $statement->bindParam(":sale_for", $data['sale_for'], PDO::PARAM_INT);
    $statement->bindParam(":category_id", $data['category_id'], PDO::PARAM_INT);
    $statement->bindParam(":is_active", $data['is_active'], PDO::PARAM_BOOL);
    $statement->bindParam(":created_at", $data['created_at'], PDO::PARAM_STR);

    return $statement->execute();
  }

  // Funcion para editar producto  
  protected static function editProductModel(array $new_data): bool
  {
    $statement = MainModel::connect()->prepare("UPDATE products SET file_image=:file_image, link_image=:link_image, name=:name, inventary_min=:inventary_min, price_sale=:price_sale, unit=:unit, sale_for=:sale_for,category_id=:category_id, is_active=:is_active WHERE product_id=:product_id");

    if (!empty($new_data['file_image'])) {
      $statement->bindParam(":file_image", $new_data['file_image'], PDO::PARAM_LOB);
      $statement->bindValue(":link_image", null, PDO::PARAM_NULL);
    } else if (!empty($new_data['link_image'])) {
      $statement->bindParam(":link_image", $new_data['link_image'], PDO::PARAM_STR);
      $statement->bindValue(":file_image", null, PDO::PARAM_NULL);
    }

    if (empty($new_data['link_image']) && empty($new_data['file_image'])) {
      $statement->bindValue(":link_image", null, PDO::PARAM_NULL);
      $statement->bindValue(":file_image", null, PDO::PARAM_NULL);
    }

    $statement->bindParam(":product_id", $new_data['product_id'], PDO::PARAM_INT);
    $statement->bindParam(":link_image", $new_data['link_image'], PDO::PARAM_STR);
    $statement->bindParam(":name", $new_data['name'], PDO::PARAM_STR);
    $statement->bindParam(":inventary_min", $new_data['inventary_min'], PDO::PARAM_INT);
    $statement->bindParam(":price_sale", $new_data['price_sale'], PDO::PARAM_STR);
    $statement->bindParam(":unit", $new_data['unit'], PDO::PARAM_STR);
    $statement->bindParam(":sale_for", $new_data['sale_for'], PDO::PARAM_INT);
    $statement->bindParam(":category_id", $new_data['category_id'], PDO::PARAM_INT);
    $statement->bindParam(":is_active", $new_data['is_active'], PDO::PARAM_BOOL);

    return $statement->execute();
  }

  // Funciòn eliminar producto
  protected static function deleteProductModel(int $product_id): bool
  {
    $statement = MainModel::connect()->prepare("DELETE FROM products WHERE product_id=:product_id");
    $statement->bindParam(":product_id", $product_id);

    return $statement->execute();
  }

  //? FUNCIONES PARA PRODUCTOS_ALL
  // Funcion de obtener todos los productos en el inventario
  protected static function getProductsInventaryModel(array $filters = []): array
  {
    $words = $filters['words'];
    $product_id = $filters['product_id'];
    $local_id = $filters['local_id'];
    $state = $filters['state'];

    if (empty($words)) $products_all = MainModel::connect()->prepare("SELECT pa.product_unit_id, pa.serial_number,pa.state, pa.local_id, p.name AS product_name FROM products_all pa INNER JOIN products p ON p.product_id = pa.product_id ORDER BY p.product_id DESC");
    if (!empty($words)) {
      $words = "%$words%";
      $products_all = MainModel::connect()->prepare("SELECT pa.product_unit_id, pa.serial_number, pa.state, pa.local_id, p.name AS product_name FROM products_all pa INNER JOIN products p ON p.product_id = pa.product_id WHERE p.name LIKE :words OR pa.serial_number LIKE :words ORDER BY p.product_id DESC");
      $products_all->bindParam(":words", $words, PDO::PARAM_STR);
    }
    if (empty($words) && (!empty($product_id) || !empty($local_id) || !empty($state))) {
      $sentence = "";

      foreach ($filters as $column => $value) {
        if (!empty($value)) {
          if ($sentence == "") $sentence .= "pa.$column=" . $value;
          else $sentence .= " AND pa.$column=" . $value;
        }
      }
      $products_all = MainModel::connect()->prepare("SELECT pa.product_unit_id, pa.serial_number, pa.state, pa.local_id, p.name AS product_name FROM products_all pa INNER JOIN products p ON p.product_id = pa.product_id INNER JOIN categories c ON p.category_id = c.cat_id WHERE $sentence ORDER BY p.product_id DESC");
    }


    $products_all->execute();

    return $products_all->fetchAll();
  }

  // Funcion para editar producto de inventario, solo estado y local
  protected static function editProductInventaryModel(array $new_data): bool
  {
    if (isset($new_data['local_id'])) {
      $statement = MainModel::connect()->prepare("UPDATE products_all SET local_id=:local_id WHERE product_unit_id=:product_unit_id");

      $statement->bindParam(":product_unit_id", $new_data['product_unit_id'], PDO::PARAM_INT);
      $statement->bindParam(":local_id", $new_data['local_id'], PDO::PARAM_INT);
    } else {
      $statement = MainModel::connect()->prepare("UPDATE products_all SET state=:state WHERE product_unit_id=:product_unit_id");

      $statement->bindParam(":product_unit_id", $new_data['product_unit_id'], PDO::PARAM_INT);
      $statement->bindParam(":state", $new_data['state'], PDO::PARAM_INT);
    }

    return $statement->execute();
  }
}
