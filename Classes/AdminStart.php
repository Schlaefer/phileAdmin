<?php

namespace Phile\Plugin\Siezi\PhileAdmin;

use Phile\Plugin\Siezi\PhileAdmin\Lib\AdminController;

class AdminStart extends AdminController
{

    public function index()
    {
        return $this->render('start/index.twig');
    }

}
