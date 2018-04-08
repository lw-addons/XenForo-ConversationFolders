<?php

namespace LiamW\ConversationFolders\Extend\Model\InlineMod;

class Conversation extends XFCP_Conversation
{
	public function moveConversations(array $conversationIds, array $options = array(), &$errorKey = '', array $viewingUser = null)
	{
		foreach ($conversationIds as $conversationId)
		{
			$this->_getConversationFolderModel()
				->moveConversation($conversationId, $options['folder']);
		}

		return true;
	}

	/**
	 * @return \LiamW\ConversationFolders\Model\ConversationFolder
	 */
	protected function _getConversationFolderModel()
	{
		return $this->getModelFromCache('LiamW\ConversationFolders\Model\ConversationFolder');
	}
}

if (false)
{
	class XFCP_Conversation extends \XenForo_Model_InlineMod_Conversation
	{
	}
}