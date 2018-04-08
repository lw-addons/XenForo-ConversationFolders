<?php

class LiamW_ConversationFolders_Extend_Route_Prefix_Conversations extends XFCP_LiamW_ConversationFolders_Extend_Route_Prefix_Conversations
{
	public function match($routePath, Zend_Controller_Request_Http $request, XenForo_Router $router)
	{
		if (preg_match('/\.?\d+\/folder\/?/', $routePath))
		{
			$action = $router->resolveActionWithIntegerParam($routePath, $request, 'conversation_folder_id');
			$action = $router->resolveActionAsPageNumber($action, $request);

			return $router->getRouteMatch('XenForo_ControllerPublic_Conversation', $action, 'inbox');
		}

		return parent::match($routePath, $request, $router);
	}

	public function buildLink($originalPrefix, $outputPrefix, $action, $extension, $data, array &$extraParams)
	{
		if (isset($data['conversation_folder_id']) && strpos($action, 'folder') !== false)
		{
			$action = XenForo_Link::getPageNumberAsAction($action, $extraParams);

			$titleField = isset($data['folder_title']) ? 'folder_title' : 'title';

			return XenForo_Link::buildBasicLinkWithIntegerParam($outputPrefix, $action, $extension, $data,
				'conversation_folder_id', $titleField);
		}

		return parent::buildLink($originalPrefix, $outputPrefix, $action, $extension, $data,
			$extraParams);
	}
}

if (false)
{
	class XFCP_LiamW_ConversationFolders_Extend_Route_Prefix_Conversations extends XenForo_Route_Prefix_Conversations
	{
	}
}