<?php

namespace LiamW\ConversationFolders\DataWriter;

class ConversationFolder extends \XenForo_DataWriter
{
	protected function _getFields()
	{
		return array(
			'xf_liam_conversation_folder' => array(
				'conversation_folder_id' => array(
					'type' => self::TYPE_UINT,
					'autoIncrement' => true
				),
				'user_id' => array(
					'type' => self::TYPE_UINT,
					'required' => true,
					'verification' => array(
						'XenForo_DataWriter_Helper_User',
						'verifyUserId'
					)
				),
				'title' => array(
					'type' => self::TYPE_STRING,
					'maxLength' => 50,
					'required' => true
				),
				'description' => array(
					'type' => self::TYPE_STRING,
					'default' => ''
				),
				'conversation_count' => array(
					'type' => self::TYPE_UINT,
					'default' => 0
				)
			)
		);
	}

	protected function _getExistingData($data)
	{
		if (!$conversationFolderId = $this->_getExistingPrimaryKey($data))
		{
			return false;
		}

		return array(
			'xf_liam_conversation_folder' => $this->_getConversationFolderModel()
				->getConversationFolderById($conversationFolderId)
		);
	}

	protected function _getUpdateCondition($tableName)
	{
		return 'conversation_folder_id = ' . $this->_db->quote($this->getExisting('conversation_folder_id'));
	}

	protected function _postDelete()
	{
		$this->_db->update('xf_conversation_master', array('conversation_folder_id' => 0),
			'conversation_folder_id = ' . $this->get('conversation_folder_id'));
	}

	/**
	 * @return \LiamW\ConversationFolders\Model\ConversationFolder
	 */
	protected function _getConversationFolderModel()
	{
		return $this->getModelFromCache('\LiamW\ConversationFolders\Model\ConversationFolder');
	}
}