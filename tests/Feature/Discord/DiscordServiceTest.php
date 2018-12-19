<?php

namespace Tests\Feature\Discord;

use App\Service\DiscordService;
use PHPUnit\Framework\MockObject\MockObject;
use RestCord\DiscordClient;
use RestCord\Model\Guild\Guild;
use RestCord\Model\Guild\GuildMember;
use Tests\TestCase;

class DiscordServiceTest extends TestCase
{

	/** @var DiscordClient|MockObject */
	private $discordMock;

	/** @var GuildMember|MockObject */
	private $guildMemberMock;

	/** @var GuildMember|MockObject */
	private $guildMock;

	public function setUp()
	{
		$this->discordMock = $this
			->getMockBuilder(DiscordClient::class)
			->disableOriginalConstructor()
			->getMock();

		$this->guildMemberMock = $this
			->getMockBuilder(GuildMember::class)
			->disableOriginalConstructor()
			->getMock();

		$this->guildMock = $this
			->getMockBuilder(Guild::class)
			->setMethods(['getGuild', 'getGuildMember'])
			->getMock();
		
		parent::setUp();
	}

	public function tearDown()
	{
		$this->discordMock = null;
		$this->guildMemberMock = null;
		$this->guildMock = null;
		parent::tearDown();
	}

	/**
	 * @test
	 */
	public function get_discord_roles_member()
	{
		$this->guildMemberMock->roles = [1234, 1345];

		$this->guildMock->roles = [
			$this->rolesArrayToObject(['name' => '@everyone', 'id' => 1234]),
			$this->rolesArrayToObject(['name' => 'Bot', 'id' => '5678']),
			$this->rolesArrayToObject(['name' => '@admin', 'id' => '1345'])
		];

		$this->guildMock
			->expects($this->once())
			->method('getGuild')
			->willReturn($this->guildMock);

		$this->guildMock
			->expects($this->once())
			->method('getGuildMember')
			->willReturn($this->guildMemberMock);

		$this->discordMock->guild = $this->guildMock;
		$discordService = new DiscordService($this->discordMock);
		$rolesForMember = $discordService->getMemberRoles(1234);

		$this->assertEquals(['@everyone', '@admin'], $rolesForMember);
	}

	/**
	 * @param array $data
	 * @return object
	 */
	private function rolesArrayToObject(array $data)
	{
		return (object) $data;
	}

}
