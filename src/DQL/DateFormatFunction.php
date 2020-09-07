<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/8 0008
 * Time: 下午 15:10
 */

namespace App\DQL;


use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

class DateFormatFunction extends FunctionNode
{
    protected $dateExpression;
    protected $formatString;

    /**
     * @param \Doctrine\ORM\Query\SqlWalker $sqlWalker
     *
     * @return string
     */
    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        return 'DATE_FORMAT(' . $sqlWalker->walkStringPrimary($this->dateExpression)
            . ',' . $sqlWalker->walkStringPrimary($this->formatString) . ')';
    }

    /**
     * @param \Doctrine\ORM\Query\Parser $parser
     *
     * @return void
     */
    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->dateExpression = $parser->StringPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->formatString = $parser->StringPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}