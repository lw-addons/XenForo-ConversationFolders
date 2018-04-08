<?php

namespace LiamW\ConversationFolders\Extend\ControllerPublic;

class Conversation extends XFCP_Conversation
{
	protected function _postDispatch($controllerResponse, $controllerName, $action)
	{
		parent::_postDispatch($controllerResponse, $controllerName, $action);

		if ($controllerResponse instanceof \XenForo_ControllerResponse_View)
		{
			$controllerResponse->params['canUseConversationFolders'] = \XenForo_Visitor::getInstance()
				->hasPermission('general', 'liam_conversationFolders');
		}
	}

	public function actionIndex()
	{
		if ($this->_input->filterSingle('conversation_folder_id', \XenForo_Input::UINT))
		{
			return $this->responseReroute('XenForo_ControllerPublic_Conversation', 'folder');
		}

		return parent::actionIndex();
	}

	public function actionAdd()
	{
		$parent = parent::actionAdd();

		if ($parent instanceof \XenForo_ControllerResponse_View)
		{
			$parent->params['conversationFolders'] = $this->_getConversationFolderModel()->getAllConversationFolders();
		}

		return $parent;
	}

	public function actionInsert()
	{
		if (\XenForo_Visitor::getInstance()->hasPermission('general', 'liam_conversationFolders'))
		{
			\XenForo_Application::set('liam_conversationFolder',
				$this->_input->filterSingle('conversation_folder_id', \XenForo_Input::UINT));
		}

		return parent::actionInsert();
	}

	public function actionView()
	{
		$parent = parent::actionView();

		if ($parent instanceof \XenForo_ControllerResponse_View)
		{
			$parent->params['conversationFolders'] = $this->_getConversationFolderModel()->getAllConversationFolders();
		}

		return $parent;
	}

	public function actionMove()
	{
		if (!\XenForo_Visitor::getInstance()->hasPermission('general', 'liam_conversationFolders'))
		{
			return $this->responseNoPermission();
		}

		$conversationId = $this->_input->filterSingle('conversation_id', \XenForo_Input::UINT);
		$conversation = $this->_getConversationOrError($conversationId);

		if ($this->isConfirmedPost())
		{
			$folderId = $this->_input->filterSingle('conversation_folder_id', \XenForo_Input::UINT);

			$this->_getConversationFolderModel()
				->moveConversation($conversationId, $folderId);

			return $this->responseRedirect(\XenForo_ControllerResponse_Redirect::SUCCESS,
				\XenForo_Link::buildPublicLink('conversations', $conversation));
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
		if (!\XenForo_Visitor::getInstance()->hasPermission('general', 'liam_conversationFolders'))
		{
			return $this->responseRedirect(\XenForo_ControllerResponse_Redirect::RESOURCE_CANONICAL,
				\XenForo_Link::buildPublicLink('conversations'));
		}

		$conversationFolderId = $this->_input->filterSingle('conversation_folder_id', \XenForo_Input::UINT);

		$viewParams = $this->_getConversationListData(array(
			'conversation_folder_id' => $conversationFolderId
		));

		$viewParams['selectedFolder'] = $conversationFolderId;

		$this->canonicalizePageNumber(
			$viewParams['page'], $viewParams['conversationsPerPage'], $viewParams['totalConversations'],
			'conversations'
		);

		return $this->responseView('XenForo_ViewPublic_Conversation_List', 'conversation_list', $viewParams);
	}

	public function actionFolderNew()
	{
		if (!\XenForo_Visitor::getInstance()->hasPermission('general', 'liam_conversationFolders'))
		{
			return $this->responseNoPermission();
		}

		return $this->responseView('', 'liam_conversationFolders_edit');
	}

	public function actionFolderEdit()
	{
		if (!\XenForo_Visitor::getInstance()->hasPermission('general', 'liam_conversationFolders'))
		{
			return $this->responseNoPermission();
		}

		$conversationFolderId = $this->_input->filterSingle('conversation_folder_id', \XenForo_Input::UINT);
		$conversationFolder = $this->_getConversationFolderOrError($conversationFolderId);

		if ($conversationFolder['user_id'] != \XenForo_Visitor::getUserId())
		{
			return $this->responseNoPermission();
		}

		$viewParams = array(
			'folder' => $conversationFolder
		);

		return $this->responseView('', 'liam_conversationFolders_edit', $viewParams);
	}

	public function actionFolderSave()
	{
		if (!\XenForo_Visitor::getInstance()->hasPermission('general', 'liam_conversationFolders'))
		{
			return $this->responseNoPermission();
		}

		$conversationFolderId = $this->_input->filterSingle('conversation_folder_id', \XenForo_Input::UINT);

		$input = $this->_input->filter(array(
			'title' => \XenForo_Input::STRING,
			'description' => \XenForo_Input::STRING
		));

		$dw = \XenForo_DataWriter::create('\LiamW\ConversationFolders\DataWriter\ConversationFolder');
		if ($conversationFolderId)
		{
			$conversationFolder = $this->_getConversationOrError($conversationFolderId);

			if ($conversationFolder['user_id'] != \XenForo_Visitor::getUserId())
			{
				return $this->responseNoPermission();
			}

			$dw->setExistingData($conversationFolder);
		}
		$dw->set('user_id', \XenForo_Visitor::getUserId());
		$dw->bulkSet($input);
		$dw->save();

		return $this->responseRedirect(\XenForo_ControllerResponse_Redirect::SUCCESS,
			\XenForo_Link::buildPublicLink('conversations'));
	}

	public function actionFolderDelete()
	{
		if (!\XenForo_Visitor::getInstance()->hasPermission('general', 'liam_conversationFolders'))
		{
			return $this->responseNoPermission();
		}

		$conversationFolderId = $this->_input->filterSingle('conversation_folder_id', \XenForo_Input::UINT);
		$conversationFolder = $this->_getConversationFolderOrError($conversationFolderId);

		if ($conversationFolder['user_id'] != \XenForo_Visitor::getUserId())
		{
			return $this->responseNoPermission();
		}

		if ($this->isConfirmedPost())
		{
			$dw = \XenForo_DataWriter::create('\LiamW\ConversationFolders\DataWriter\ConversationFolder');
			$dw->setExistingData($conversationFolder);
			$dw->delete();

			return $this->responseRedirect(\XenForo_ControllerResponse_Redirect::SUCCESS,
				\XenForo_Link::buildPublicLink('conversations'));
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

		$fetchOptions['join'] |= \LiamW\ConversationFolders\Extend\Model\Conversation::FETCH_FOLDER;

		return $fetchOptions;
	}

	protected function _getConversationListData(array $extraConditions = array())
	{
		$conversationFolders = $this->_getConversationFolderModel()
			->getAllConversationFolders();

		$viewParams = array(
			'conversationFolders' => $conversationFolders,
			'completeConversationCount' => $this->_getConversationModel()
				->countConversationsForUser(\XenForo_Visitor::getUserId()),
			'unfolderedConversationsCount' => $this->_getConversationModel()
				->countConversationsForUser(\XenForo_Visitor::getUserId(), array('conversation_folder_id' => null)),
			'pageNavParams' => array(
				'_params' => array(
					'conversation_folder_id' => $this->_input->filterSingle('conversation_folder_id',
						\XenForo_Input::UINT)
				)
			)
		);

		if (!isset($extraConditions['conversation_folder_id']) && !\XenForo_Application::getOptions()->liam_conversationFolders_show_all)
		{
			$extraConditions['conversation_folder_id'] = null;
		}

		return array_merge_recursive(parent::_getConversationListData($extraConditions), $viewParams);
	}

	protected function _getConversationFolderOrError($conversationFolderId = null)
	{
		$conversationFolder = $this->_getConversationFolderModel()->getConversationFolderById($conversationFolderId);

		if (!$conversationFolder)
		{
			throw $this->responseException($this->responseError(new \XenForo_Phrase('liam_conversationFolder_folder_not_found')));
		}

		return $conversationFolder;
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
	class XFCP_Conversation extends \XenForo_ControllerPublic_Conversation
	{
	}
}