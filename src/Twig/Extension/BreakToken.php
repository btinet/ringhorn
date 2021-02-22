<?php

namespace Btinet\Ringhorn\Twig\Extension;

use Twig\Error\SyntaxError;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

class BreakToken extends AbstractTokenParser
{
    public function parse(Token $token)
    {
        $lineno = $token->getLine();
        $stream = $this->parser->getStream();

        $stream->expect(Token::BLOCK_END_TYPE);

        return new BreakNode(array(), array(), $lineno, $this->getTag());
    }

    public function getTag()
    {
        return 'break';
    }
}
