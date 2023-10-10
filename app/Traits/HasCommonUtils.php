<?php

declare(strict_types=1);

namespace App\Traits;

trait HasCommonUtils
{
	protected function intro(): void {
		$this->line("  _    _                                                   _          ");
		$this->line(" | |  | |                                                 (_)         ");
		$this->line(" | |__| |  _   _   _ __     ___   _ __    ___    ___       _    ___   ");
		$this->line(" |  __  | | | | | | '_ \   / _ \ | '__|  / __|  / _ \     | |  / _ \  ");
		$this->line(" | |  | | | |_| | | |_) | |  __/ | |    | (__  |  __/  _  | | | (_) | ");
		$this->line(" |_|  |_|  \__, | | .__/   \___| |_|     \___|  \___| (_) |_|  \___/  ");
		$this->line("            __/ | | |                                                 ");
		$this->line("           |___/  |_|                                                 ");
		$this->line("                                                                      ");
	}
}
