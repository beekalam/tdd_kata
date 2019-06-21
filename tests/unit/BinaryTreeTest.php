<?php

namespace Tests\Unit;

use App\Tree\BinaryNode;
use App\Tree\BinaryTree;
use PHPUnit\Framework\TestCase;

class BinaryTreeTest extends TestCase
{

    /** @test */
    function it_()
    {
        $final = new BinaryNode("Final");

        $tree = new BinaryTree($final);

        $semiFinal1 = new BinaryNode("Semi Final 1");
        $semiFinal2 = new BinaryNode("Semi Final 2");
        $quarterFinal1 = new BinaryNode("Quarter Final 1");
        $quarterFinal2 = new BinaryNode("Quarter Final 2");
        $quarterFinal3 = new BinaryNode("Quarter Final 3");
        $quarterFinal4 = new BinaryNode("Quarter Final 4");

        $semiFinal1->addChildren($quarterFinal1, $quarterFinal2);
        $semiFinal2->addChildren($quarterFinal3, $quarterFinal4);

        $final->addChildren($semiFinal1, $semiFinal2);

        $ans = <<<EOT
-Semi Final 1
--Quarter Final 1
--Quarter Final 2
-Semi Final 2
--Quarter Final 3
--Quarter Final 4
EOT;

        $ret = str_replace("\n","\r\n",$tree->traverse($tree->root));
        $ret = trim($ans);
        $this->assertEquals($ans,$ret);
    }
}