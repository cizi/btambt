<?php

namespace App\Enum;

class LitterApplicationStateEnum {

	/** @const stav zavedení záznamu */
	const INSERT = 0;

	/** @const stav zavedení záznamu, resp. už bylo zavedeno */
	const REWRITTEN = 1;
}