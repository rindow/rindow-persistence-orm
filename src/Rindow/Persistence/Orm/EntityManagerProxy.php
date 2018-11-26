<?php
namespace Rindow\Persistence\Orm;

interface EntityManagerProxy
{
    public function setEntityManagerHolder(/* EntityManagerHolder */$entityManagerHolder);
}