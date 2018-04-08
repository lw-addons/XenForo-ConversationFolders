<?php

class LiamW_ConversationFolders_Listener
{
	public static function extendConversationController($class, array &$extend)
	{
		$extend[] = 'LiamW_ConversationFolders_Extend_ControllerPublic_Conversation';
	}

	public static function extendConversationInlineModController($class, array &$extend)
	{
		$extend[] = 'LiamW_ConversationFolders_Extend_ControllerPublic_InlineMod_Conversation';
	}

	public static function extendConversationModel($class, array &$extend)
	{
		$extend[] = 'LiamW_ConversationFolders_Extend_Model_Conversation';
	}

	public static function extendConversationInlineModModel($class, array &$extend)
	{
		$extend[] = 'LiamW_ConversationFolders_Extend_Model_InlineMod_Conversation';
	}

	public static function extendConversationDataWriter($class, array &$extend)
	{
		$extend[] = 'LiamW_ConversationFolders_Extend_DataWriter_ConversationMaster';
	}

	public static function extendConversationsRoute($class, array &$extend)
	{
		$extend[] = 'LiamW_ConversationFolders_Extend_Route_Prefix_Conversations';
	}
}