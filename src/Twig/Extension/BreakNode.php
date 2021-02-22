<?php

namespace Btinet\Ringhorn\Twig\Extension;

use Twig\Compiler;
use Twig\Node\Node;

class BreakNode extends Node
{
    public function compile(Compiler $compiler)
    {
        $compiler->addDebugInfo($this);

        $compiler->write("break;\n");
    }
}