<?php

namespace Tests\Unit;

use App\Tree\BST;
use PHPUnit\Framework\TestCase;

class BSTTest extends TestCase
{
    private $bst;

    protected function setUp(): void
    {
        parent::setUp();
        // $this->bst = new BST();
    }

    /** @test */
    function can_create_a_bst()
    {
        $bst = new BST(1);
        $this->assertNotNull($bst);
    }

    /** @test */
    function can_insert_node()
    {
        $tree = new BST(10);

        $tree->insert(12);
        $tree->insert(6);
        $tree->insert(3);
        $tree->insert(8);
        $tree->insert(15);
        $tree->insert(13);
        $tree->insert(36);

        $this->assertEquals(8, $tree->nodeCount);
    }

    /** @test */
    function search_for_node()
    {
        $bst = new BST(11);
        $bst->insert(10);
        $bst->insert(7);
        $bst->insert(8);
        $this->assertTrue($bst->search(11)->data == 11);
        $this->assertNull($bst->search(9));
    }

    /** @test */
    function can_delete_a_node()
    {
        $bst = new BST(11);
        $bst->insert(10);
        $bst->insert(9);
        $bst->remove(9);
        $this->assertTrue($bst->search(11)->data == 11);
        $this->assertNull($bst->search(9));
        $this->assertEquals(2, $bst->nodeCount);
    }
}