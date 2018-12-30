<?php
namespace App;

/**
 * Post status valid
 */
abstract class Status
{

	const PENDING = 'pending';
	const REJECT  = 'reject';
	const ACCEPTED = 'accepted';
	const WRITING  = 'writing';
}