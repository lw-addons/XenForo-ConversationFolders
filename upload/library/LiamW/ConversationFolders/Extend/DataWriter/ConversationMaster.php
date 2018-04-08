<?php

class LiamW_ConversationFolders_Extend_DataWriter_ConversationMaster extends XFCP_LiamW_ConversationFolders_Extend_DataWriter_ConversationMaster
{
	protected function _postSave()
	{
		parent::_postSave();

		if (XenForo_Application::isRegistered('liam_conversationFolder'))
		{
			$this->_getConversationFolderModel()->addConversationToFolder($this->get('conversation_id'),
				XenForo_Application::get('liam_conversationFolder'));
		}

		if ($recipients = $this->_getUserModel()
			->getUsersByIds($this->_newRecipients,
				array('join' => XenForo_Model_User::FETCH_USER_FULL | XenForo_Model_User::FETCH_USER_PERMISSIONS))
		)
		{
			$this->_getConversationFolderModel()->autoFileConversation($this->getMergedData(), $recipients);
		}

	}

	protected function _postDelete()
	{
		parent::_postDelete();

		$this->_getConversationFolderModel()->removeConversationFromAllFolders($this->get('conversation_id'));
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
	class XFCP_LiamW_ConversationFolders_Extend_DataWriter_ConversationMaster extends XenForo_DataWriter_ConversationMaster
	{
	}
}