> 작업 진행중입니다.

### 테스트 환경
- XAMPP for Windows 8.2.4
  - Apache 2.4.56
  - PHP 8.2.4
  - MariaDB 10.4.28

### 웹서버 설정
- `php.ini`
  - __extension_dir__ : PHP 확장 라이브러리 경로
  - __extension=pdo_mysql__ : PDO 사용
  - __expose_php=Off__ : PHP 버전 정보 숨김
- `httpd.conf`
  - __ServerSignature Off__ : 아파치 서버 정보 숨김
  - __ServerTokens Prod__ : 아파치 서버 정보 숨김
  - __Alias /assets "/project_path/view/assets"__ : 뷰 파일 전체 경로 숨김
  - __Options +FollowSymLinks -MultiViews__
  - ```
    <IfModule mod_rewrite.c>
        RewriteEngine on
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteRule ^ /index.php [L,QSA] # index.php 에서 모든 경로 처리
    </IfModule>
    ```
  - ```
    <Directory "/project_path/controller">
        Require all denied # Controller 폴더 직접 접근 불가
    </Directory>
    ```
  - ```
    <Directory "/project_path/model">
        Require all denied # Model 폴더 직접 접근 불가
    </Directory>
    ```
  - ```
    <Files ".*">
        Require all denied # '.' 으로 시작하는 모든 파일 접근 불가
    </Files>
    ```

### 파일 구조
- Controller 파일들은 `BaseController` 파일을 기본으로 상속 받아서 사용
- Model 파일들은 `BaseModel` 파일을 기본으로 상속 받아서 사용
- Controller, Model, View 외에는 루트 폴더에 다른 경로를 만들지 않음
- `.ini` 파일에 기본 설정 변수값을 지정해서 사용

### URL 규칙
- POST
  - `/sports` 스포츠 객체 생성 [postSports() 실행]
  - `/sports/{sportsId}/player` 해당 스포츠의 플레이어 객체 생성 [postPlayer() 실행]
  - `/auth/login` login 처리 [postLogin() 실행, 없는 경우 postAuth() 실행]
- GET
  - `/sports` 모든 스포츠 목록 읽어오기 [getSports() 실행]
  - `/sports/{sportsId}` 해당 스포츠 읽어오기 [getSports() 실행] 
    - `$parameterMap['sprots']` 값이 있는 지 확인 필요 
  - `/sports/{sportsId}/player` 모든 선수 목록 읽어오기 [getPlayer() 실행]
  - `/sports/{sportsId}/player/{playerId}` 해당 스포츠 해당 선수 읽어오기 [getPlayer() 실행] 
    - `$parameterMap['sprots']`, `$parameterMap['player']` 값이 있는 지 확인 필요
- PATCH
  - `/sports/{sportsId}` 해당 스포츠 수정 [patchSports() 실행]
  - `/sports/{sportsId}/player/{playerId}` 해당 스포츠 해당 선수 수정 [patchPlayer() 실행]
- PUT
  - `/sports/{sportsId}` 해당 스포츠 수정 [putSports() 실행]
  - `/sports/{sportsId}/player/{playerId}` 해당 스포츠 해당 선수 수정 [putPlayer() 실행]
- DELETE
  - `/sports/{sportsId}` 해당 스포츠 삭제 [deleteSports() 실행]
  - `/sports/{sportsId}/player/{playerId}` 해당 스포츠 해당 선수 삭제 [deletePlayer() 실행]