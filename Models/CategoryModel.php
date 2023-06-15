<?php

require_once "MainModel.php";

class CategoryModel extends MainModel
{
  // Funcion de obtener categorias
  protected static function getCategoriesModel(): array
  {
    $categories = MainModel::executeQuerySimple("SELECT * FROM categories");

    return $categories->fetchAll();
  }

  // Funcion de obtener datos de una categoria
  protected static function getDataCategoryModel(int $category_id): array
  {
    $category = MainModel::connect()->prepare("SELECT * FROM categories WHERE cat_id =:category_id");
    $category->bindParam(":category_id", $category_id);
    $category->execute();

    return $category->fetch();
  }

  // Funcion para crear categoeria 
  protected static function createCategoryModel(array $data): bool
  {
    if (!empty($data['file_image'])) {
      $statement = MainModel::connect()->prepare("INSERT INTO 
      categories(file_image,name,description,created_at) 
      VALUES(:file_image,:name,:description,:created_at)");

      $statement->bindParam(":file_image", $data['file_image'], PDO::PARAM_LOB);
      $statement->bindParam(":name", $data['name'], PDO::PARAM_STR);
      $statement->bindParam(":description", $data['description'], PDO::PARAM_STR);
      $statement->bindParam(":created_at", $data['created_at'], PDO::PARAM_STR);
    } else {
      $statement = MainModel::connect()->prepare("INSERT INTO 
      categories(link_image,name,description,created_at) 
      VALUES(:link_image,:name,:description,:created_at)");

      $statement->bindParam(":link_image", $data['link_image'], PDO::PARAM_STR);
      $statement->bindParam(":name", $data['name'], PDO::PARAM_STR);
      $statement->bindParam(":description", $data['description'], PDO::PARAM_STR);
      $statement->bindParam(":created_at", $data['created_at'], PDO::PARAM_STR);
    }

    return $statement->execute();
  }

  // Funcion para editar categoria  
  protected static function editcategoryModel(array $new_data): bool
  {
    $statement = MainModel::connect()->prepare("UPDATE categories SET link_image=:link_image, name=:name, description=:description WHERE cat_id=:category_id");

    $statement->bindParam(":category_id", $new_data['category_id']);
    $statement->bindParam(":link_image", $new_data['link_image']);
    $statement->bindParam(":name", $new_data['name']);
    $statement->bindParam(":description", $new_data['description']);

    return $statement->execute();
  }

  // Funciòn eliminar categoria
  protected static function deleteCategoryModel(int $category_id): bool
  {
    $statement = MainModel::connect()->prepare("DELETE FROM categories WHERE cat_id=:category_id");
    $statement->bindParam(":category_id", $category_id);

    return $statement->execute();
  }
}
