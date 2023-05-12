<?php

if ($requestFetch) {
  require_once "./../Models/CategoryModel.php";
} else {
  require_once "./Models/CategoryModel.php";
}

class CategoryController extends CategoryModel
{
  // Función controlador para obtener los usuarios
  public function getCategoriesController()
  {
    $categories = CategoryModel::getCategoriesModel();
    return json_encode($categories);
  }

  // Funcion controlador para obetenr los datos de usuario
  public function getDataCategoryController()
  {
    $category_id = intval($_POST['categoryId']);
    $category = CategoryModel::getDataCategoryModel($category_id);
    return json_encode($category);
  }

  // Funcion controlador para crear o editar usuario
  public function createCategoryController()
  {
    $name = MainModel::clearString($_POST['tx_nombre']);
    $link_image = MainModel::clearString($_POST['tx_linkImage']);
    $description = MainModel::clearString($_POST['tx_descripcion']);

    // Validacion de campos vacios
    if (empty($name)) {
      $alert = [
        "Alert" => "simple",
        "title" => "Campos vacios",
        "text" => "Por favor. Complete todos los campos.",
        "icon" => "warning"
      ];
      return json_encode($alert);
      exit();
    }

    // validación de duplicidad de nombre
    $sql_verify = MainModel::executeQuerySimple("SELECT * FROM categories WHERE name='$name'");
    $categories = $sql_verify->fetchAll();
    $duplicated = count($categories) > 0;
    if ($duplicated) {
      $alert = [
        "Alert" => "simple",
        "title" => "Duplicidad de datos",
        "text" => "El nombre de cada categoría es único.",
        "icon" => "warning"
      ];
      return json_encode($alert);
      exit();
    }

    $data = [
      "link_image" => $link_image,
      "name" => $name,
      "description" => $description,
      "created_at" =>  date('Y-m-d H:i:s'),
    ];

    $stm = CategoryModel::createCategoryModel($data);

    if ($stm) {
      $alert = [
        "Alert" => "alert&reload",
        "title" => "Categoría creada",
        "text" => "La categortía se creó exitosamente.",
        "icon" => "success"
      ];
    } else {
      $alert = [
        "Alert" => "simple",
        "title" => "Opps. Ocurrió un problema",
        "text" => "La categoría no se creó.",
        "icon" => "error"
      ];
    }

    return json_encode($alert);
    exit();
  }
  // Funcion controlador para crear o editar usuario
  public function editCategoryController()
  {
    $cat_id = MainModel::clearString($_POST['tx_category_id']);
    $name = MainModel::clearString($_POST['tx_nombre']);
    $link_image = MainModel::clearString($_POST['tx_linkImage']);
    $description = MainModel::clearString($_POST['tx_descripcion']);

    // Validacion de campos vacios
    if (empty($name)) {
      $alert = [
        "Alert" => "simple",
        "title" => "Campos vacios",
        "text" => "Por favor. Complete todos los campos.",
        "icon" => "warning"
      ];
      return json_encode($alert);
      exit();
    }

    // validación de duplicidad de nombre
    $sql_verify = MainModel::executeQuerySimple("SELECT * FROM categories WHERE cat_id<>$cat_id AND name='$name'");
    $categories = $sql_verify->fetchAll();
    $duplicated = count($categories) > 0;
    if ($duplicated) {
      $alert = [
        "Alert" => "simple",
        "title" => "Duplicidad de datos",
        "text" => "El nombre de cada categoría es único.",
        "icon" => "warning"
      ];
      return json_encode($alert);
      exit();
    }

    $new_data = [
      "category_id" => $cat_id,
      "link_image" => $link_image,
      "name" => $name,
      "description" => $description,
    ];

    $stm = CategoryModel::editcategoryModel($new_data);

    if ($stm) {
      $alert = [
        "Alert" => "alert&reload",
        "title" => "Categoría modificada",
        "text" => "La categoría se modificó exitosamente.",
        "icon" => "success"
      ];
    } else {
      $alert = [
        "Alert" => "simple",
        "title" => "Opps. Ocurrió un problema",
        "text" => "La categoría no se modificó.",
        "icon" => "error"
      ];
    }

    return json_encode($alert);
    exit();
  }

  // Funcion controlador para eliminar usuario
  public function deleteCategoryController()
  {
    $category_id = intval($_POST['tx_category_idDel']);

    // Verificacion si usexisten productos de esta categoria
    $query_verify = MainModel::executeQuerySimple("SELECT * FROM products WHERE category_id=$category_id");
    $products = $query_verify->fetchAll();
    $exist_products = count($products) > 0;

    if ($exist_products) {
      $alert = [
        "Alert" => "simple",
        "title" => "Acción rechazada",
        "text" => "Hay productos que pertenecen a esta categoría, por lo cual no puede eliminarla.",
        "icon" => "warning"
      ];

      return json_encode($alert);
      exit();
    }

    $stm = CategoryModel::deleteCategoryModel($category_id);

    if ($stm) {
      $alert = [
        "Alert" => "alert&reload",
        "title" => "Categoría eliminado",
        "text" => "Las categoría se eliminó exitosamente.",
        "icon" => "success"
      ];
    } else {
      $alert = [
        "Alert" => "simple",
        "title" => "Opps. Al parecer ocurrió  un error",
        "text" => "Categoría no eliminada.",
        "icon" => "error"
      ];
    }

    return json_encode($alert);
    exit();
  }
}
