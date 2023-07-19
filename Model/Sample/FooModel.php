<?php

namespace Model\Sample;

use Model\BaseModel;

class FooModel extends BaseModel
{
    public function createFoo(array $data)
    {
        $qb = $this->queryBuilder();
        $qb->beginTransaction();
        $foo_id = $qb->table('foo')
            ->insert([
                'alias' => uniqid('foo_alias_'),
                'content' => uniqid('foo_content_'),
                'password' => ['sample', 'password'],
                'created_ip' => $_SERVER['REMOTE_ADDR']
            ])
            ->exec();
        $bar_id = $qb->table('bar')
            ->insert([
                'foo_id' => $foo_id,
                'alias' => uniqid('bar_alias_'),
                'content' => uniqid('bar_content_'),
                'password' => ['sample', 'password'],
                'created_ip' => $_SERVER['REMOTE_ADDR']
            ])
            ->exec();
        $qb->table('baz')
            ->insert([
                'bar_id' => $bar_id,
                'alias' => uniqid('baz_alias_'),
                'content' => uniqid('baz_content_'),
                'password' => ['sample', 'password'],
                'created_ip' => $_SERVER['REMOTE_ADDR']
            ])
            ->exec();
        $result = $qb->commit();

        echo '<br/>';
        echo '<pre>';
        echo '<b>result : </b><br/>';
        var_dump($result);
        echo '</pre>';
    }

    public function getFoo(string $foo_id)
    {
        $qb = $this->queryBuilder();
        $result = $qb->table('foo f')
            ->join('bar b', 'INNER', 'b.foo_id', '=', 'f.id')
            ->join('baz z', 'INNER', 'z.bar_id', '=', 'b.id')
            ->select('f.*')
            ->where('f.alias', 'LIKE', 'foo_alias_%')
            ->where('f.content', 'LIKE', 'foo_content_%')
            ->where('f.id', '=', $foo_id)
            ->where('password', '=', ['sample', 'password'])
            ->orderBy('f.id', 'DESC')
            ->exec();

        echo '<br/>';
        echo '<pre>';
        echo '<b>result : </b><br/>';
        var_dump($result);
        echo '</pre>';
    }

    public function deleteFoo(string $foo_id)
    {
        $qb = $this->queryBuilder();
        $result = $qb->table('foo')
            ->delete()
            ->where('id', '=', $foo_id)
            ->exec();

        echo '<br/>';
        echo '<pre>';
        echo '<b>result : </b><br/>';
        var_dump($result);
        echo '</pre>';
    }
}