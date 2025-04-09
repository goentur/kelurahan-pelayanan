<?php

namespace App\Repositories\Common;

trait HeaderFooterPdf
{
    protected $headerFn;
    protected $footerFn;

    public function SetHeader(\Closure $fn)
    {
        $this->headerFn = $fn;
    }

    public function Header()
    {
        if ($fn = $this->headerFn) {
            $fn($this);
        }
    }

    public function SetFooter(\Closure $fn)
    {
        $this->footerFn = $fn;
    }

    public function Footer()
    {
        if ($fn = $this->footerFn) {
            $fn($this);
        }
    }
}
