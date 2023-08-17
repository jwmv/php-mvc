<?php

error_reporting(E_ALL);
ini_set('display_errors', TRUE);

// PHP 에러를 exception 핸들러에서 처리
set_error_handler(/** @throws ErrorException */function($errNo, $errStr, $errFile, $errLine) {
    throw new ErrorException($errStr, $errNo, 0, $errFile, $errLine);
});

set_exception_handler(function(Throwable $exception) {
    header('Content-type: application/json');
    exit(json_encode([
        'message' => $exception->getMessage(),
        'code' => $exception->getCode(),
        'filename' => basename($exception->getFile()),
        'line' => $exception->getLine()
    ], JSON_UNESCAPED_UNICODE));
});

// 클래스 인스턴스가 생성 될 때, namespace 및 class 이름 받아서 인클루딩
// namespace 값은 폴더 구조에 맞게 지정
spl_autoload_register(/** @throws Exception */ function($class) {
    $class = str_replace('\\', '/', $class);

    if (is_file($class . '.php')) {
        include_once $class . '.php';
    } else {
        throw new Exception('Not found.', 404);
    }
});

// 라우터 실행
new Controller\Router();