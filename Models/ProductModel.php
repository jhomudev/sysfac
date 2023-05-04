<?php

require_once "MainModel.php";

class ProductModel extends MainModel
{

  // Funcion de obtener productos
  protected static function getProductsModel(array $filters = [])
  {
    // $filters=[
    //   "words"=>"hhh",
    //   "column" =>"category_id",
    //   "value" =>1,
    // ];
    $words = $filters['words'];
    $column = $filters['column'];
    $value = $filters['value'];

    $query = "SELECT * FROM products";
    if (isset($words)) {
      $query = "SELECT * FROM products WHERE name LIKE :words";
      $products = MainModel::connect()->prepare($query);
      $products->bindParam(":words", "%$words%");
    };
    if (isset($column) && isset($value)) {
      $query = "SELECT * FROM products WHERE $column=:value";
      $products = MainModel::connect()->prepare($query);
      $products->bindParam(":value", $value);
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
    products(name,description,inventary_min,price_sale,unit,category_id,is_active,created_at) 
    VALUES(:name,:description,:inventary_min,:price_sale,:unit,:category_id,:is_active,:created_at)");

    $statement->bindParam(":name", $data['name']);
    $statement->bindParam(":description", $data['description']);
    $statement->bindParam(":inventary_min", $data['inventary_min']);
    $statement->bindParam(":price_sale", $data['price_sale']);
    $statement->bindParam(":unit", $data['unit']);
    $statement->bindParam(":category_id", $data['category_id']);
    $statement->bindParam(":is_active", $data['is_active']);
    $statement->bindParam(":created_at", $data['created_at']);
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

  // Funcion para editar producto  
  protected static function editProductModel(array $new_data)
  {
    $statement = MainModel::connect()->prepare("UPDATE products SET name=:name, description=:description, inventary_min=:inventary_min, price_sale=:price_sale, unit=:unit, category_id=:category_id, is_active=:is_active, created_at=:created_at WHERE product_id=:product_id");

    $statement->bindParam(":product_id", $new_data['product_id']);
    $statement->bindParam(":name", $new_data['name']);
    $statement->bindParam(":description", $new_data['description']);
    $statement->bindParam(":inventary_min", $new_data['inventary_min']);
    $statement->bindParam(":price_sale", $new_data['price_sale']);
    $statement->bindParam(":unit", $new_data['unit']);
    $statement->bindParam(":category_id", $new_data['category_id']);
    $statement->bindParam(":is_active", $new_data['is_active']);
    $statement->bindParam(":created_at", $new_data['created_at']);

    $statement->execute();

    return $statement;
  }
}
