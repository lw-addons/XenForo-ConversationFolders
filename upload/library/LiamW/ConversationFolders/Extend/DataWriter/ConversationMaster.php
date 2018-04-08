<?php

namespace LiamW\ConversationFolders\Extend\DataWriter;

class ConversationMaster extends XFCP_ConversationMaster
{
	protected function _postSave()
	{
		parent::_postSave();

		if (\XenForo_Application::isRegistered('liam_conversationFolder'))
		{
			$this->_getConversationFolderModel()->addConversationToFolder($this->get('conversation_id'),
				\XenForo_Application::get('liam_conversationFolder'));
		}
	}

	protected function _postDelete()
	{
		parent::_postDelete();

		$this->_getConversationFolderModel()->removeConversationFromAllFolders($this->get('conversation_id'));
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
	class XFCP_ConversationMaster extends \XenForo_DataWriter_ConversationMaster
	{
	}
}