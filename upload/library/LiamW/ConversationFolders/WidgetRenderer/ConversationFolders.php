<?php

class LiamW_ConversationFolders_WidgetRenderer_ConversationFolders extends WidgetFramework_WidgetRenderer
{
	/**
	 * Required method: define basic configuration of the renderer.
	 * Available configuration parameters:
	 *    - name: The display name of the renderer
	 *    - options: An array of renderer's options
	 *    - useCache: Flag to determine the renderer can be cached or not
	 *    - useUserCache: Flag to determine the renderer needs to be cached by an
	 *                    user-basis.
	 *                    Internally, this is implemented by getting the current user permission
	 *                    combination id (not the user id as normally expected). This is done to
	 *                    make sure the cache is used effectively
	 *    - useLiveCache: Flag to determine the renderer wants to by pass writing to
	 *                    database
	 *                    when it's being cached. This may be crucial if the renderer does a lot
	 *                    of thing on a big board. It's recommended to use a option for this
	 *                    because not all forum owner has a live cache system setup
	 *                    (XCache/memcached)
	 *    - cacheSeconds: A numeric value to specify the maximum age of the cache (in
	 *                    seconds).
	 *                    If the cache is too old, the widget will be rendered from scratch
	 *    - useWrapper: Flag to determine the widget should be wrapped with a wrapper.
	 *                    Renderers
	 *                    that support wrapper will have an additional benefits of tabs: only
	 *                    wrapper-enabled widgets will be possible to use in tabbed interface
	 */
	protected function _getConfiguration()
	{
		return array(
			'name' => new XenForo_Phrase('liam_conversationFolders'),
			'useCache' => false,
			'useWrapper' => false
		);
	}

	/**
	 * Required method: get the template title of the options template (to be used in
	 * AdminCP).
	 * If this is not used, simply returns false.
	 */
	protected function _getOptionsTemplate()
	{
		return false;
	}

	protected function _getRenderTemplate(array $widget, $positionCode, array $params)
	{
		return 'liam_conversationFolders_wf_block';
	}

	protected function _render(array $widget, $positionCode, array $params, XenForo_Template_Abstract $renderTemplateObject)
	{
		/** @var LiamW_ConversationFolders_Model_ConversationFolder $conversationFolderModel */
		$conversationFolderModel = XenForo_Model::create('LiamW_ConversationFolders_Model_ConversationFolder');

		$conversationFolders = $conversationFolderModel
			->getAllConversationFolders();

		/** @var XenForo_Model_Conversation $conversationModel */
		$conversationModel = XenForo_Model::create('XenForo_Model_Conversation');

		$renderTemplateObject->setParams(array(
			'conversationFolders' => $conversationFolders,
			'completeConversationCount' => $conversationModel
				->countConversationsForUser(XenForo_Visitor::getUserId()),
			'unfolderedConversationsCount' => $conversationModel
				->countConversationsForUser(XenForo_Visitor::getUserId(), array('conversation_folder_id' => 0))
		));

		return $renderTemplateObject->render();
	}

}