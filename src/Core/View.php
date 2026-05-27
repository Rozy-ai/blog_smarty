<?php

declare(strict_types=1);

namespace App\Core;

use Smarty\Smarty;

final class View
{
    private Smarty $smarty;

    public function __construct(array $paths)
    {
        $smarty = new Smarty();
        $smarty->setTemplateDir($paths['templates']);
        $smarty->setCompileDir($paths['compile']);
        $smarty->setCacheDir($paths['cache']);
        $smarty->setCaching(Smarty::CACHING_OFF);

        $this->smarty = $smarty;
    }

    public function render(string $template, array $data = []): void
    {
        foreach ($data as $key => $value) {
            $this->smarty->assign($key, $value);
        }

        $this->smarty->display($template);
    }
}
