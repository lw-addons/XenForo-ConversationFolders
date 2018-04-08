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
			'library/LiamW/ConversationFolders/DataWriter/ConversationFolder.php' => '7005c6dbe48f549d917bf838986b9bcc',
			'library/LiamW/ConversationFolders/Extend/ControllerPublic/Conversation.php' => 'b393e8928abbf2213e39dc8bd3a26764',
			'library/LiamW/ConversationFolders/Extend/ControllerPublic/InlineMod/Conversation.php' => 'aae70c0b5335e6f7f21f6bf96622333b',
			'library/LiamW/ConversationFolders/Extend/DataWriter/ConversationMaster.php' => '907281494f1a7f531b834c32fd18b43c',
			'library/LiamW/ConversationFolders/Extend/Model/Conversation.php' => 'eaca48604e5a38747b314dc6268b7f99',
			'library/LiamW/ConversationFolders/Extend/Model/InlineMod/Conversation.php' => '90903fb46b4265124798cb564ed4a5f6',
			'library/LiamW/ConversationFolders/Extend/Route/Prefix/Conversations.php' => 'a8c2333df07b6a8604704ed874a0b079',
			'library/LiamW/ConversationFolders/Installer.php' => '9f73fb3b6253d7069923c8e5c6649f84',
			'library/LiamW/ConversationFolders/Listener.php' => '8b4cffefecca1f005a83a9e9ef995bca',
			'library/LiamW/ConversationFolders/Model/ConversationFolder.php' => '47c91a6c1c0e0b9a468eac9f1e367f26',
		);
	}
}