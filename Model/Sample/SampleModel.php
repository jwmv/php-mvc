<?php

namespace Model\Sample;

use Model\Model;
use PDO, Exception;

class SampleModel extends Model
{
    /**
     * @param string $message
     * @return false|int
     * @throws Exception
     */
    public function createFoo(string $message): false|int
    {
        if (empty($message)) {
            throw new Exception('입력 값이 올바르지 않습니다.', 400);
        }

        $message = htmlspecialchars($message, ENT_QUOTES);
        if (strlen($message) >= 150) {
            throw new Exception('메시지의 제한 길이 150자를 초과하였습니다.', 400);
        }

        $query = 'INSERT INTO foo SET message = :message';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':message', $message);

        $result = false;
        if ($stmt->execute()) {
            $result = $stmt->rowCount();
        }

        return $result;
    }

    /**
     * @param int $id
     * @return false|array
     * @throws Exception
     */
    public function readFooById(int $id): false|array
    {
        if (empty($id)) {
            throw new Exception('입력 값이 올바르지 않습니다.', 400);
        }

        $query = 'SELECT * FROM foo WHERE id = :id AND deleted_at IS NULL';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        $result = false;
        if ($stmt->execute()) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $result;
    }

    /**
     * @return false|array
     */
    public function readFooAll(): false|array
    {
        $query = 'SELECT * FROM foo WHERE deleted_at IS NULL ORDER BY id DESC';
        $stmt = $this->pdo->prepare($query);

        $result = false;
        if ($stmt->execute()) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $result;
    }

    /**
     * @param string $message
     * @param int $id
     * @return false|int
     * @throws Exception
     */
    public function updateFooById(string $message, int $id): false|int
    {
        if (empty($id) || empty($message)) {
            throw new Exception('입력 값이 올바르지 않습니다.', 400);
        }

        $message = htmlspecialchars($message, ENT_QUOTES);
        if (strlen($message) >= 150) {
            throw new Exception('메시지의 제한 길이 150자를 초과하였습니다.', 400);
        }

        $query = 'UPDATE foo SET message = :message, updated_at = NOW() WHERE id = :id';
        $stmt = $this->pdo->prepare($query);
        $execute = $stmt->execute([
            ':message' => $message,
            ':id' => $id
        ]);

        return $execute ? $stmt->rowCount() : false;
    }

    /**
     * @param int $id
     * @return false|int
     * @throws Exception
     */
    public function deleteFooById(int $id): false|int
    {
        if (empty($id)) {
            throw new Exception('입력 값이 올바르지 않습니다.', 400);
        }

        $query = 'UPDATE foo SET deleted_at = NOW() WHERE id = :id';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);

        $result = false;
        if ($stmt->execute()) {
            $result = $stmt->rowCount();
        }

        return $result;
    }

    /**
     * @param string $comment
     * @param int $fooId
     * @return false|int
     * @throws Exception
     */
    public function createBarWithFooId(string $comment, int $fooId): false|int
    {
        if (empty($fooId) || empty($comment)) {
            throw new Exception('입력 값이 올바르지 않습니다.', 400);
        }

        $comment = htmlspecialchars($comment, ENT_QUOTES);
        if (strlen($comment) >= 150) {
            throw new Exception('메시지의 제한 길이 150자를 초과하였습니다.', 400);
        }

        $query = 'INSERT INTO bar SET comment = :comment, foo_id = :foo_id';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':comment', $comment);
        $stmt->bindParam(':foo_id', $fooId);

        $result = false;
        if ($stmt->execute()) {
            $result = $stmt->rowCount();
        }

        return $result;
    }

    /**
     * @param int $id
     * @return false|array
     * @throws Exception
     */
    public function readBarById(int $id): false|array
    {
        if (empty($id)) {
            throw new Exception('입력 값이 올바르지 않습니다.', 400);
        }

        $query = 'SELECT * FROM bar WHERE id = :id AND deleted_at IS NULL';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);

        $result = false;
        if ($stmt->execute()) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $result;
    }

    /**
     * @return false|array
     */
    public function readBarAll(): false|array
    {
        $query = 'SELECT * FROM bar WHERE deleted_at IS NULL ORDER BY id DESC';
        $stmt = $this->pdo->prepare($query);

        $result = false;
        if ($stmt->execute()) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $result;
    }

    /**
     * @param string $comment
     * @param int $id
     * @return false|int
     * @throws Exception
     */
    public function updateBarById(string $comment, int $id): false|int
    {
        if (empty($id) || empty($comment)) {
            throw new Exception('입력 값이 올바르지 않습니다.', 400);
        }

        $comment = htmlspecialchars($comment, ENT_QUOTES);
        if (strlen($comment) >= 150) {
            throw new Exception('메시지의 제한 길이 150자를 초과하였습니다.', 400);
        }

        $query = 'UPDATE bar SET comment = :comment, updated_at = NOW() WHERE id = :id';
        $stmt = $this->pdo->prepare($query);
        $execute = $stmt->execute([
            ':comment' => $comment,
            ':id' => $id
        ]);

        return $execute ? $stmt->rowCount() : false;
    }

    /**
     * @param int $id
     * @return false|int
     * @throws Exception
     */
    public function deleteBarById(int $id): false|int
    {
        if (empty($id)) {
            throw new Exception('입력 값이 올바르지 않습니다.', 400);
        }

        $query = 'UPDATE bar SET deleted_at = NOW() WHERE id = :id';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);

        $result = false;
        if ($stmt->execute()) {
            $result = $stmt->rowCount();
        }

        return $result;
    }

    /**
     * @param int $fooId
     * @return array
     * @throws Exception
     */
    public function readFooBarByFooIdWithQB(int $fooId): array
    {
        if (empty($fooId)) {
            throw new Exception('입력 값이 올바르지 않습니다.', 400);
        }

        $qb = $this->queryBuilder();
        return $qb->table('foo f')
            ->join('bar b', 'INNER', 'b.foo_id', '=', 'f.id')
            ->select('b.*')
            ->where('b.foo_id', '=', [$fooId, 'EMAIL'])
            ->where('b.deleted_at', 'IS', 'NULL')
            ->where('f.deleted_at', 'IS', 'NULL')
            ->orderBy('b.id', 'DESC')
            // ->exec();
            ->debug();
    }

    /**
     * @param string $fooMessage
     * @param string $barComment
     * @return bool
     * @throws Exception
     */
    public function createFooBarWithQB(string $fooMessage, string $barComment): bool
    {
        if (empty($fooMessage) || empty($barComment)) {
            throw new Exception('입력 값이 올바르지 않습니다.', 400);
        }

        $message = htmlspecialchars($fooMessage, ENT_QUOTES);
        $comment = htmlspecialchars($barComment, ENT_QUOTES);
        if (strlen($message) >= 150 || strlen($comment) >= 150) {
            throw new Exception('메시지의 제한 길이 150자를 초과하였습니다.', 400);
        }

        $qb = $this->queryBuilder();
        $qb->beginTransaction();

        $fooId = $qb->table('foo')
            ->insert([
                'message' => $message
            ])
            ->exec();
        $barId = $qb->table('bar')
            ->insert([
                'foo_id' => $fooId,
                'comment' => $comment
            ])
            ->exec();

        return $qb->commit();
    }

    /**
     * @param string $message
     * @return array|false|int|string
     * @throws Exception
     */
    public function createFooWithQB(string $message): false|array|int|string
    {
        if (empty($message)) {
            throw new Exception('입력 값이 올바르지 않습니다.', 400);
        }

        $msg = htmlspecialchars($message, ENT_QUOTES);
        if (strlen($msg) >= 150) {
            throw new Exception('메시지의 제한 길이 150자를 초과하였습니다.', 400);
        }

        $qb = $this->queryBuilder();
        return $qb->table('foo')
            ->insert([
                'message' => [$msg, 'ALPHABET']
            ])
            ->exec();
    }

    /**
     * @param string $fooId
     * @return array|false|int|string
     * @throws Exception
     */
    public function deleteFooByIdWithQB(string $fooId): false|array|int|string
    {
        $qb = $this->queryBuilder();
        return $qb->table('foo')
            ->update([
                'deleted_at' => date('Y-m-d H:i:s')
            ])
            ->where('id', '=', $fooId)
            ->debug();
//        return $qb->table('foo')
//            ->delete()
//            ->where('id', '=', $fooId)
//            ->exec();
    }
}