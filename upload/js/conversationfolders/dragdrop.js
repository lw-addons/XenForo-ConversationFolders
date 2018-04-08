/**
 * @param {jQuery} $
 *            jQuery Object
 */
!function ($, window, document, _undefined)
{

	XenForo.ConversationActivateDrag = function ($conversationListItem)
	{
		this.__construct($conversationListItem);
	};

	XenForo.ConversationActivateDrag.prototype =
	{

		__construct: function ($conversationList)
		{
			$conversationList.on('dragstart', $.context(this, 'conversationDragStart'));
			$conversationList.on('dragend', $.context(this, 'conversationDragEnd'));
			$conversationList.attr('draggable', 'true');

			this.$conversationList = $($conversationList);
		},

		conversationDragStart: function (e)
		{
			e.originalEvent.dataTransfer.setData("text", this.$conversationList.attr('id'));
			e.originalEvent.dataTransfer.effectAllowed = 'move';

			$('.folderList').addClass('DragInProgress');
		},

		conversationDragEnd: function (e)
		{
			$('.folderList').removeClass('DragInProgress');
		}
	};

	XenForo.ConversationActivateDrop = function ($folderListItem)
	{
		this.__construct($folderListItem);
	};

	XenForo.ConversationActivateDrop.prototype =
	{
		__construct: function ($folderListItem)
		{
			console.log($folderListItem);

			$folderListItem.on('dragover', $.context(this, 'dragOver'));
			$folderListItem.on('dragenter', $.context(this, 'dragEnter'));
			$folderListItem.on('dragleave', $.context(this, 'dragLeave'));
			$folderListItem.on('drop', $.context(this, 'drop'));

			this.$folderListItem = $($folderListItem);
		},

		dragOver: function (e)
		{
			e.preventDefault();
		},

		dragEnter: function (e)
		{
			$(e.originalEvent.target).css('font-size', '200%');
		},

		dragLeave: function (e)
		{
			$(e.originalEvent.target).css('font-size', '100%');
		},

		drop: function (e)
		{
			$('.folderList').addClass('DragInProgress');

			var folderId = this.$folderListItem.data('id');
			var Id = e.originalEvent.dataTransfer.getData("text");
			var conversationId = Id.substr(13);

			this.$folderListItem.css('font-size', '100%');

			XenForo.ajax('conversations/move', {
				'conversation_id': conversationId,
				'conversation_folder_id': folderId,
				'_xfConfirm': 1
			}, function (ajaxData)
			{
				if (XenForo.hasResponseError(ajaxData))
					return false;

				if (XenForo.hasTemplateHtml(ajaxData) && !$.folderSelected)
				{
					$templateHtml = $(ajaxData.templateHtml);
					new XenForo.ConversationActivateDrag($templateHtml);

					$templateHtml.xfInsert('replaceAll', $('#' + Id));
				}
				else if ($.folderSelected)
				{
					$('#' + Id).xfRemove();
				}

				if (ajaxData.folderCounts)
				{
					$.each(ajaxData.folderCounts, function (folderId, conversationCounts)
					{
						var $countSpan = $('#folder-' + folderId).find('.count');

						$countSpan.find('.unread').text(conversationCounts['unread_count']);
						$countSpan.find('.total').text('(' + conversationCounts['conversation_count'] + ')');
					});
				}
			});
		}
	};

	XenForo.register('.ConversationListItem', 'XenForo.ConversationActivateDrag');
	XenForo.register('.folderList .FolderLink', 'XenForo.ConversationActivateDrop');
}
(jQuery, this, document);