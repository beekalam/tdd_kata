<?php

namespace Tests\Unit;

use App\Tree\Tree;
use App\Tree\TreeNode;
use PHPUnit\Framework\TestCase;


class TreeTest extends TestCase
{

    /** @test */
    function when_given_children_to_treenode_should_retain_all()
    {
        $tn = new TreeNode(12);
        $tn->addChildren(new TreeNode(12));

        $this->assertEquals(1, count($tn->children));
        $this->assertEquals(12, $tn->data);
    }

    /** @test */
    function newly_created_tree_should_have_root_of_not_null()
    {
        $tn = new TreeNode(13);
        $t = new Tree($tn);
        $this->assertNotNull($t->root);
    }

    /** @test */
    function whne_()
    {
        $ceo = new TreeNode("CEO");
        $tree = new Tree($ceo);
        $cto = new TreeNode("CTO");
        $cfo = new TreeNode("CFO");
        $cmo = new TreeNode("CMO");
        $coo = new TreeNode("COO");

        $ceo->addChildren($cto);
        $ceo->addChildren($cfo);
        $ceo->addChildren($cmo);
        $ceo->addChildren($coo);

        $seniorArchitect = new TreeNode("Senior Architect");
        $softwareEngineer = new TreeNode("Software Engineer");
        $userInterfaceDesigner = new TreeNode("User Interface Designer");
        $qualityAssuranceEngineer = new TreeNode("Quality Assurance Engineer");

        $cto->addChildren($seniorArchitect);
        $seniorArchitect->addChildren($softwareEngineer);
        $cto->addChildren($qualityAssuranceEngineer);
        $cto->addChildren($userInterfaceDesigner);

        $res=<<<EOT
CEO
-CTO
--Senior Architect
---Software Engineer
--Quality Assurance Engineer
--User Interface Designer
-CFO
-CMO
-COO
EOT;
        $ans = str_replace("\n","\r\n",$tree->traverse($tree->root));
        $ans = trim($ans);
        $this->assertEquals($res,$ans);

    }
}