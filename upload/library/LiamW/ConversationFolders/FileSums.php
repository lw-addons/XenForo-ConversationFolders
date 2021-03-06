<?php

class LiamW_ConversationFolders_FileSums
{
	public static function addHashes(XenForo_ControllerAdmin_Abstract $controller, array &$hashes)
	{
		$hashes += self::getHashes();
	}

	/**
	 * @return array
	 */
	public static function getHashes()
	{
		return array(
			'library/LiamW/ConversationFolders/DataWriter/ConversationFolder.php' => '92a045ea98f930cc94ff617766920eaf',
			'library/LiamW/ConversationFolders/Extend/ControllerPublic/Conversation.php' => 'fa93df1538c69ec0e7f8258fc1605df4',
			'library/LiamW/ConversationFolders/Extend/ControllerPublic/InlineMod/Conversation.php' => '74df5cf4c2eb83a7dec3bfc4ab24d4f4',
			'library/LiamW/ConversationFolders/Extend/DataWriter/ConversationMaster.php' => '2aa64a9720ef93ba02d6f4f01bdcff86',
			'library/LiamW/ConversationFolders/Extend/Model/Conversation.php' => '93b7057787050810a76151f4200cab7d',
			'library/LiamW/ConversationFolders/Extend/Model/InlineMod/Conversation.php' => '6821d4f5443504735a65e898f1cff48e',
			'library/LiamW/ConversationFolders/Extend/Route/Prefix/Conversations.php' => '7a04737bafb2acea7c7e72d3661bc467',
			'library/LiamW/ConversationFolders/Installer.php' => 'dd94fbd12b1bea327f67eee90ef58e1b',
			'library/LiamW/ConversationFolders/Listener.php' => 'd5d00054a221703559cc09840f40245e',
			'library/LiamW/ConversationFolders/Model/ConversationFolder.php' => 'd1255bbf8aad254ce29a6da2d9b291a2',
			'library/LiamW/ConversationFolders/Option/AutoFile.php' => 'a87a2654daabd3198a32bb1a15d6f6b1',
			'library/LiamW/ConversationFolders/ViewPublic/Conversation/MoveSuccess.php' => '71eeefbb135b91318fae84af6e8bb301',
			'library/LiamW/ConversationFolders/WidgetRenderer/ConversationFolders.php' => 'fa1d85aa2e9d8628b32ef46ed2a9950f',
			'js/conversationfolders/dragdrop.js' => '0a4c0ef5e27d4f4f398516ab93792f19',
		);
	}
}