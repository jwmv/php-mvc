<?php

namespace Controller;

abstract class BaseController
{
    /**
     * 'POST', 'GET', 'PATCH', 'PUT', 'DELETE' 등 요청 된 메소드 사용
     * $_SERVER['REQUEST_METHOD'] 값을 사용
     *
     * @var string
     */
    protected string $requestMethod;

    /**
     * $_POST, $_GET 같은 HTTP 메소드 변수가 없는 PATCH, PUT, DELETE 메소드의 경우
     * Body Content 값을 읽어서 사용
     *
     * @var array
     */
    protected array $bodyContent;

    /**
     * URL 주소를 ['키' => '값', ...] 형태의 배열로 저장
     * EX) /sports/soccer/player/7
     *     -> ['sports' => 'soccer', 'player' => '7']
     *
     * @var array
     */
    protected array $parameterMap;

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
    protected string $executableFn;

    /**
     * @param string $requestMethod 요청 메소드 (GET, POST, PATCH, PUT, DELETE)
     * @param array $bodyContent 요청 콘텐츠 (json to array, POST 데이터와 동일)
     * @param array $parameterMap URL 파싱 데이터
     * @param string $executableFn URL 파싱 데이터 중 실행할 함수명
     */
    public function __construct(string $requestMethod, array $bodyContent, array $parameterMap, string $executableFn)
    {
        $this->requestMethod = $requestMethod;
        $this->bodyContent = $bodyContent;
        $this->parameterMap = $parameterMap;
        $this->executableFn = $executableFn;

        // patchSports() 조합으로 함수 실행
        $fn = ($this->requestMethod . $this->executableFn);
        $this->$fn();
    }

    /**
     * @param string $filename 파일명
     * @param string $filetype 파일 종류(viewer, header, footer)
     * @return string
     */
    private function viewPath(string $filename, string $filetype): string
    {
        $viewPath = '';
        switch ($filetype) {
            case 'viewer':
                $viewPath = $_SERVER['DOCUMENT_ROOT'] . '/View/' . $filename . '.php';
                break;
            case 'header':
            case 'footer':
                $viewPath = $_SERVER['DOCUMENT_ROOT'] . '/View/template/' . $filename . '.php';
                break;
        }
        return $viewPath;
    }

    /**
     * @param string $viewerFile 뷰어 파일 이름
     * @param string $headerFile 헤더 파일 이름
     * @param string $footerFile 푸터 파일 이름
     * @return void
     */
    protected function view(string $viewerFile, string $headerFile = 'defaultHeader', string $footerFile = 'defaultFooter'): void
    {
        include_once $this->viewPath($headerFile, 'header');
        include_once $this->viewPath($viewerFile, 'viewer');
        include_once $this->viewPath($footerFile, 'footer');
    }
}