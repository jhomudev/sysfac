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
    return json_encode($categories->fetchAll());
  }

  // Funcion controlador para obetenr los datos de usuario
  public function getDataCategoryController()
  {
    $category_id = intval($_POST['categoryId']);
    $category = CategoryModel::getDataCategoryModel($category_id);
    return json_encode($category->fetch());
  }

  // Funcion controlador para crear o editar usuario
  public function createCategoryController()
  {
    $name = mainModel::clearString($_POST['tx_nombre']);
    $link_image = mainModel::clearString($_POST['tx_linkImage']);
    $description = mainModel::clearString($_POST['tx_descripcion']);

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
    $cat_id = mainModel::clearString($_POST['tx_category_id']);
    $name = mainModel::clearString($_POST['tx_nombre']);
    $link_image = mainModel::clearString($_POST['tx_linkImage']);
    $description = mainModel::clearString($_POST['tx_descripcion']);

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
    $category_id = $_POST['tx_user_id'];

    // Verificacion si usuario a eliminar es superadmin
    $query_verify = MainModel::executeQuerySimple("SELECT * FROM users WHERE user_id=$category_id AND type=" . USER_TYPE->superadmin . "");
    $categories = $query_verify->fetchAll();
    $isSuperAdmin = count($categories) > 0;

    if ($isSuperAdmin) {
      $alert = [
        "Alert" => "simple",
        "title" => "Acción rechazada",
        "text" => "Los superadmins no pueden ser eliminados.",
        "icon" => "warning"
      ];

      return json_encode($alert);
      exit();
    }

    $stm = CategoryModel::deleteCategoryModel($category_id);

    if ($stm) {
      $alert = [
        "Alert" => "alert&reload",
        "title" => "Usuario eliminado",
        "text" => "El usuario se eliminó exitosamente.",
        "icon" => "success"
      ];
    } else {
      $alert = [
        "Alert" => "simple",
        "title" => "Opps. Al parecer ocurrió  un error",
        "text" => "Usuario no eliminado.",
        "icon" => "error"
      ];
    }

    return json_encode($alert);
    exit();
  }
}
