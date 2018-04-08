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
				'auto_file_regex' => array(
					'type' => self::TYPE_STRING,
					'default' => '',
					'verification' => array(
						$this,
						'_verifyRegex'
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
			print 'fal';

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

	protected function _verifyRegex($regex)
	{
		if ($this->isChanged('auto_file_regex') && $regex !== '')
		{
			if (preg_match('/\W[\s\w]*e[\s\w]*$/', $this->get('find')))
			{
				$this->error(new \XenForo_Phrase('please_enter_valid_regular_expression'), 'auto_file_regex');

				return false;
			}
			else
			{
				try
				{
					preg_match($regex, '');
				} catch (\ErrorException $e)
				{
					$this->error(new \XenForo_Phrase('please_enter_valid_regular_expression'), 'auto_file_regex');

					return false;
				}
			}
		}

		return true;
	}

	/**
	 * @return \LiamW\ConversationFolders\Model\ConversationFolder
	 */
	protected function _getConversationFolderModel()
	{
		return $this->getModelFromCache('\LiamW\ConversationFolders\Model\ConversationFolder');
	}
}