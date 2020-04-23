<?php

namespace App;

use Nette;
use Nette\Application\Routers\RouteList;
use Nette\Application\Routers\Route;


class RouterFactory {

	/**
	 * @return Nette\Application\IRouter
	 */
	public static function createRouter() {
		$router = new RouteList;

		// admin routing
		$router[] = new Route('admin/<presenter>/<action>[/<id>]', array(
			'module' => 'Admin',
			'presenter' => 'Default',
			'action' => 'default',
			'id' => NULL,
		));

		// frontend routing
		//$router[] = new Route('[<lang>][/<id>]', array(
		$router[] = new Route('<presenter>/<action>[/<id>]', array(
			'module' => 'Frontend',
			'presenter' => 'Homepage',
			'action' => 'default',
			'id' => NULL,
		));

		return $router;
	}
}
