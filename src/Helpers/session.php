<?php

use Jmarreros\Session\Session;

function session(): Session{
	return app()->session;
}