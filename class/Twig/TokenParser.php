<?php

namespace Twig;
class TokenParser extends \Twig_Extensions_TokenParser_Trans {
    public $_values = [];

    public function parse(\Twig_Token $token) {
        /*     if (!$this->_pot) {
            $this->_pot = new POT();
        }*/

        $lineno = $token->getLine();
        $stream = $this->parser->getStream();
        $count = null;
        $plural = null;
        $notes = null;

        if (!$stream->test(\Twig_Token::BLOCK_END_TYPE)) {
            $body = $this->parser->getExpressionParser()->parseExpression();
        } else {
            $stream->expect(\Twig_Token::BLOCK_END_TYPE);
            $body = $this->parser->subparse(array($this, 'decideForFork'));
            $next = $stream->next()->getValue();

            if ('plural' === $next) {
                $count = $this->parser->getExpressionParser()->parseExpression();
                $stream->expect(\Twig_Token::BLOCK_END_TYPE);
                $plural = $this->parser->subparse(array($this, 'decideForFork'));

                if ('notes' === $stream->next()->getValue()) {
                    $stream->expect(\Twig_Token::BLOCK_END_TYPE);
                    $notes = $this->parser->subparse(array($this, 'decideForEnd'), true);
                }
            } elseif ('notes' === $next) {
                $stream->expect(\Twig_Token::BLOCK_END_TYPE);
                $notes = $this->parser->subparse(array($this, 'decideForEnd'), true);
            }
        }

        $stream->expect(\Twig_Token::BLOCK_END_TYPE);

        if ($body instanceof \Twig_Node_Expression_Constant) {
            $msgid = trim($body->getAttribute("value"));
            $this->_values[$msgid] = ["lineno" => $lineno, "value" => $msgid];
        } elseif ($body instanceof \Twig_Node_Text) {
            $msgid = trim($body->getAttribute("data"));
            $this->_values[$msgid] = ["lineno" => $lineno, "value" => $msgid];
        } elseif ($body instanceof \Twig_Node) {
            $str = "";
            foreach($body as $node) {
                if ($node instanceof \Twig_Node_Text) {
                    $str .= $node->getAttribute("data");
                } elseif ($node instanceof \Twig_Node_Print) {
                    foreach($node as $exp) {
                        $str .= "%" . $exp->getAttribute("name") . "%";
                    }
                }
            }
            $msgid = trim($str);

            $this->_values[$msgid] = ["lineno" => $lineno, "value" => $msgid];
        }

        $this->checkTransString($body, $lineno);

        return new \Twig_Extensions_Node_Trans($body, $plural, $count, $notes, $lineno, $this->getTag());

        return;
        // echo "Test";
        // outp($this);
        // foreach()
        $stream = $this->parser->getStream();

        $value = $stream->getCurrent()->getValue();
        outP($stream->getCurrent());
        $this->_values[] = $value;
        outp($value);
        // print_r($token);
        return parent::parse($token);
    }
}

?>