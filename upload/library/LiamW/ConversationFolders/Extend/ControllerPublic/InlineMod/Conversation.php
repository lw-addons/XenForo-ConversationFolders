<?php

namespace LiamW\ConversationFolders\Extend\ControllerPublic\InlineMod;

class Conversation extends XFCP_Conversation
{
	public function actionMove()
	{
		if (!\XenForo_Visitor::getInstance()->hasPermission('general', 'liam_conversationFolders'))
		{
			return $this->responseNoPermission();
		}

		if ($this->isConfirmedPost())
		{
			$options = array(
				'folder' => $this->_input->filterSingle('conversation_folder_id', \XenForo_Input::UINT)
			);

			return $this->executeInlineModAction('moveConversations', $options, array('fromCookie' => false));
		}
		else
		{
			$conversationIds = $this->getInlineModIds();

			$redirect = $this->getDynamicRedirect();

			if (!$conversationIds)
			{
				return $this->responseRedirect(
					\XenForo_ControllerResponse_Redirect::SUCCESS,
					$redirect
				);
			}

			$viewParams = array(
				'conversationIds' => $conversationIds,
				'conversationCount' => count($conversationIds),
				'redirect' => $redirect,
				'conversationFolders' => $this->_getConversationFolderModel()->getAllConversationFolders()
			);

			return $this->responseView('',
				'liam_conversationFolders_inline_mod_move', $viewParams);
		}
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
	class XFCP_Conversation extends \XenForo_ControllerPublic_InlineMod_Conversation
	{
	}
}