<?php

class LiamW_ConversationFolders_DataWriter_ConversationFolder extends XenForo_DataWriter
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
				'auto_file_regex' => array(
					'type' => self::TYPE_STRING,
					'default' => '',
					'maxLength' => 255,
					'verification' => array(
						$this,
						'_verifyAutoFileString'
					)
				),
				'auto_file_weight' => array(
					'type' => self::TYPE_UINT,
					'default' => 0
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
		$this->_getConversationFolderModel()->removeAllConversationsFromFolder($this->get('conversation_folder_id'));
	}

	protected function _verifyAutoFileString(&$autoFileString)
	{
		$autoFileString = trim(strval($autoFileString));

		if (XenForo_Application::getOptions()->liam_conversationFolders_auto_file_substring)
		{
			return true;
		}

		if ($autoFileString !== '')
		{
			if (preg_match('/\W[\s\w]*e[\s\w]*$/', $autoFileString))
			{
				$this->error(new XenForo_Phrase('please_enter_valid_regular_expression'), 'auto_file_regex');

				return false;
			}
			else
			{
				try
				{
					preg_match($autoFileString, '');
				} catch (Exception $e)
				{
					$this->error(new XenForo_Phrase('please_enter_valid_regular_expression'), 'auto_file_regex');

					return false;
				}
			}
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