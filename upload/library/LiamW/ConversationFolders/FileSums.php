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
			'library/LiamW/ConversationFolders/DataWriter/ConversationFolder.php' => 'ef205d662abb9b8e8e0f43ff0cc397d9',
			'library/LiamW/ConversationFolders/Extend/ControllerPublic/Conversation.php' => 'c48ecea9443c81a3aef5b9f994f8a7b8',
			'library/LiamW/ConversationFolders/Extend/ControllerPublic/InlineMod/Conversation.php' => '74df5cf4c2eb83a7dec3bfc4ab24d4f4',
			'library/LiamW/ConversationFolders/Extend/DataWriter/ConversationMaster.php' => '2aa64a9720ef93ba02d6f4f01bdcff86',
			'library/LiamW/ConversationFolders/Extend/Model/Conversation.php' => '93b7057787050810a76151f4200cab7d',
			'library/LiamW/ConversationFolders/Extend/Model/InlineMod/Conversation.php' => '6821d4f5443504735a65e898f1cff48e',
			'library/LiamW/ConversationFolders/Extend/Route/Prefix/Conversations.php' => '96be657bae4ca42c166becbaa48f0887',
			'library/LiamW/ConversationFolders/Installer.php' => '06bf3345de9220e68905cf123fb29154',
			'library/LiamW/ConversationFolders/Listener.php' => 'd5d00054a221703559cc09840f40245e',
			'library/LiamW/ConversationFolders/Model/ConversationFolder.php' => '1f2885758b8f3fad3819202b39bed350',
			'library/LiamW/ConversationFolders/Option/AutoFile.php' => 'a87a2654daabd3198a32bb1a15d6f6b1',
			'library/LiamW/ConversationFolders/WidgetRenderer/ConversationFolders.php' => 'fa1d85aa2e9d8628b32ef46ed2a9950f',
		);
	}
}