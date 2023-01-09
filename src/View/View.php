<?php

namespace Jmarreros\View;

interface View {
	public function render( string $string ): string;
}