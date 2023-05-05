<?php

require_once "MainModel.php";

class CategoryModel extends MainModel
{
  // Funcion de obtener categorias
  protected static function getCategoriesModel()
  {
    $categories = MainModel::executeQuerySimple("SELECT * FROM categories");

    return $categories;
  }

  // Funcion de obtener datos de una categoria
  protected static function getDataCategoryModel(int $category_id)
  {
    $category = MainModel::connect()->prepare("SELECT * FROM categories WHERE cat_id =:category_id");
    $category->bindParam(":category_id",$category_id);
    $category->execute();

    return $category;
  }

  // Funcion para crear categoeria 
  protected static function createCategoryModel(array $data)
  {
    $statement = MainModel::connect()->prepare("INSERT INTO 
    categories(link_image,name,description,created_at) 
    VALUES(:link_image,:name,:description,:created_at)");

    $statement->bindParam(":link_image", $data['link_image']);
    $statement->bindParam(":name", $data['name']);
    $statement->bindParam(":description", $data['description']);
    $statement->bindParam(":created_at", $data['created_at']);

    $statement->execute();

    return $statement;
  }

  // Funciòn eliminar usuario
  protected static function deleteCategoryModel(int $category_id)
  {
    $statement = MainModel::connect()->prepare("DELETE FROM categories WHERE cat_id=:category_id");
    $statement->bindParam(":category_id", $category_id);
    $statement->execute();

    return $statement;
  }

  // Funcion para editar usuario  
  protected static function editcategoryModel(array $new_data)
  {
    $statement = MainModel::connect()->prepare("UPDATE categories SET link_image=:link_image, name=:name, description=:description WHERE cat_id=:category_id");

    $statement->bindParam(":category_id", $new_data['category_id']);
    $statement->bindParam(":link_image", $new_data['link_image']);
    $statement->bindParam(":name", $new_data['name']);
    $statement->bindParam(":description", $new_data['description']);

    $statement->execute();

    return $statement;
  }
}
