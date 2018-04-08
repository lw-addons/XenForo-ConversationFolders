<?php

class LiamW_ConversationFolders_Extend_ControllerPublic_Conversation extends XFCP_LiamW_ConversationFolders_Extend_ControllerPublic_Conversation
{
	protected function _postDispatch($controllerResponse, $controllerName, $action)
	{
		parent::_postDispatch($controllerResponse, $controllerName, $action);

		if ($controllerResponse instanceof XenForo_ControllerResponse_View)
		{
			$controllerResponse->params['canUseConversationFolders'] = XenForo_Visitor::getInstance()
				->hasPermission('general', 'liam_conversationFolders');
		}
	}

	public function actionAdd()
	{
		$parent = parent::actionAdd();

		if ($parent instanceof XenForo_ControllerResponse_View)
		{
			$parent->params['conversationFolders'] = $this->_getConversationFolderModel()->getAllConversationFolders();
		}

		return $parent;
	}

	public function actionInsert()
	{
		if (XenForo_Visitor::getInstance()->hasPermission('general', 'liam_conversationFolders'))
		{
			XenForo_Application::set('liam_conversationFolder',
				$this->_input->filterSingle('conversation_folder_id', XenForo_Input::UINT));
		}

		return parent::actionInsert();
	}

	public function actionView()
	{
		$parent = parent::actionView();

		if ($parent instanceof XenForo_ControllerResponse_View)
		{
			$parent->params['conversationFolders'] = $this->_getConversationFolderModel()->getAllConversationFolders();
		}

		return $parent;
	}

	public function actionMove()
	{
		if (!XenForo_Visitor::getInstance()->hasPermission('general', 'liam_conversationFolders'))
		{
			return $this->responseNoPermission();
		}

		$conversationId = $this->_input->filterSingle('conversation_id', XenForo_Input::UINT);
		$conversation = $this->_getConversationOrError($conversationId);

		if ($this->isConfirmedPost())
		{
			$folderId = $this->_input->filterSingle('conversation_folder_id', XenForo_Input::UINT);

			$this->_getConversationFolderModel()
				->moveConversation($conversationId, $folderId);

			return $this->responseRedirect(XenForo_ControllerResponse_Redirect::SUCCESS,
				XenForo_Link::buildPublicLink('conversations', $conversation));
		}
		else
		{
			$viewParams = array(
				'conversationFolders' => $this->_getConversationFolderModel()->getAllConversationFolders(),
				'conversation' => $conversation
			);

			return $this->responseView('',
				'liam_conversationFolders_move', $viewParams);
		}
	}

	public function actionFolder()
	{
		if (!XenForo_Visitor::getInstance()
			->hasPermission('general', 'liam_conversationFolders')
		)
		{
			return $this->responseNoPermission();
		}

		$conversationFolder = $this->_getConversationFolderOrError();

		$this->_assertCanUseFolder($conversationFolder);

		$this->canonicalizeRequestUrl(XenForo_Link::buildPublicLink('conversations/folder', $conversationFolder));

		$viewParams = $this->_getConversationListData(array(
			'conversation_folder_id' => $conversationFolder['conversation_folder_id']
		));

		$viewParams['selectedFolder'] = $conversationFolder['conversation_folder_id'];

		$this->canonicalizePageNumber(
			$viewParams['page'], $viewParams['conversationsPerPage'], $viewParams['totalConversations'],
			'conversations'
		);

		return $this->responseView('XenForo_ViewPublic_Conversation_List', 'conversation_list', $viewParams);
	}

	public function actionFolderNew()
	{
		if (!XenForo_Visitor::getInstance()->hasPermission('general', 'liam_conversationFolders'))
		{
			return $this->responseNoPermission();
		}

		$viewParams = array(
			'canUseAutoFile' => XenForo_Visitor::getInstance()->hasPermission('general', 'conversationFolders_afile')
		);

		return $this->responseView('', 'liam_conversationFolders_edit', $viewParams);
	}

	public function actionFolderEdit()
	{
		if (!XenForo_Visitor::getInstance()->hasPermission('general', 'liam_conversationFolders'))
		{
			return $this->responseNoPermission();
		}

		$conversationFolderId = $this->_input->filterSingle('conversation_folder_id', XenForo_Input::UINT);
		$conversationFolder = $this->_getConversationFolderOrError($conversationFolderId);

		$this->_assertCanUseFolder($conversationFolder);

		if ($conversationFolder['user_id'] != XenForo_Visitor::getUserId())
		{
			return $this->responseNoPermission();
		}

		$viewParams = array(
			'folder' => $conversationFolder,
			'canUseAutoFile' => XenForo_Visitor::getInstance()->hasPermission('general', 'conversationFolders_afile')
		);

		return $this->responseView('', 'liam_conversationFolders_edit', $viewParams);
	}

	public function actionFolderSave()
	{
		if (!XenForo_Visitor::getInstance()->hasPermission('general', 'liam_conversationFolders'))
		{
			return $this->responseNoPermission();
		}

		$conversationFolderId = $this->_input->filterSingle('conversation_folder_id', XenForo_Input::UINT);

		$input = $this->_input->filter(array(
			'title' => XenForo_Input::STRING,
			'description' => XenForo_Input::STRING,
		));

		if (XenForo_Visitor::getInstance()->hasPermission('general', 'conversationFolders_afile'))
		{
			$input += $this->_input->filter(array(
				'auto_file_regex' => XenForo_Input::STRING,
				'auto_file_weight' => XenForo_Input::UINT
			));
		}

		$dw = XenForo_DataWriter::create('LiamW_ConversationFolders_DataWriter_ConversationFolder');
		if ($conversationFolderId)
		{
			$conversationFolder = $this->_getConversationFolderOrError($conversationFolderId);

			$this->_assertCanUseFolder($conversationFolder);

			$dw->setExistingData($conversationFolder);
		}
		$dw->set('user_id', XenForo_Visitor::getUserId());
		$dw->bulkSet($input);
		$dw->save();

		return $this->responseRedirect(XenForo_ControllerResponse_Redirect::SUCCESS,
			XenForo_Link::buildPublicLink('conversations/folder', $dw->getMergedData()));
	}

	public function actionFolderDelete()
	{
		if (!XenForo_Visitor::getInstance()->hasPermission('general', 'liam_conversationFolders'))
		{
			return $this->responseNoPermission();
		}

		$conversationFolderId = $this->_input->filterSingle('conversation_folder_id', XenForo_Input::UINT);
		$conversationFolder = $this->_getConversationFolderOrError($conversationFolderId);

		$this->_assertCanUseFolder($conversationFolder);

		if ($this->isConfirmedPost())
		{
			$dw = XenForo_DataWriter::create('LiamW_ConversationFolders_DataWriter_ConversationFolder');
			$dw->setExistingData($conversationFolder);
			$dw->delete();

			return $this->responseRedirect(XenForo_ControllerResponse_Redirect::SUCCESS,
				XenForo_Link::buildPublicLink('conversations'));
		}
		else
		{
			$viewParams = array(
				'conversationFolder' => $conversationFolder
			);

			return $this->responseView('', 'liam_conversationFolders_delete', $viewParams);
		}
	}

	protected function _getListFetchOptions()
	{
		$fetchOptions = parent::_getListFetchOptions();

		if (!isset($fetchOptions['join']))
		{
			$fetchOptions['join'] = 0;
		}

		$fetchOptions['join'] |= LiamW_ConversationFolders_Extend_Model_Conversation::FETCH_FOLDER;

		return $fetchOptions;
	}

	protected function _getConversationListData(array $extraConditions = array())
	{
		$conversationFolders = $this->_getConversationFolderModel()
			->getAllConversationFolders();

		$viewParams = array(
			'conversationFolders' => $conversationFolders,
			'completeConversationCount' => $this->_getConversationModel()
				->countConversationsForUser(XenForo_Visitor::getUserId()),
			'unfolderedConversationsCount' => $this->_getConversationModel()
				->countConversationsForUser(XenForo_Visitor::getUserId(), array('conversation_folder_id' => 0))
		);

		if ($conversationFolderId = $this->_input->filterSingle('conversation_folder_id',
			XenForo_Input::UINT)
		)
		{
			$viewParams['pageNavParams'] = array(
				'_params' => array(
					'conversation_folder_id' => $this->_input->filterSingle('conversation_folder_id',
						XenForo_Input::UINT)
				)
			);
		}

		if (!isset($extraConditions['conversation_folder_id']) && !XenForo_Application::getOptions()->liam_conversationFolders_show_all)
		{
			$extraConditions['conversation_folder_id'] = 0;
		}

		return array_merge_recursive(parent::_getConversationListData($extraConditions), $viewParams);
	}

	protected function _getConversationFolderOrError($conversationFolderId = null)
	{
		if ($conversationFolderId == null)
		{
			$conversationFolderId = $this->_input->filterSingle('conversation_folder_id', XenForo_Input::UINT);
		}

		$conversationFolder = $this->_getConversationFolderModel()->getConversationFolderById($conversationFolderId);

		if (!$conversationFolder)
		{
			throw $this->responseException($this->responseError(new XenForo_Phrase('liam_conversationFolder_folder_not_found')));
		}

		return $conversationFolder;
	}

	protected function _assertCanUseFolder(array $conversationFolder)
	{
		if (!$this->_getConversationFolderModel()->canUseConversationFolder($conversationFolder, $errorPhraseKey))
		{
			throw $this->getErrorOrNoPermissionResponseException($errorPhraseKey);
		}
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
	class XFCP_LiamW_ConversationFolders_Extend_ControllerPublic_Conversation extends XenForo_ControllerPublic_Conversation
	{
	}
}