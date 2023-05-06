<?php

require_once "MainModel.php";

class ProductModel extends MainModel
{

  // Funcion de obtener productos
  protected static function getProductsModel(array $filters = [])
  {
    $words = $filters['words'];
    $column = $filters['column'];
    $value = $filters['value'];

    if (empty($words) && empty($column) && empty($value)) $products = MainModel::connect()->prepare("SELECT p.product_id,p.link_image,p.name, p.price_sale,p.unit,p.category_id,p.is_active,c.name AS category FROM products p INNER JOIN categories c ON p.category_id = c.cat_id ORDER by p.product_id DESC");
    else {
      if (!empty($words)) {
        $words = "%$words%";
        $products = MainModel::connect()->prepare("SELECT p.product_id,p.link_image,p.name, p.price_sale,p.unit,p.category_id,p.is_active,c.name AS category FROM products p INNER JOIN categories c ON p.category_id = c.cat_id WHERE p.name LIKE :words ORDER by p.product_id DESC");
        $products->bindParam(":words", $words, PDO::PARAM_STR);
      };
      if (!empty($column) && isset($value)) {
        $products = MainModel::connect()->prepare("SELECT p.product_id,p.link_image,p.name, p.price_sale,p.unit,p.category_id,p.is_active,c.name AS category FROM products p INNER JOIN categories c ON p.category_id = c.cat_id WHERE p.$column=:value ORDER by p.product_id DESC");
        $products->bindParam(":value", $value, PDO::PARAM_STR);
      }
    }

    $products->execute();

    return $products;
  }

  // Funcion de obtener productos
  protected static function getDataProductModel(int $product_id)
  {
    $query = "SELECT * FROM products WHERE product_id=:product_id";
    $product = MainModel::connect()->prepare($query);
    $product->bindParam(":product_id", $product_id);

    $product->execute();

    return $product;
  }

  // Funcion para crear producto
  protected static function createProductModel(array $data)
  {
    $statement = MainModel::connect()->prepare("INSERT INTO 
    products(link_image, name,inventary_min,price_sale,unit,category_id,is_active,created_at) 
    VALUES(:link_image, :name,:inventary_min,:price_sale,:unit,:category_id,:is_active,:created_at)");

    $statement->bindParam(":link_image", $data['link_image'], PDO::PARAM_STR);
    $statement->bindParam(":name", $data['name'], PDO::PARAM_STR);
    $statement->bindParam(":inventary_min", $data['inventary_min'], PDO::PARAM_INT);
    $statement->bindParam(":price_sale", $data['price_sale'], PDO::PARAM_STR);
    $statement->bindParam(":unit", $data['unit'], PDO::PARAM_STR);
    $statement->bindParam(":category_id", $data['category_id'], PDO::PARAM_INT);
    $statement->bindParam(":is_active", $data['is_active'], PDO::PARAM_BOOL);
    $statement->bindParam(":created_at", $data['created_at'], PDO::PARAM_STR);
    $statement->execute();

    return $statement;
  }

  // Funcion para editar producto  
  protected static function editProductModel(array $new_data)
  {
    $statement = MainModel::connect()->prepare("UPDATE products SET link_image=:link_image, name=:name, inventary_min=:inventary_min, price_sale=:price_sale, unit=:unit, category_id=:category_id, is_active=:is_active WHERE product_id=:product_id");

    $statement->bindParam(":product_id", $new_data['product_id'], PDO::PARAM_INT);
    $statement->bindParam(":link_image", $new_data['link_image'], PDO::PARAM_STR);
    $statement->bindParam(":name", $new_data['name'], PDO::PARAM_STR);
    $statement->bindParam(":inventary_min", $new_data['inventary_min'], PDO::PARAM_INT);
    $statement->bindParam(":price_sale", $new_data['price_sale'], PDO::PARAM_STR);
    $statement->bindParam(":unit", $new_data['unit'], PDO::PARAM_STR);
    $statement->bindParam(":category_id", $new_data['category_id'], PDO::PARAM_INT);
    $statement->bindParam(":is_active", $new_data['is_active'], PDO::PARAM_BOOL);

    $statement->execute();

    return $statement;
  }

  // Funciòn eliminar producto
  protected static function deleteProductModel(int $product_id)
  {
    $statement = MainModel::connect()->prepare("DELETE FROM products WHERE product_id=:product_id");
    $statement->bindParam(":product_id", $product_id);
    $statement->execute();

    return $statement;
  }
}
