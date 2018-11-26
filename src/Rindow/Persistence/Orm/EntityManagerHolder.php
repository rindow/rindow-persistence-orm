<?php
namespace Rindow\Persistence\Orm;

interface EntityManagerHolder
{
    public function getCurrentEntityManager();
}