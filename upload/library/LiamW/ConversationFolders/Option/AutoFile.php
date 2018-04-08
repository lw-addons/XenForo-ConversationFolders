<?php

class LiamW_ConversationFolders_Option_AutoFile
{
	public static function verifyAutoFileOption($autoFileSubstring, XenForo_DataWriter $dw, $optionId)
	{
		if (!$autoFileSubstring)
		{
			XenForo_Model::create('LiamW_ConversationFolders_Model_ConversationFolder')->resetAutoFileSystem();
		}
	}
}