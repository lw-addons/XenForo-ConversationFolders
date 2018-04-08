<?php

namespace LiamW\ConversationFolders;

class FileSums
{
	public static function addHashes(\XenForo_ControllerAdmin_Abstract $controller, array &$hashes)
	{
		$hashes += self::getHashes();
	}

	/**
	 * @return array
	 */
	public static function getHashes()
	{
		return array(
			'library/LiamW/ConversationFolders/DataWriter/ConversationFolder.php' => '73f5808034ffdc2dc624701e14ed2bc8',
			'library/LiamW/ConversationFolders/Extend/ControllerPublic/Conversation.php' => 'b20bf6d8e77cf58572a76ac086266b08',
			'library/LiamW/ConversationFolders/Extend/ControllerPublic/InlineMod/Conversation.php' => 'aae70c0b5335e6f7f21f6bf96622333b',
			'library/LiamW/ConversationFolders/Extend/DataWriter/ConversationMaster.php' => 'b4134965ae6e476703ab322b9aaea4f2',
			'library/LiamW/ConversationFolders/Extend/Model/Conversation.php' => '8431fd1157f6c08c51c823d5d7dad7bb',
			'library/LiamW/ConversationFolders/Extend/Model/InlineMod/Conversation.php' => '90903fb46b4265124798cb564ed4a5f6',
			'library/LiamW/ConversationFolders/Installer.php' => '83273795eb31ab4344b19e22ef33c45c',
			'library/LiamW/ConversationFolders/Listener.php' => 'c7145f42c463701c3197b2540f68e741',
			'library/LiamW/ConversationFolders/Model/ConversationFolder.php' => 'c7926f4596179bb16bf0b8f9169b3f47',
		);
	}
}