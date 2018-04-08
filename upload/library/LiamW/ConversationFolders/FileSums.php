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
			'library/LiamW/ConversationFolders/DataWriter/ConversationFolder.php' => '067c9206992e6ac7b3810983700678c3',
			'library/LiamW/ConversationFolders/Extend/ControllerPublic/Conversation.php' => 'c41cd707399fc4e1cc477227d2778e29',
			'library/LiamW/ConversationFolders/Extend/ControllerPublic/InlineMod/Conversation.php' => 'aae70c0b5335e6f7f21f6bf96622333b',
			'library/LiamW/ConversationFolders/Extend/DataWriter/ConversationMaster.php' => 'b4134965ae6e476703ab322b9aaea4f2',
			'library/LiamW/ConversationFolders/Extend/Model/Conversation.php' => '0b458af8a9a386fc1bd18be2d468ce57',
			'library/LiamW/ConversationFolders/Extend/Model/InlineMod/Conversation.php' => '90903fb46b4265124798cb564ed4a5f6',
			'library/LiamW/ConversationFolders/Installer.php' => 'a6cc7ce6de20a31c0e3ab3a55ff7e722',
			'library/LiamW/ConversationFolders/Listener.php' => 'c7145f42c463701c3197b2540f68e741',
			'library/LiamW/ConversationFolders/Model/ConversationFolder.php' => 'edb58a84663d4720b5b7e8469eef931b',
		);
	}
}