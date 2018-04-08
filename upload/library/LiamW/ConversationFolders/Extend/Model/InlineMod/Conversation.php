<?php

class LiamW_ConversationFolders_Extend_Model_InlineMod_Conversation extends XFCP_LiamW_ConversationFolders_Extend_Model_InlineMod_Conversation
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
	 * @return LiamW_ConversationFolders_Model_ConversationFolder
	 */
	protected function _getConversationFolderModel()
	{
		return $this->getModelFromCache('LiamW_ConversationFolders_Model_ConversationFolder');
	}
}

if (false)
{
	class XFCP_LiamW_ConversationFolders_Extend_Model_InlineMod_Conversation extends XenForo_Model_InlineMod_Conversation
	{
	}
}