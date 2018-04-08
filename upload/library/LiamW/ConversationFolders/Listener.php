<?php

namespace LiamW\ConversationFolders;

class Listener
{
	public static function extendConversationController($class, array &$extend)
	{
		$extend[] = 'LiamW\ConversationFolders\Extend\ControllerPublic\Conversation';
	}

	public static function extendConversationInlineModController($class, array &$extend)
	{
		$extend[] = 'LiamW\ConversationFolders\Extend\ControllerPublic\InlineMod\Conversation';
	}

	public static function extendConversationModel($class, array &$extend)
	{
		$extend[] = 'LiamW\ConversationFolders\Extend\Model\Conversation';
	}

	public static function extendConversationInlineModModel($class, array &$extend)
	{
		$extend[] = 'LiamW\ConversationFolders\Extend\Model\InlineMod\Conversation';
	}

	public static function extendConversationDataWriter($class, array &$extend)
	{
		$extend[] = 'LiamW\ConversationFolders\Extend\DataWriter\ConversationMaster';
	}

	public static function extendConversationsRoute($class, array &$extend)
	{
		$extend[] = 'LiamW\ConversationFolders\Extend\Route\Prefix\Conversations';
	}
}