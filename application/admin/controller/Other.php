<?php
namespace app\admin\controller;
use app\admin\controller\Init;
class Other extends Init
{
    public function unicode()
    {
        return $this->fetch('unicode');
    }
}
