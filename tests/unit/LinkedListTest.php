<?php

namespace Tests\Unit;

use App\Linkedlist\LinkedList;
use App\Linkedlist\ListNode;
use PHPUnit\Framework\TestCase;

class LinkedListTest extends TestCase
{
    private $linkedList;

    protected function setUp(): void
    {
        parent::setUp();
        $this->linkedList = new LinkedList();
    }

    /** @test */
    function when_given_data_to_list_node_should_get_data_back()
    {
        $listNode = new ListNode(12);
        $this->assertNotNull(12, $listNode->data);
        $this->assertNull($listNode->next);
    }

    /** @test */
    function newly_created_linkedlist_should_have_first_node_null()
    {
        $linkedList = new LinkedList();
        $this->assertNull($linkedList->getFirstNode());
    }

    /** @test */
    function newly_created_linkedlist_should_have_total_zero()
    {
        $linkedList = new LinkedList();
        $this->assertEquals(0, $linkedList->getTotal());
    }

    /** @test */
    function when_insert_1_to_linkedlist_should_have_firstnode_with_1()
    {
        $this->linkedList->insert(1);
        $this->assertEquals(1, $this->linkedList->getFirstNode()->data);
        $this->assertEquals(1, $this->linkedList->getTotal());
    }

    /** @test */
    function when_insert_1_and_2_should_have_total_of_2()
    {
        $this->linkedList->insert(1);
        $this->linkedList->insert(2);
        $this->assertEquals(2, $this->linkedList->getTotal());
    }

    /** @test */
    function when_insert_1_and_2_should_have_2_at_last_node()
    {
        $this->linkedList->insert(1);
        $this->linkedList->insert(2);
        $this->assertEquals(2, $this->linkedList->lastNode()->data);
    }

    /** @test */
    function when_given_1_2_should_have_1_2_in_display_output()
    {
        $this->linkedList->insert(1);
        $this->linkedList->insert(2);
        $display = $this->linkedList->display();
        $this->assertStringContainsString('1', $display);
        $this->assertStringContainsString('2', $display);
    }

    /** @test */
    function when_inserting_value_at_first_node_should_have_value_at_first_node()
    {
        $this->linkedList->insert(3);
        $this->linkedList->insertAtFirst(4);
        $this->assertEquals(4, $this->linkedList->getFirstNode()->data);

        $this->linkedList->insertAtFirst(1);
        $this->assertEquals(1, $this->linkedList->getFirstNode()->data);
        $this->assertEquals(3, $this->linkedList->getTotal());
    }


    /** @test */
    function when_1_2_inserted_should_find_1_2()
    {
        $this->linkedList->insert(1);
        $this->linkedList->insert(2);
        $this->assertEquals(1, $this->linkedList->find(1)->data);
        $this->assertEquals(2, $this->linkedList->find(2)->data);
        $this->assertNull($this->linkedList->find(4));
    }

    /** @test */
    function when_insertedBefore_2_on_empty_linkedlist_total_should_be_0()
    {
        $this->linkedList->insertBefore(3, 2);
        $this->assertEquals(0, $this->linkedList->getTotal());
    }

    /** @test */
    function when_1_2_inserted_and_3_inserted_before_2_3_should_be_between_1_and_2()
    {
        $this->linkedList->insert(1);
        $this->linkedList->insert(2);
        $this->linkedList->insertBefore(3, 2);
        $this->assertEquals(3, $this->linkedList->getTotal());
        $one = $this->linkedList->find(1);
        $two = $this->linkedList->find(3);
        $this->assertEquals(3, $one->next->data);
        $this->assertEquals(2, $two->next->data);
    }

    /** @test */
    function when_insertedAfter_2_on_empty_linkedlist_total_should_be_0()
    {
        $this->linkedList->insertAfter(3, 2);
        $this->assertEquals(0, $this->linkedList->getTotal());
    }

    /** @test */
    function when_1_2_inserted_and_3_inserted_after_1_3_should_be_between_1_and_2()
    {
        $this->linkedList->insert(1);
        $this->linkedList->insert(2);
        $this->linkedList->insertAfter(3, 1);
        $this->assertEquals(3, $this->linkedList->getTotal());
        $one = $this->linkedList->find(1);
        $two = $this->linkedList->find(3);
        $this->assertEquals(3, $one->next->data);
        $this->assertEquals(2, $two->next->data);
    }

    /** @test */
    function when_deleting_first_node_total_should_decrement()
    {
        $this->linkedList->insert(1);
        $this->linkedList->deleteFirst();

        $this->assertEquals(0, $this->linkedList->getTotal());
    }

    /** @test */
    function when_1_2_inserted_and_first_node_deleted_first_node_should_be_2()
    {
        $this->linkedList->insert(1);
        $this->linkedList->insert(2);
        $this->linkedList->deleteFirst();
        $this->assertEquals(2, $this->linkedList->getFirstNode()->data);
        $this->assertEquals(1, $this->linkedList->getTotal());
        $this->assertNull($this->linkedList->find(1));
    }

    /** @test */
    function when_last_node_deleted_size_should_decrement()
    {
        $this->linkedList->insert(1);
        $this->linkedList->deleteLast();
        $this->assertEquals(0, $this->linkedList->getTotal());
    }

    /** @test */
    function when_1_2_inserted_and_last_node_deleted_last_node_should_be_1()
    {
        $this->linkedList->insert(1);
        $this->linkedList->insert(2);
        $this->linkedList->deleteLast();
        $this->assertEquals(1, $this->linkedList->getFirstNode()->data);
        $this->assertEquals(1, $this->linkedList->getTotal());
        $this->assertNull($this->linkedList->find(2));
    }

    /** @test */
    function when_first_node_deleted_size_should_decrement()
    {
        $this->linkedList->insert(1);
        $this->linkedList->delete(1);
        $this->assertEquals(0, $this->linkedList->getTotal());
    }

    /** @test */
    function when_1_2_3_inserted_and_2_deleted_should_contain_only_1_2()
    {
        $this->linkedList->insert(1);
        $this->linkedList->insert(2);
        $this->linkedList->insert(3);
        $this->linkedList->delete(2);
        $this->assertEquals(1, $this->linkedList->getFirstNode()->data);
        $this->assertEquals(3, $this->linkedList->lastNode()->data);
        $this->assertNull($this->linkedList->find(2));
        $this->assertEquals(2, $this->linkedList->getTotal());
    }

    /** @test */
    function when_1_2_3_inserted_and_list_reversed_should_be_3_2_1()
    {
        $this->linkedList->insert(1);
        $this->linkedList->insert(2);
        $this->linkedList->insert(3);
        $reversed = $this->linkedList->reverse();
        $this->assertEquals(3, $reversed->getTotal());
        $this->assertEquals(3, $reversed->getFirstNode()->data);
        $reversed = $reversed->getFirstNode()->next;
        $this->assertEquals(2, $reversed->data);
        $reversed = $reversed->next;
        $this->assertEquals(1, $reversed->data);
    }

    /** @test */
    function when_getnth_node_on_empty_list_should_return_null()
    {
        $this->assertNull($this->linkedList->getNthNode(1));
    }

    /** @test */
    function when_1_2_3_inserted_and_getNthNode_with_2_called_should_return_3()
    {
        $this->linkedList->insert(1);
        $this->linkedList->insert(2);
        $this->linkedList->insert(3);
        $this->assertEquals(1, $this->linkedList->getNthNode(0)->data);
        $this->assertEquals(3, $this->linkedList->getNthNode(2)->data);
    }

    /** @test */
    function implements_iterator_interface()
    {
        $this->linkedList->insert(1);
        $this->linkedList->insert(2);
        $this->linkedList->insert(3);
        $list = [];
        foreach ($this->linkedList as $n) {
            $list[] = $n;
        }

        $this->assertEquals($this->linkedList->getNthNode(0)->data, $list[0]);
        $this->assertEquals($this->linkedList->getNthNode(1)->data, $list[1]);
        $this->assertEquals($this->linkedList->getNthNode(2)->data, $list[2]);
    }

}