<?php

class AdminThemesController extends AdminThemesControllerCore
{
    public function init()
	{
        $this->logged_on_addons = false;
        parent::init();
	}
}
