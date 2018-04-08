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
			'library/LiamW/ConversationFolders/DataWriter/ConversationFolder.php' => '6a6df47f61434f312666a7916d9fa605',
			'library/LiamW/ConversationFolders/Extend/ControllerPublic/Conversation.php' => '01ab5b1b85f5f5f1472e7f2f82ccb4c4',
			'library/LiamW/ConversationFolders/Extend/ControllerPublic/InlineMod/Conversation.php' => '74df5cf4c2eb83a7dec3bfc4ab24d4f4',
			'library/LiamW/ConversationFolders/Extend/DataWriter/ConversationMaster.php' => '2aa64a9720ef93ba02d6f4f01bdcff86',
			'library/LiamW/ConversationFolders/Extend/Model/Conversation.php' => 'c4c9c5a05d738adc506334de75395105',
			'library/LiamW/ConversationFolders/Extend/Model/InlineMod/Conversation.php' => '6821d4f5443504735a65e898f1cff48e',
			'library/LiamW/ConversationFolders/Extend/Route/Prefix/Conversations.php' => '9dcb1fa8cf43fa50d7f13468404d47cb',
			'library/LiamW/ConversationFolders/Installer.php' => '06bf3345de9220e68905cf123fb29154',
			'library/LiamW/ConversationFolders/Listener.php' => '0854f4ca1b43d3df62b6b35f895bb793',
			'library/LiamW/ConversationFolders/Model/ConversationFolder.php' => 'af673a3b59420d190e36bc3d7a414990',
			'library/LiamW/ConversationFolders/Option/AutoFile.php' => 'a87a2654daabd3198a32bb1a15d6f6b1',
		);
	}
}