<?php

namespace Tests\Feature\Repository;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RepositoryTest extends TestCase
{
	use RefreshDatabase;

	/** @test */
	public function get_by_slug()
	{
		$this->assertTrue(true);
	}
}
