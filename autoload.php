<?php
spl_autoload_register(function($className) {
    $className = ltrim($className, '\\');
    $fileName  = '';
    $namespace = '';
    if ($lastNsPos = strrpos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;

    }
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
    $controlName = 'control'.ucfirst($fileName);

//    $modulPath = __DIR__ . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . $fileName;
//    $controllerPath = __DIR__ . DIRECTORY_SEPARATOR . 'controller' . DIRECTORY_SEPARATOR . 'control'.ucfirst($fileName);

    if (file_exists(modelPath.$fileName)) {
        require(modelPath.$fileName);
    }

    if (file_exists(controllerPath.$controlName)) {
        require(controllerPath.$controlName);
    }
});
