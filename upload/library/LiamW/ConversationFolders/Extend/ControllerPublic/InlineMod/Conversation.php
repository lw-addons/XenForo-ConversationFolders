<?php

class LiamW_ConversationFolders_Extend_ControllerPublic_InlineMod_Conversation extends XFCP_LiamW_ConversationFolders_Extend_ControllerPublic_InlineMod_Conversation
{
	public function actionMove()
	{
		if (!XenForo_Visitor::getInstance()->hasPermission('general', 'liam_conversationFolders'))
		{
			return $this->responseNoPermission();
		}

		if ($this->isConfirmedPost())
		{
			$options = array(
				'folder' => $this->_input->filterSingle('conversation_folder_id', XenForo_Input::UINT)
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
					XenForo_ControllerResponse_Redirect::SUCCESS,
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
	 * @return LiamW_ConversationFolders_Model_ConversationFolder
	 */
	protected function _getConversationFolderModel()
	{
		return $this->getModelFromCache('LiamW_ConversationFolders_Model_ConversationFolder');
	}
}

if (false)
{
	class XFCP_LiamW_ConversationFolders_Extend_ControllerPublic_InlineMod_Conversation extends XenForo_ControllerPublic_InlineMod_Conversation
	{
	}
}