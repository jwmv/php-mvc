<?php

namespace Controller;

use Exception;
use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class Router
{
    /**
     * URL 주소를 ['키' => '값', ...] 형태의 배열로 저장
     * EX) /sports/soccer/player/7
     *     -> ['sports' => 'soccer', 'player' => '7']
     *
     * @var array
     */
    private array $parameterMap = [];

    /**
     * URL 주소의 첫번째 Path 값을 컨트롤러 이름으로 사용하고, 컨트롤러 폴더 내에 파일이 존재하는 지 확인
     * EX) /sports/soccer/player/7
     *     -> /SportsController.php
     *
     * @var string
     */
    private string $controller = 'Index';

    /**
     * 컨트롤러 내에 있어야 하는 함수
     * EX1) /sports/soccer/player/7
     *      -> SportsController->player()
     * EX2) /auth/login
     *      -> AuthController->login()
     * EX3) /auth
     *      -> AuthController->auth()
     *
     * @var string
     */
    private string $executableFn = '';

    /**
     * Controller 폴더 내에 존재하는 컨트롤러 경로 및 파일명
     *
     * @var string
     */
    private string $controllerClass = '';

    /**
     * @throws Exception
     */
    public function __construct()
    {
        /**
         * URL 주소에서 각각의 Path 값을 컨트롤러명, 함수명 및 변수값으로 사용할 수 있도록 파싱
         */
        $this->parseRequestUri();

        /**
         * 컨트롤러 폴더 내에서 파싱 된 컨트롤러명의 파일이 있는 지 검색
         */
        $this->findControllerInDirectory();

        /**
         * 함수 및 데이터를 전달받아서 검색 된 컨트롤러에 생성자로 전달
         */
        $this->instantiateClass();
    }

    /**
     * @return void
     */
    private function parseRequestUri(): void
    {
        // URL 주소에서 각각의 Path 값을 배열화
        // EX) /sports/soccer/player/7
        //     -> [0 => 'sports', 1 => 'soccer', 2 => 'player', 3 => '7']
        $path =
            array_values(
                array_filter(
                    array_map('trim', explode('/', parse_url($_SERVER['REQUEST_URI'])['path']))
                )
            )
        ;

        // URL 주소의 첫번째 Path 값을 컨트롤러 이름으로 사용
        // '/' 으로 요청한 경우 Index 컨트롤러 사용
        if (!empty($path[0])) {
            $this->controller = ucfirst($path[0]);
        }

        foreach ($path as $i => $p) {
            if ($i % 2 === 0) {
                // 주소를 ['키' => '값', ...] 형태의 배열로 저장
                $this->parameterMap[$p] = !empty($path[$i + 1]) ? $path[$i + 1] : '';

                // 짝수 주소들은 컨트롤러에서 실행시킬 함수명으로 사용
                if ($i > 0) {
                    $this->executableFn .= ucfirst($p);
                }
            }
        }

        // Path 값이 2개 이하인 경우, 마지막 Path 값을 함수명으로 사용
        // EX) /auth/login
        //     -> AuthController->login()
        // EX) /api/getUserById
        //     -> ApiController->getUserById()
        if (!empty($path) && count($path) <= 2) {
            $this->executableFn = $path[array_key_last($path)];
        }
    }

    /**
     * @return void
     */
    private function findControllerInDirectory(): void
    {
        // 컨트롤러 디렉토리 내의 모든 파일
        $files =
            new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator(
                    __DIR__,
                    FilesystemIterator::SKIP_DOTS | FilesystemIterator::UNIX_PATHS /*| FilesystemIterator::KEY_AS_FILENAME*/)
            );

        // 최상위 경로에 있는 파일부터 검색
        $files->rewind();

        // 파일 검색 위치가 유효하면 계속 실행
        while ($files->valid()) {
            // $files->key() 값은 파일 경로 및 확장자를 포함하는 파일명 값
            // URL 주소의 첫번째 Path 값이 컨트롤러 파일로 생성되어 있는 지 확인
            if (strpos($files->key(), '/' . $this->controller . 'Controller.php')) {
                // 파일 경로에서 Controller/ 부터 값을 가져와서 namespace\\class 형식으로 변환
                // 파일 경로들은 namespace 값으로 사용
                $this->controllerClass =
                    str_replace(
                        '/',
                        '\\',
                        substr(
                            $files->key(),
                            strpos(
                                $files->key(),
                                '\\Controller/') + 1,
                            -4
                        )
                    );
                break;
            }

            // 다음 파일 검색
            $files->next();
        }
    }

    /**
     * @return void
     * @throws Exception
     */
    private function instantiateClass(): void
    {
        // 컨트롤러가 존재하면 인스턴스 생성
        if (class_exists($this->controllerClass)) {
            // 'POST', 'GET', 'PATCH', 'PUT', 'DELETE' 등 요청 된 메소드 사용
            $requestMethod = $_SERVER['REQUEST_METHOD'];

            // $_POST, $_GET 같은 HTTP 메소드 변수가 없는 PATCH, PUT, DELETE 메소드의 경우, Body Content 값을 읽어서 사용
            $bodyContent = json_decode(file_get_contents('php://input'), true) ?: [];

            // 클래스에 실행 가능한 함수가 존재하는 지 확인
            $isExecutable = method_exists($this->controllerClass, $requestMethod . $this->executableFn);

            // 클래스에 실행 가능한 함수가 존재하지 않으면
            if (!$isExecutable) {
                // executableFn 으로 받은 값을 parameterMap 에서 검색해서 해당 key 값을 executableFn 으로 사용
                $this->executableFn = array_search($this->executableFn, $this->parameterMap);

                // 클래스에 실행 가능한 함수가 존재하는 지 다시 확인
                $isExecutable = !empty($this->executableFn) && method_exists($this->controllerClass, $requestMethod . $this->executableFn);

                if (!$isExecutable) {
                    throw new Exception('Function not exists. [' . strtoupper($requestMethod) . strtoupper($this->executableFn) . ']', 400);
                }
            }

            new $this->controllerClass($requestMethod, $bodyContent, $this->parameterMap, $this->executableFn);
        } else {
            throw new Exception('Class not exists.', 400);
        }
    }
}