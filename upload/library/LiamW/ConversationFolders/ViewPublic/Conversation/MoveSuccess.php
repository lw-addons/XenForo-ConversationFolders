<?php

class LiamW_ConversationFolders_ViewPublic_Conversation_MoveSuccess extends XenForo_ViewPublic_Base
{
	public function renderJson()
	{
		/** @var XenForo_ViewRenderer_Json $renderer */
		$renderer = $this->_renderer;

		$folderCounts = $this->_params['folderCounts'];
		unset($this->_params['folderCounts']);

		$output = $renderer->getDefaultOutputArray(get_class($this), $this->_params, $this->_templateName);

		$output['folderCounts'] = $folderCounts;

		return XenForo_ViewRenderer_Json::jsonEncodeForOutput($output);
	}
}