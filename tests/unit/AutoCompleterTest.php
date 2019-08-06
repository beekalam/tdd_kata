<?php
use App\FluentFactory\F;
use http\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use App\AutoCompleter\AutoCompleter;

class AutoCompleterTest extends TestCase
{
	/** @test */
	function can_suggest_a_single_word(){
		$s = "the";
		$ac = new AutoCompleter($s);
		$this->assertEquals(["the"], $ac->suggest('t'));
	}

	/** @test */
	function should_return_nothing_when_there_is_no_suggestions(){
		$s = "the";
		$ac = new AutoCompleter($s);
		$this->assertEquals([],$ac->suggest('q'));
	}

	/** @test */
	function can_suggest_a_single_word_in_a_string(){
		$s = "the quick brown fox jumps over lazy dog";
		$ac = new AutoCompleter($s);
		$this->assertEquals(["the"], $ac->suggest('t'));
	}

	/** @test */
	function can_return_a_list_of_suggestions(){
		$s = "the they them";
		$ac = new AutoCompleter($s);
		$this->assertEquals(['the','they','them'], $ac->suggest('t'));
	}

	/** @test */
	function should_suggest_the_best_matches_in_order(){
		$s = "the they them theme themeselves";
		$ac = new AutoCompleter($s);
		$this->assertEquals(['theme','themeselves'], $ac->suggest('theme'));
	}

	/** @test */
	function can_add_new_words_to_the_previous_sentences(){
		$s = "the";
		$ac = new AutoCompleter($s);
		$this->assertEquals(['the'], $ac->suggest('th'));
		$ac->addSentence('them');
		$this->assertEquals(['the','them'], $ac->suggest('th'));
	}

	/** @test */
	function should_not_have_duplicate_suggestions(){
		$s = "the the";		
		$ac = new AutoCompleter($s);
		$this->assertEquals(1, count($ac->suggest('th')));
	}

	/** @test */
	function can_suggest_based_on_frequency_of_used_word(){
		$s="them them the";
		$ac = new AutoCompleter($s);
		$this->assertEquals(['them','the'], $ac->suggest('th'));	
		$ac->addSentence("the the");
		$this->assertEquals(['the','them'], $ac->suggest('th'));
	}

}