<?php

namespace Model;

use PDO;

/**
 * 모든 모델이 상속 받을 기초 모델
 */
abstract class BaseModel
{
    /**
     * ini 파일은 '.' 으로 시작해야 서버 설정으로 보호 가능
     * '.' 으로 시작하는 파일에 대해서는 별도 설정 필요
     *
     * @var array|false 모든 영역에서 ini 파일의 데이터 사용 가능
     */
    protected array|false $ini;

    /**
     * @var PDO
     */
    protected PDO $pdo;

    /**
     * 생성자에서 ini 파일을 읽어와 PDO 기본 세팅
     *
     * EX)
     * [DATABASE]
     * HOST = localhost
     * DBNAME = sample
     * USERNAME = root
     * PASSWORD = root
     *
     * [SAMPLE]
     * phpversion[] = "5.0"
     * phpversion[] = "5.1"
     * phpversion[] = "5.2"
     * phpversion[] = "5.3"
     */
    public function __construct()
    {
        $this->ini = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . '/.sample.ini', true);
        $this->pdo = new PDO(
            "mysql:host={$this->ini['DATABASE']['HOST']};dbname={$this->ini['DATABASE']['DBNAME']}",
            $this->ini['DATABASE']['USERNAME'],
            $this->ini['DATABASE']['PASSWORD']
        );

        // PDO 기능이 아닌 데이터베이스 자체의 Prepared Statement 기능 사용
        $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        // 에러가 발생하는 경우 EXCEPTION 을 던지도록 설정
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * TODO: 쿼리 빌더
     *
     * @return QueryBuilder 쿼리 빌더
     */
    public function queryBuilder(): QueryBuilder
    {
        return new QueryBuilder($this->pdo);
    }
}